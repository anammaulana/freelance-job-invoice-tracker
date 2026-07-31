<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class DashboardReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_metrics_overdue_invoices_and_five_latest_payments(): void
    {
        $user = User::factory()->create();
        $clients = Client::factory()->count(2)->create();
        $activeProject = Project::factory()->for($clients[0])->create([
            'name' => 'Active Build',
            'status' => Project::STATUS_ACTIVE,
        ]);
        Project::factory()->for($clients[1])->create(['status' => Project::STATUS_COMPLETED]);

        $overdueInvoice = Invoice::factory()->for($activeProject)->create([
            'invoice_number' => 'INV-OVERDUE',
            'due_date' => '2026-01-10',
            'amount' => '1000.00',
            'status' => Invoice::STATUS_SENT,
        ]);
        Invoice::factory()->for($activeProject)->create([
            'invoice_number' => 'INV-CANCELLED',
            'due_date' => '2026-01-05',
            'amount' => '500.00',
            'status' => Invoice::STATUS_CANCELLED,
        ]);
        $partialInvoice = Invoice::factory()->for($activeProject)->create([
            'invoice_number' => 'INV-PARTIAL',
            'amount' => '700.00',
            'status' => Invoice::STATUS_PARTIAL,
        ]);
        Payment::factory()->for($partialInvoice)->create([
            'payment_date' => '2026-02-01',
            'amount' => '200.00',
        ]);

        foreach (range(1, 6) as $index) {
            Payment::factory()->for($overdueInvoice)->create([
                'payment_date' => '2026-03-0'.$index,
                'amount' => '10.00',
                'reference' => 'PAY-'.$index,
            ]);
        }

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total clients')
            ->assertSee('2')
            ->assertSee('Active projects')
            ->assertSee('1')
            ->assertSee('Unpaid invoices')
            ->assertSee('1,440.00')
            ->assertSee('Total income')
            ->assertSee('260.00')
            ->assertSee('INV-OVERDUE')
            ->assertDontSee('INV-CANCELLED')
            ->assertSee('2026-03-06')
            ->assertSee('2026-03-02')
            ->assertDontSee('2026-03-01');
    }

    public function test_income_report_filters_by_payment_date_and_groups_invoice_statuses(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Acme Studio']);
        $project = Project::factory()->for($client)->create(['name' => 'Brand Refresh']);
        $sentInvoice = Invoice::factory()->for($project)->create([
            'invoice_number' => 'INV-SENT',
            'amount' => '1000.00',
            'status' => Invoice::STATUS_SENT,
        ]);
        $paidInvoice = Invoice::factory()->for($project)->create([
            'invoice_number' => 'INV-PAID',
            'amount' => '1500.00',
            'status' => Invoice::STATUS_PAID,
        ]);

        Payment::factory()->for($sentInvoice)->create([
            'payment_date' => '2026-01-15',
            'amount' => '100.00',
            'method' => 'Cash',
        ]);
        Payment::factory()->for($paidInvoice)->create([
            'payment_date' => '2026-02-10',
            'amount' => '500.00',
            'method' => 'Bank Transfer',
        ]);

        $this->actingAs($user)->get(route('reports.income', [
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
        ]))
            ->assertOk()
            ->assertSee('500.00')
            ->assertSee('INV-PAID')
            ->assertDontSee('INV-SENT</td>', false)
            ->assertSee('Paid')
            ->assertSee('Sent')
            ->assertSee('1,500.00')
            ->assertSee('1,000.00');
    }

    public function test_income_report_export_downloads_valid_xlsx_file(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Export Client']);
        $project = Project::factory()->for($client)->create(['name' => 'Export Project']);
        $invoice = Invoice::factory()->for($project)->create([
            'invoice_number' => 'INV-EXPORT',
            'status' => Invoice::STATUS_PAID,
        ]);
        Payment::factory()->for($invoice)->create([
            'payment_date' => '2026-02-10',
            'amount' => '750.00',
            'method' => 'Bank Transfer',
            'reference' => 'EXP-001',
        ]);

        $response = $this->actingAs($user)->get(route('reports.income.export', [
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('content-disposition', 'attachment; filename="income-report.xlsx"');

        $path = tempnam(sys_get_temp_dir(), 'xlsx-test-');
        file_put_contents($path, $response->getContent());

        $zip = new ZipArchive;

        $this->assertTrue($zip->open($path));
        $worksheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($path);

        $this->assertIsString($worksheet);
        $this->assertStringContainsString('INV-EXPORT', $worksheet);
        $this->assertStringContainsString('Export Client', $worksheet);
        $this->assertStringContainsString('750', $worksheet);
    }

    public function test_sprint_three_pages_require_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('reports.income'))->assertRedirect(route('login'));
        $this->get(route('reports.income.export'))->assertRedirect(route('login'));
    }
}
