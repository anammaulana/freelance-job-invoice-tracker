<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use App\Support\RbacPermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_download_and_delete_project_document(): void
    {
        Storage::fake(Document::DISK);

        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $response = $this->actingAs($user)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('brief.pdf', 128, 'application/pdf'),
        ]);

        $document = Document::firstOrFail();

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'attachable_type' => Project::class,
            'attachable_id' => $project->id,
            'original_filename' => 'brief.pdf',
            'disk' => Document::DISK,
            'uploaded_by_user_id' => $user->id,
        ]);
        Storage::disk(Document::DISK)->assertExists($document->stored_path);

        $this->actingAs($user)->get(route('documents.download', $document))
            ->assertOk()
            ->assertDownload('brief.pdf');

        $this->actingAs($user)->delete(route('documents.destroy', $document))
            ->assertRedirect();

        Storage::disk(Document::DISK)->assertMissing($document->stored_path);
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }

    public function test_upload_rejects_unsupported_mime_type(): void
    {
        Storage::fake(Document::DISK);

        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $this->actingAs($user)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('payload.exe', 10, 'application/x-msdownload'),
        ])->assertSessionHasErrors('document');

        $this->assertDatabaseCount('documents', 0);
    }

    public function test_upload_rejects_files_above_size_limit(): void
    {
        Storage::fake(Document::DISK);

        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $this->actingAs($user)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('large.pdf', Document::MAX_UPLOAD_KILOBYTES + 1, 'application/pdf'),
        ])->assertSessionHasErrors('document');

        $this->assertDatabaseCount('documents', 0);
    }

    public function test_documents_can_attach_to_task_client_invoice_and_payment_records(): void
    {
        Storage::fake(Document::DISK);

        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $project = Project::factory()->for($client)->create();
        $task = ProjectTask::create([
            'project_id' => $project->id,
            'title' => 'Attachable task',
            'priority' => 'High',
            'status' => ProjectTask::STATUS_BACKLOG,
        ]);
        $invoice = Invoice::factory()->for($project)->create();
        $payment = Payment::factory()->for($invoice)->create();
        $expense = Expense::factory()->for($project)->create();

        $targets = [
            [route('projects.tasks.documents.store', [$project, $task]), ProjectTask::class, $task->id, 'task.pdf'],
            [route('clients.documents.store', $client), Client::class, $client->id, 'client.pdf'],
            [route('invoices.documents.store', $invoice), Invoice::class, $invoice->id, 'invoice.pdf'],
            [route('invoices.payments.documents.store', [$invoice, $payment]), Payment::class, $payment->id, 'payment.pdf'],
            [route('expenses.documents.store', $expense), Expense::class, $expense->id, 'expense.pdf'],
        ];

        foreach ($targets as [$route, $type, $id, $filename]) {
            $this->actingAs($admin)->post($route, [
                'document' => UploadedFile::fake()->create($filename, 20, 'application/pdf'),
            ])->assertRedirect();

            $this->assertDatabaseHas('documents', [
                'attachable_type' => $type,
                'attachable_id' => $id,
                'original_filename' => $filename,
            ]);
        }
    }

    public function test_document_rbac_allows_project_manager_and_blocks_viewer_or_finance_project_writes(): void
    {
        Storage::fake(Document::DISK);

        $project = Project::factory()->for(Client::factory())->create();
        $manager = User::factory()->role(RbacPermissions::PROJECT_MANAGER)->create();
        $viewer = User::factory()->role(RbacPermissions::VIEWER)->create();
        $finance = User::factory()->role(RbacPermissions::FINANCE)->create();

        $this->actingAs($manager)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('manager.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $document = Document::firstOrFail();

        $this->actingAs($viewer)->get(route('documents.download', $document))
            ->assertOk()
            ->assertDownload('manager.pdf');

        $this->actingAs($viewer)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('viewer.pdf', 20, 'application/pdf'),
        ])->assertForbidden();

        $this->actingAs($finance)->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('finance.pdf', 20, 'application/pdf'),
        ])->assertForbidden();

        $this->actingAs($finance)->get(route('documents.download', $document))
            ->assertForbidden();
    }

    public function test_finance_can_manage_invoice_payment_and_expense_documents_only(): void
    {
        Storage::fake(Document::DISK);

        $finance = User::factory()->role(RbacPermissions::FINANCE)->create();
        $invoice = Invoice::factory()->for(Project::factory()->for(Client::factory()))->create();
        $payment = Payment::factory()->for($invoice)->create();
        $expense = Expense::factory()->for($invoice->project)->create();

        $this->actingAs($finance)->post(route('invoices.documents.store', $invoice), [
            'document' => UploadedFile::fake()->create('invoice-note.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $this->actingAs($finance)->post(route('invoices.payments.documents.store', [$invoice, $payment]), [
            'document' => UploadedFile::fake()->create('payment-note.pdf', 20, 'application/pdf'),
        ])->assertRedirect();
        $this->actingAs($finance)->post(route('expenses.documents.store', $expense), [
            'document' => UploadedFile::fake()->create('expense-note.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $this->assertDatabaseHas('documents', ['attachable_type' => Invoice::class, 'attachable_id' => $invoice->id]);
        $this->assertDatabaseHas('documents', ['attachable_type' => Payment::class, 'attachable_id' => $payment->id]);
        $this->assertDatabaseHas('documents', ['attachable_type' => Expense::class, 'attachable_id' => $expense->id]);
    }

    public function test_unauthenticated_document_requests_are_blocked(): void
    {
        Storage::fake(Document::DISK);

        $admin = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();
        $document = $project->documents()->create([
            'original_filename' => 'private.pdf',
            'stored_path' => 'documents/private.pdf',
            'disk' => Document::DISK,
            'mime_type' => 'application/pdf',
            'size' => 20,
            'uploaded_by_user_id' => $admin->id,
        ]);

        $this->get(route('documents.download', $document))->assertRedirect(route('login'));
        $this->post(route('projects.documents.store', $project), [
            'document' => UploadedFile::fake()->create('guest.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('login'));
    }
}
