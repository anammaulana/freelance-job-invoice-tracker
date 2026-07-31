<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Support\RbacPermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_view_update_and_soft_delete_expense(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['name' => 'Website Build']);

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'project_id' => $project->id,
            'category' => 'Software',
            'expense_date' => '2026-08-01',
            'amount' => '125.50',
            'description' => 'Design subscription',
            'vendor' => 'Figma',
        ]);

        $expense = Expense::firstOrFail();

        $response->assertRedirect(route('expenses.show', $expense));
        $this->assertDatabaseHas('expenses', [
            'project_id' => $project->id,
            'category' => 'Software',
            'amount' => '125.50',
            'vendor' => 'Figma',
        ]);

        $this->actingAs($user)->get(route('expenses.show', $expense))
            ->assertOk()
            ->assertSee('Website Build')
            ->assertSee('Design subscription');

        $this->actingAs($user)->put(route('expenses.update', $expense), [
            'project_id' => null,
            'category' => 'Travel',
            'expense_date' => '2026-08-02',
            'amount' => '80.00',
            'description' => 'Client meeting transport',
            'vendor' => 'Blue Cab',
        ])->assertRedirect(route('expenses.show', $expense));

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'project_id' => null,
            'category' => 'Travel',
            'amount' => '80.00',
        ]);

        $this->actingAs($user)->delete(route('expenses.destroy', $expense))->assertRedirect(route('expenses.index'));
        $this->assertSoftDeleted('expenses', ['id' => $expense->id]);
    }

    public function test_expense_validation_requires_core_fields_and_valid_project(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)->post(route('expenses.store'), [
            'project_id' => 9999,
            'category' => '',
            'expense_date' => 'not-a-date',
            'amount' => '0',
            'description' => '',
        ])->assertSessionHasErrors(['project_id', 'category', 'expense_date', 'amount', 'description']);

        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_expense_can_have_documents(): void
    {
        Storage::fake(Document::DISK);

        $user = User::factory()->admin()->create();
        $expense = Expense::factory()->create(['category' => 'Receipt']);

        $this->actingAs($user)->post(route('expenses.documents.store', $expense), [
            'document' => UploadedFile::fake()->create('receipt.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $document = Document::firstOrFail();

        $this->assertDatabaseHas('documents', [
            'attachable_type' => Expense::class,
            'attachable_id' => $expense->id,
            'original_filename' => 'receipt.pdf',
        ]);

        $this->assertTrue($expense->refresh()->documents->contains($document));
        Storage::disk(Document::DISK)->assertExists($document->stored_path);
    }

    public function test_finance_report_shows_income_expenses_and_net_profit_by_date_range(): void
    {
        $user = User::factory()->role(RbacPermissions::FINANCE)->create();
        $project = Project::factory()->for(Client::factory())->create(['name' => 'Finance Project']);
        $invoice = Invoice::factory()->for($project)->create(['invoice_number' => 'INV-FIN']);

        Payment::factory()->for($invoice)->create(['payment_date' => '2026-08-05', 'amount' => '1000.00']);
        Payment::factory()->for($invoice)->create(['payment_date' => '2026-07-31', 'amount' => '400.00']);
        Expense::factory()->for($project)->create([
            'category' => 'Software',
            'expense_date' => '2026-08-06',
            'amount' => '250.00',
            'description' => 'Tools',
        ]);
        Expense::factory()->for($project)->create([
            'category' => 'Old Travel',
            'expense_date' => '2026-07-30',
            'amount' => '120.00',
            'description' => 'Outside range',
        ]);

        $this->actingAs($user)->get(route('reports.income', [
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
        ]))
            ->assertOk()
            ->assertSee('Filtered income total')
            ->assertSee('1,000.00')
            ->assertSee('Filtered expense total')
            ->assertSee('250.00')
            ->assertSee('Net profit')
            ->assertSee('750.00')
            ->assertSee('Software')
            ->assertDontSee('Old Travel');
    }

    public function test_expense_rbac_allows_finance_writes_viewer_reads_and_blocks_project_manager_writes(): void
    {
        $expense = Expense::factory()->create(['category' => 'RBAC Expense']);
        $finance = User::factory()->role(RbacPermissions::FINANCE)->create();
        $viewer = User::factory()->role(RbacPermissions::VIEWER)->create();
        $manager = User::factory()->role(RbacPermissions::PROJECT_MANAGER)->create();

        $this->actingAs($finance)->get(route('expenses.index'))->assertOk()->assertSee('RBAC Expense');
        $this->actingAs($finance)->post(route('expenses.store'), $this->expensePayload())->assertRedirect();

        $this->actingAs($viewer)->get(route('expenses.index'))->assertOk();
        $this->actingAs($viewer)->get(route('expenses.show', $expense))->assertOk();
        $this->actingAs($viewer)->post(route('expenses.store'), $this->expensePayload())->assertForbidden();
        $this->actingAs($viewer)->delete(route('expenses.destroy', $expense))->assertForbidden();

        $this->actingAs($manager)->get(route('expenses.index'))->assertForbidden();
        $this->actingAs($manager)->post(route('expenses.store'), $this->expensePayload())->assertForbidden();
    }

    public function test_sprint_four_expense_pages_require_authentication(): void
    {
        $expense = Expense::factory()->create();

        $this->get(route('expenses.index'))->assertRedirect(route('login'));
        $this->get(route('expenses.show', $expense))->assertRedirect(route('login'));
        $this->post(route('expenses.store'), $this->expensePayload())->assertRedirect(route('login'));
    }

    /**
     * @return array<string, string>
     */
    private function expensePayload(): array
    {
        return [
            'category' => 'Office',
            'expense_date' => '2026-08-01',
            'amount' => '42.00',
            'description' => 'Office supplies',
            'vendor' => 'Stationery Co',
        ];
    }
}
