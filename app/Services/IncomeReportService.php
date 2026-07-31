<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class IncomeReportService
{
    /**
     * @param  array{start_date?: string|null, end_date?: string|null}  $filters
     * @return EloquentCollection<int, Payment>
     */
    public function payments(array $filters): EloquentCollection
    {
        return Payment::with('invoice.project.client')
            ->when($filters['start_date'] ?? null, fn ($query, string $date) => $query->whereDate('payment_date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, string $date) => $query->whereDate('payment_date', '<=', $date))
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @param  array{start_date?: string|null, end_date?: string|null}  $filters
     * @return EloquentCollection<int, Expense>
     */
    public function expenses(array $filters): EloquentCollection
    {
        return Expense::with('project.client')
            ->when($filters['start_date'] ?? null, fn ($query, string $date) => $query->whereDate('expense_date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, string $date) => $query->whereDate('expense_date', '<=', $date))
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @return Collection<int, array{status: string, count: int, total_amount: float}>
     */
    public function invoiceStatusRecap(): Collection
    {
        $recap = Invoice::query()
            ->selectRaw('status, COUNT(*) as invoice_count, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('status')
            ->pluck('total_amount', 'status');

        $counts = Invoice::query()
            ->selectRaw('status, COUNT(*) as invoice_count')
            ->groupBy('status')
            ->pluck('invoice_count', 'status');

        return collect(Invoice::STATUSES)
            ->map(fn (string $status): array => [
                'status' => $status,
                'count' => (int) ($counts[$status] ?? 0),
                'total_amount' => (float) ($recap[$status] ?? 0),
            ])
            ->values();
    }

    /**
     * @param  EloquentCollection<int, Payment>  $payments
     */
    public function total(EloquentCollection $payments): float
    {
        return (float) $payments->sum('amount');
    }

    /**
     * @param  EloquentCollection<int, Payment>  $payments
     * @param  EloquentCollection<int, Expense>  $expenses
     * @return array{income: float, expenses: float, net_profit: float}
     */
    public function financeSummary(EloquentCollection $payments, EloquentCollection $expenses): array
    {
        $income = (float) $payments->sum('amount');
        $expenseTotal = (float) $expenses->sum('amount');

        return [
            'income' => $income,
            'expenses' => $expenseTotal,
            'net_profit' => $income - $expenseTotal,
        ];
    }
}
