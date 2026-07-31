<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * @return array{
     *     metrics: array{client_count: int, active_project_count: int, unpaid_invoice_total: float, total_income: float, total_expenses: float, net_profit: float},
     *     overdue_invoices: Collection<int, Invoice>,
     *     latest_payments: Collection<int, Payment>
     * }
     */
    public function summary(): array
    {
        $unpaidInvoices = Invoice::query()
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
            ->withSum('payments', 'amount')
            ->get();

        $unpaidTotal = $unpaidInvoices->sum(function (Invoice $invoice): float {
            return max(0, (float) $invoice->amount - (float) ($invoice->payments_sum_amount ?? 0));
        });

        $totalIncome = (float) Payment::sum('amount');
        $totalExpenses = (float) Expense::sum('amount');

        return [
            'metrics' => [
                'client_count' => Client::count(),
                'active_project_count' => Project::where('status', Project::STATUS_ACTIVE)->count(),
                'unpaid_invoice_total' => $unpaidTotal,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_profit' => $totalIncome - $totalExpenses,
            ],
            'overdue_invoices' => Invoice::with('project.client')
                ->whereDate('due_date', '<', now()->toDateString())
                ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
                ->orderBy('due_date')
                ->limit(10)
                ->get(),
            'latest_payments' => Payment::with('invoice.project.client')
                ->orderByDesc('payment_date')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ];
    }
}
