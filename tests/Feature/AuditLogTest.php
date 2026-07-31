<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use App\Services\AuditLogService;
use App\Support\RbacPermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_actions_create_safe_audit_logs_for_approved_modules(): void
    {
        Storage::fake(Document::DISK);

        $admin = User::factory()->admin()->create([
            'name' => 'Audit Admin',
            'email' => 'audit-admin@example.com',
        ]);
        $client = Client::factory()->create();
        $project = Project::factory()->for($client)->create([
            'project_value' => '10000.00',
            'progress' => 0,
        ]);
        $invoice = Invoice::factory()->for($project)->create([
            'amount' => '1000.00',
            'status' => Invoice::STATUS_SENT,
        ]);

        AuditLog::query()->delete();

        $this->actingAs($admin)->post(route('clients.store'), $this->clientPayload([
            'name' => 'Audited Client',
        ]))->assertRedirect();
        $auditedClient = Client::where('name', 'Audited Client')->firstOrFail();

        $this->actingAs($admin)->put(route('clients.update', $auditedClient), $this->clientPayload([
            'name' => 'Audited Client Updated',
        ]))->assertRedirect();
        $this->actingAs($admin)->delete(route('clients.destroy', $auditedClient))->assertRedirect();

        $this->actingAs($admin)->post(route('projects.store'), $this->projectPayload($client))->assertRedirect();
        $this->actingAs($admin)->post(route('invoices.payments.store', $invoice), [
            'payment_date' => '2026-08-12',
            'amount' => '250.00',
            'method' => 'Bank Transfer',
            'reference' => 'PAY-001',
            'notes' => 'Partial payment.',
        ])->assertRedirect();
        $payment = Payment::where('reference', 'PAY-001')->firstOrFail();

        $this->actingAs($admin)->post(route('expenses.store'), $this->expensePayload($project))->assertRedirect();
        $expense = Expense::where('category', 'Audit Expense')->firstOrFail();
        $this->actingAs($admin)->delete(route('expenses.destroy', $expense))->assertRedirect();

        $this->actingAs($admin)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->createWithContent('brief.txt', 'file content must stay out of logs'),
        ])->assertRedirect();
        $document = Document::where('original_filename', 'brief.txt')->firstOrFail();
        $this->actingAs($admin)->delete(route('documents.destroy', $document))->assertRedirect();

        $this->actingAs($admin)->post(route('projects.milestones.store', $project), $this->milestonePayload())->assertRedirect();
        $milestone = ProjectMilestone::where('title', 'Audit Milestone')->firstOrFail();
        $this->actingAs($admin)->delete(route('projects.milestones.destroy', [$project, $milestone]))->assertRedirect();

        $this->actingAs($admin)->post(route('projects.tasks.store', $project), $this->taskPayload())->assertRedirect();
        $task = ProjectTask::where('title', 'Audit Task')->firstOrFail();
        $this->actingAs($admin)->delete(route('projects.tasks.destroy', [$project, $task]))->assertRedirect();

        $this->assertAuditExists('created', Client::class, $auditedClient->id, $admin);
        $this->assertAuditExists('updated', Client::class, $auditedClient->id, $admin);
        $this->assertAuditExists('deleted', Client::class, $auditedClient->id, $admin);
        $this->assertAuditExists('created', Project::class, Project::where('name', 'Audit Project')->value('id'), $admin);
        $this->assertAuditExists('created', Payment::class, $payment->id, $admin);
        $this->assertAuditExists('created', Expense::class, $expense->id, $admin);
        $this->assertAuditExists('soft_deleted', Expense::class, $expense->id, $admin);
        $this->assertAuditExists('created', Document::class, $document->id, $admin);
        $this->assertAuditExists('deleted', Document::class, $document->id, $admin);
        $this->assertAuditExists('created', ProjectMilestone::class, $milestone->id, $admin);
        $this->assertAuditExists('soft_deleted', ProjectMilestone::class, $milestone->id, $admin);
        $this->assertAuditExists('created', ProjectTask::class, $task->id, $admin);
        $this->assertAuditExists('soft_deleted', ProjectTask::class, $task->id, $admin);

        $clientUpdateLog = AuditLog::where('action', 'updated')
            ->where('target_type', Client::class)
            ->where('target_id', $auditedClient->id)
            ->firstOrFail();

        $this->assertSame('Audited Client', $clientUpdateLog->changes['before']['name']);
        $this->assertSame('Audited Client Updated', $clientUpdateLog->changes['after']['name']);
    }

    public function test_audit_logs_cover_invoice_update_and_delete_regression(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['project_value' => '10000.00']);

        $this->actingAs($admin)->post(route('invoices.store'), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-01',
            'due_date' => '2026-08-15',
            'amount' => '1000.00',
            'status' => Invoice::STATUS_DRAFT,
            'notes' => 'Initial invoice.',
        ])->assertRedirect();

        $invoice = Invoice::firstOrFail();

        $this->actingAs($admin)->put(route('invoices.update', $invoice), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-01',
            'due_date' => '2026-08-20',
            'amount' => '1200.00',
            'status' => Invoice::STATUS_SENT,
            'notes' => 'Updated invoice.',
        ])->assertRedirect(route('invoices.show', $invoice));

        $this->actingAs($admin)->delete(route('invoices.destroy', $invoice))
            ->assertRedirect(route('invoices.index'));

        $this->assertAuditExists('created', Invoice::class, $invoice->id, $admin);
        $this->assertAuditExists('updated', Invoice::class, $invoice->id, $admin);
        $this->assertAuditExists('deleted', Invoice::class, $invoice->id, $admin);
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_audit_ui_is_admin_only_and_read_only(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->role(RbacPermissions::VIEWER)->create();
        $client = Client::factory()->create(['name' => 'Visible Audit Client']);
        $auditLog = AuditLog::where('target_type', Client::class)
            ->where('target_id', $client->id)
            ->firstOrFail();

        $this->actingAs($admin)->get(route('audit-logs.index'))
            ->assertOk()
            ->assertSee('Client #'.$client->id);
        $this->actingAs($admin)->get(route('audit-logs.show', $auditLog))
            ->assertOk()
            ->assertSee('Visible Audit Client')
            ->assertSee('Safe Change Summary');

        $this->actingAs($viewer)->get(route('audit-logs.index'))->assertForbidden();
        $this->actingAs($viewer)->get(route('audit-logs.show', $auditLog))->assertForbidden();
        auth()->logout();
        $this->get(route('audit-logs.index'))->assertRedirect(route('login'));

        $this->actingAs($admin)->post('/audit-logs')->assertStatus(405);
        $this->actingAs($admin)->put("/audit-logs/{$auditLog->id}")->assertStatus(405);
        $this->actingAs($admin)->delete("/audit-logs/{$auditLog->id}")->assertStatus(405);
    }

    public function test_audit_logs_exclude_sensitive_fields_and_document_file_contents(): void
    {
        Storage::fake(Document::DISK);

        $admin = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $this->actingAs($admin)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->createWithContent('secret.txt', 'SUPER_SECRET_FILE_CONTENT'),
        ])->assertRedirect();

        $documentLog = AuditLog::where('target_type', Document::class)->firstOrFail();
        $encodedChanges = json_encode($documentLog->changes);

        $this->assertStringContainsString('secret.txt', $encodedChanges);
        $this->assertStringNotContainsString('SUPER_SECRET_FILE_CONTENT', $encodedChanges);
        $this->assertStringNotContainsString('stored_path', $encodedChanges);
        $this->assertStringNotContainsString('documents/', $encodedChanges);

        $service = app(AuditLogService::class);
        $service->created(new class extends Client
        {
            protected $attributes = [
                'name' => 'Synthetic Client',
                'api_token' => 'TOKEN_SHOULD_NOT_BE_LOGGED',
                'password' => 'PASSWORD_SHOULD_NOT_BE_LOGGED',
            ];

            public function getKey()
            {
                return 999;
            }
        }, $admin);

        $syntheticLog = AuditLog::where('target_id', 999)->latest()->firstOrFail();
        $encodedSyntheticChanges = json_encode($syntheticLog->changes);

        $this->assertStringContainsString('Synthetic Client', $encodedSyntheticChanges);
        $this->assertStringNotContainsString('TOKEN_SHOULD_NOT_BE_LOGGED', $encodedSyntheticChanges);
        $this->assertStringNotContainsString('PASSWORD_SHOULD_NOT_BE_LOGGED', $encodedSyntheticChanges);
        $this->assertStringNotContainsString('api_token', $encodedSyntheticChanges);
        $this->assertStringNotContainsString('password', $encodedSyntheticChanges);
    }

    /**
     * @param  class-string  $targetType
     */
    private function assertAuditExists(string $action, string $targetType, int|string|null $targetId, User $actor): void
    {
        $this->assertDatabaseHas('audit_logs', [
            'actor_user_id' => $actor->id,
            'actor_name' => $actor->name,
            'actor_email' => $actor->email,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function clientPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Audit Client',
            'email' => 'audit-client@example.com',
            'phone_number' => '08123456789',
            'company' => 'Audit Co',
            'address' => 'Audit Street',
        ], $overrides);
    }

    /**
     * @return array<string, mixed>
     */
    private function projectPayload(Client $client): array
    {
        return [
            'client_id' => $client->id,
            'name' => 'Audit Project',
            'description' => 'Audit project description.',
            'start_date' => '2026-08-01',
            'deadline' => '2026-08-20',
            'project_value' => '3000.00',
            'status' => Project::STATUS_DRAFT,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function expensePayload(Project $project): array
    {
        return [
            'project_id' => $project->id,
            'category' => 'Audit Expense',
            'expense_date' => '2026-08-02',
            'amount' => '125.00',
            'description' => 'Audit expense description.',
            'vendor' => 'Audit Vendor',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function milestonePayload(): array
    {
        return [
            'title' => 'Audit Milestone',
            'description' => 'Audit milestone description.',
            'target_date' => '2026-08-15',
            'weight' => 50,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function taskPayload(): array
    {
        return [
            'project_milestone_id' => null,
            'title' => 'Audit Task',
            'assignee' => 'Lincon',
            'priority' => 'High',
            'due_date' => '2026-08-10',
            'description' => 'Audit task description.',
            'progress' => 10,
            'status' => ProjectTask::STATUS_BACKLOG,
        ];
    }
}
