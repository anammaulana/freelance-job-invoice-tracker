<x-layouts.app title="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-zinc-950">Dashboard</h1>
        <p class="mt-1 text-sm text-zinc-600">Ringkasan performa client, project, invoice, dan payment.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-zinc-600">Total clients</p>
            <p class="mt-2 text-2xl font-semibold text-zinc-950">{{ $metrics['client_count'] }}</p>
        </div>
        <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-zinc-600">Active projects</p>
            <p class="mt-2 text-2xl font-semibold text-zinc-950">{{ $metrics['active_project_count'] }}</p>
        </div>
        <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-zinc-600">Unpaid invoices</p>
            <p class="mt-2 text-2xl font-semibold text-zinc-950">{{ number_format($metrics['unpaid_invoice_total'], 2) }}</p>
        </div>
        <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-zinc-600">Total income</p>
            <p class="mt-2 text-2xl font-semibold text-zinc-950">{{ number_format($metrics['total_income'], 2) }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section>
            <h2 class="text-lg font-semibold text-zinc-950">Overdue invoices</h2>
            <div class="mt-3 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                @if ($overdue_invoices->isEmpty())
                    <p class="p-5 text-sm text-zinc-600">Tidak ada invoice overdue.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-sm">
                            <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                <tr>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Client</th>
                                    <th class="px-4 py-3">Due</th>
                                    <th class="px-4 py-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($overdue_invoices as $invoice)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-zinc-950">
                                            @can('invoices.view')
                                                <a href="{{ route('invoices.show', $invoice) }}" class="hover:underline">{{ $invoice->invoice_number }}</a>
                                            @else
                                                {{ $invoice->invoice_number }}
                                            @endcan
                                        </td>
                                        <td class="px-4 py-3 text-zinc-700">{{ $invoice->project->client->name }}</td>
                                        <td class="px-4 py-3 text-zinc-700">{{ $invoice->due_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-zinc-700">{{ number_format((float) $invoice->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-zinc-950">Latest payments</h2>
            <div class="mt-3 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                @if ($latest_payments->isEmpty())
                    <p class="p-5 text-sm text-zinc-600">Belum ada payment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-sm">
                            <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Method</th>
                                    <th class="px-4 py-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($latest_payments as $payment)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 font-medium text-zinc-950">{{ $payment->invoice->invoice_number }}</td>
                                        <td class="px-4 py-3 text-zinc-700">{{ $payment->method ?? '-' }}</td>
                                        <td class="px-4 py-3 text-zinc-700">{{ number_format((float) $payment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>
