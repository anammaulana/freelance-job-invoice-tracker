<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_list_view_update_and_delete_invoice(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create([
            'name' => 'Website Build',
            'project_value' => '10000.00',
        ]);

        $createResponse = $this->actingAs($user)->post(route('invoices.store'), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-01',
            'due_date' => '2026-08-15',
            'amount' => '2500.00',
            'notes' => 'Initial milestone.',
            'status' => Invoice::STATUS_DRAFT,
        ]);

        $invoice = Invoice::firstOrFail();
        $createResponse->assertRedirect(route('invoices.show', $invoice));

        $this->assertMatchesRegularExpression('/^INV-202608-\d{4}$/', $invoice->invoice_number);

        $this->actingAs($user)->get(route('invoices.index'))
            ->assertOk()
            ->assertSee($invoice->invoice_number)
            ->assertSee('Website Build');

        $this->actingAs($user)->get(route('invoices.show', $invoice))
            ->assertOk()
            ->assertSee('Initial milestone.');

        $this->actingAs($user)->put(route('invoices.update', $invoice), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-02',
            'due_date' => '2026-08-20',
            'amount' => '3000.00',
            'notes' => 'Updated milestone.',
            'status' => Invoice::STATUS_SENT,
        ])->assertRedirect(route('invoices.show', $invoice));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount' => '3000',
            'status' => Invoice::STATUS_SENT,
        ]);

        $this->actingAs($user)->delete(route('invoices.destroy', $invoice))
            ->assertRedirect(route('invoices.index'));

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_invoice_numbers_are_unique_and_sequential_per_month(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['project_value' => '10000.00']);

        foreach (['1000.00', '1500.00'] as $amount) {
            $this->actingAs($user)->post(route('invoices.store'), [
                'project_id' => $project->id,
                'issue_date' => '2026-08-01',
                'due_date' => '2026-08-15',
                'amount' => $amount,
                'status' => Invoice::STATUS_DRAFT,
            ])->assertSessionHasNoErrors();
        }

        $this->assertDatabaseHas('invoices', ['invoice_number' => 'INV-202608-0001']);
        $this->assertDatabaseHas('invoices', ['invoice_number' => 'INV-202608-0002']);
    }

    public function test_project_invoice_total_cannot_exceed_project_value(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['project_value' => '3000.00']);

        Invoice::factory()->for($project)->create([
            'invoice_number' => 'INV-202608-0001',
            'amount' => '2500.00',
        ]);

        $this->actingAs($user)->post(route('invoices.store'), [
            'project_id' => $project->id,
            'issue_date' => '2026-08-05',
            'due_date' => '2026-08-20',
            'amount' => '600.00',
            'status' => Invoice::STATUS_DRAFT,
        ])->assertSessionHasErrors('amount');
    }

    public function test_invoice_create_requires_valid_project_and_dates(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)->post(route('invoices.store'), [
            'project_id' => 999,
            'issue_date' => '2026-08-20',
            'due_date' => '2026-08-01',
            'amount' => '',
            'status' => 'Waiting',
        ])->assertSessionHasErrors(['project_id', 'due_date', 'amount', 'status']);
    }

    public function test_multiple_payments_can_be_recorded_and_set_partial_then_paid_status(): void
    {
        $user = User::factory()->admin()->create();
        $invoice = Invoice::factory()->for(Project::factory()->for(Client::factory()))->create([
            'amount' => '1000.00',
            'status' => Invoice::STATUS_SENT,
        ]);

        $this->actingAs($user)->post(route('invoices.payments.store', $invoice), [
            'payment_date' => '2026-08-10',
            'amount' => '400.00',
            'method' => 'Bank Transfer',
            'reference' => 'TRX-001',
            'notes' => 'First payment',
        ])->assertRedirect(route('invoices.show', $invoice));

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => '400',
            'method' => 'Bank Transfer',
        ]);
        $this->assertSame(Invoice::STATUS_PARTIAL, $invoice->refresh()->status);

        $this->actingAs($user)->post(route('invoices.payments.store', $invoice), [
            'payment_date' => '2026-08-12',
            'amount' => '600.00',
            'method' => 'Bank Transfer',
        ])->assertRedirect(route('invoices.show', $invoice));

        $this->assertSame(Invoice::STATUS_PAID, $invoice->refresh()->status);
        $this->assertCount(2, $invoice->payments);
    }

    public function test_total_payments_cannot_exceed_invoice_amount(): void
    {
        $user = User::factory()->admin()->create();
        $invoice = Invoice::factory()->for(Project::factory()->for(Client::factory()))->create(['amount' => '1000.00']);

        Payment::factory()->for($invoice)->create(['amount' => '900.00']);

        $this->actingAs($user)->post(route('invoices.payments.store', $invoice), [
            'payment_date' => '2026-08-12',
            'amount' => '101.00',
        ])->assertSessionHasErrors('amount');
    }

    public function test_authenticated_user_can_update_and_delete_payment(): void
    {
        $user = User::factory()->admin()->create();
        $invoice = Invoice::factory()->for(Project::factory()->for(Client::factory()))->create([
            'amount' => '1000.00',
            'status' => Invoice::STATUS_SENT,
        ]);
        $payment = Payment::factory()->for($invoice)->create(['amount' => '200.00']);

        $this->actingAs($user)->put(route('invoices.payments.update', [$invoice, $payment]), [
            'payment_date' => '2026-08-15',
            'amount' => '1000.00',
            'method' => 'Cash',
            'reference' => 'PAID',
            'notes' => 'Full settlement',
        ])->assertRedirect(route('invoices.show', $invoice));

        $this->assertSame(Invoice::STATUS_PAID, $invoice->refresh()->status);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount' => '1000',
            'method' => 'Cash',
        ]);

        $this->actingAs($user)->delete(route('invoices.payments.destroy', [$invoice, $payment]))
            ->assertRedirect(route('invoices.show', $invoice));

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
        $this->assertSame(Invoice::STATUS_SENT, $invoice->refresh()->status);
    }

    public function test_payment_form_requires_payment_date_and_amount(): void
    {
        $user = User::factory()->admin()->create();
        $invoice = Invoice::factory()->for(Project::factory()->for(Client::factory()))->create();

        $this->actingAs($user)->post(route('invoices.payments.store', $invoice), [
            'payment_date' => '',
            'amount' => '',
        ])->assertSessionHasErrors(['payment_date', 'amount']);
    }
}
