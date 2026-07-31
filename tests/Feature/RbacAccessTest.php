<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use App\Support\RbacPermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_rbac_seeder_creates_roles_permissions_and_assigns_demo_admin(): void
    {
        $this->seed();

        foreach (RbacPermissions::roleLabels() as $slug => $name) {
            $this->assertDatabaseHas('roles', compact('slug', 'name'));
        }

        foreach (array_keys(RbacPermissions::labels()) as $slug) {
            $this->assertDatabaseHas('permissions', ['slug' => $slug]);
        }

        $demoUser = User::where('email', 'demo@example.com')->firstOrFail();

        $this->assertTrue($demoUser->hasRole(RbacPermissions::ADMIN));
    }

    public function test_admin_can_access_existing_stable_modules(): void
    {
        $user = User::factory()->admin()->create();
        $client = Client::factory()->create(['name' => 'Admin Client']);
        $project = Project::factory()->for($client)->create(['name' => 'Admin Project']);
        $invoice = Invoice::factory()->for($project)->create(['invoice_number' => 'INV-ADMIN']);
        $expense = Expense::factory()->for($project)->create(['category' => 'Admin Expense']);

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('clients.index'))->assertOk()->assertSee('Admin Client');
        $this->actingAs($user)->get(route('projects.index'))->assertOk()->assertSee('Admin Project');
        $this->actingAs($user)->get(route('invoices.index'))->assertOk()->assertSee('INV-ADMIN');
        $this->actingAs($user)->get(route('expenses.index'))->assertOk()->assertSee('Admin Expense');
        $this->actingAs($user)->delete(route('expenses.destroy', $expense))->assertRedirect();
        $this->actingAs($user)->get(route('reports.income'))->assertOk();
    }

    public function test_viewer_can_read_but_cannot_create_update_or_delete(): void
    {
        $user = User::factory()->role(RbacPermissions::VIEWER)->create();
        $client = Client::factory()->create();
        $project = Project::factory()->for($client)->create();
        $invoice = Invoice::factory()->for($project)->create();
        $expense = Expense::factory()->for($project)->create();

        $this->actingAs($user)->get(route('clients.index'))->assertOk();
        $this->actingAs($user)->get(route('projects.index'))->assertOk();
        $this->actingAs($user)->get(route('invoices.index'))->assertOk();
        $this->actingAs($user)->get(route('expenses.index'))->assertOk();
        $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $this->actingAs($user)->get(route('clients.create'))->assertForbidden();
        $this->actingAs($user)->post(route('clients.store'), $this->clientPayload())->assertForbidden();
        $this->actingAs($user)->put(route('clients.update', $client), $this->clientPayload())->assertForbidden();
        $this->actingAs($user)->delete(route('clients.destroy', $client))->assertForbidden();
        $this->actingAs($user)->get(route('projects.create'))->assertForbidden();
        $this->actingAs($user)->put(route('projects.update', $project), $this->projectPayload($client))->assertForbidden();
        $this->actingAs($user)->get(route('invoices.create'))->assertForbidden();
        $this->actingAs($user)->delete(route('invoices.destroy', $invoice))->assertForbidden();
        $this->actingAs($user)->get(route('expenses.create'))->assertForbidden();
        $this->actingAs($user)->put(route('expenses.update', $expense), $this->expensePayload())->assertForbidden();
        $this->actingAs($user)->delete(route('expenses.destroy', $expense))->assertForbidden();
    }

    public function test_finance_can_access_finance_modules_and_is_blocked_from_non_finance_mutations(): void
    {
        $user = User::factory()->role(RbacPermissions::FINANCE)->create();
        $client = Client::factory()->create();
        $project = Project::factory()->for($client)->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('invoices.index'))->assertOk();
        $this->actingAs($user)->get(route('expenses.index'))->assertOk();
        $this->actingAs($user)->get(route('reports.income'))->assertOk();
        $this->actingAs($user)->get(route('clients.index'))->assertForbidden();
        $this->actingAs($user)->get(route('projects.index'))->assertForbidden();
        $this->actingAs($user)->post(route('clients.store'), $this->clientPayload())->assertForbidden();
        $this->actingAs($user)->post(route('projects.store'), $this->projectPayload($client))->assertForbidden();

        $this->actingAs($user)->post(route('invoices.store'), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-01',
            'due_date' => '2026-08-15',
            'amount' => '500.00',
            'status' => Invoice::STATUS_DRAFT,
        ])->assertRedirect();
        $this->actingAs($user)->post(route('expenses.store'), $this->expensePayload())->assertRedirect();
    }

    public function test_project_manager_can_access_client_project_modules_and_is_blocked_from_finance_mutations(): void
    {
        $user = User::factory()->role(RbacPermissions::PROJECT_MANAGER)->create();
        $client = Client::factory()->create();
        $project = Project::factory()->for($client)->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('clients.index'))->assertOk();
        $this->actingAs($user)->get(route('projects.index'))->assertOk();
        $this->actingAs($user)->get(route('invoices.index'))->assertForbidden();
        $this->actingAs($user)->get(route('expenses.index'))->assertForbidden();
        $this->actingAs($user)->get(route('reports.income'))->assertForbidden();
        $this->actingAs($user)->post(route('invoices.store'), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-01',
            'due_date' => '2026-08-15',
            'amount' => '500.00',
            'status' => Invoice::STATUS_DRAFT,
        ])->assertForbidden();
        $this->actingAs($user)->post(route('clients.store'), $this->clientPayload())->assertRedirect();
        $this->actingAs($user)->post(route('expenses.store'), $this->expensePayload())->assertForbidden();
    }

    public function test_unauthorized_user_with_no_role_is_forbidden_after_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertForbidden();
    }

    /**
     * @return array<string, string>
     */
    private function clientPayload(): array
    {
        return [
            'name' => 'RBAC Client',
            'email' => 'rbac-client@example.com',
            'phone_number' => '08123456789',
            'company' => 'RBAC Co',
            'address' => 'Jalan RBAC',
        ];
    }

    /**
     * @return array<string, string|int>
     */
    private function projectPayload(Client $client): array
    {
        return [
            'client_id' => $client->id,
            'name' => 'RBAC Project',
            'description' => 'RBAC project payload.',
            'start_date' => '2026-08-01',
            'deadline' => '2026-08-15',
            'project_value' => '12000.50',
            'status' => Project::STATUS_DRAFT,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function expensePayload(): array
    {
        return [
            'category' => 'RBAC Expense',
            'expense_date' => '2026-08-01',
            'amount' => '100.00',
            'description' => 'RBAC expense payload.',
            'vendor' => 'RBAC Vendor',
        ];
    }
}
