<?php

namespace App\Http\Controllers;

use App\Services\IncomeReportService;
use App\Services\SimpleXlsxExporter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function income(Request $request, IncomeReportService $incomeReportService): View
    {
        $filters = $this->filters($request);
        $payments = $incomeReportService->payments($filters);

        return view('reports.income', [
            'filters' => $filters,
            'payments' => $payments,
            'totalIncome' => $incomeReportService->total($payments),
            'statusRecap' => $incomeReportService->invoiceStatusRecap(),
        ]);
    }

    public function exportIncome(
        Request $request,
        IncomeReportService $incomeReportService,
        SimpleXlsxExporter $xlsxExporter
    ): Response {
        $filters = $this->filters($request);
        $payments = $incomeReportService->payments($filters);

        $rows = [
            ['Payment Date', 'Client', 'Project', 'Invoice', 'Method', 'Reference', 'Amount'],
        ];

        foreach ($payments as $payment) {
            $rows[] = [
                $payment->payment_date->format('Y-m-d'),
                $payment->invoice->project->client->name,
                $payment->invoice->project->name,
                $payment->invoice->invoice_number,
                $payment->method ?? '',
                $payment->reference ?? '',
                (float) $payment->amount,
            ];
        }

        $rows[] = ['', '', '', '', '', 'Total', $incomeReportService->total($payments)];

        return response($xlsxExporter->make($rows), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="income-report.xlsx"',
        ]);
    }

    /**
     * @return array{start_date: string|null, end_date: string|null}
     */
    private function filters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        return [
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];
    }
}
