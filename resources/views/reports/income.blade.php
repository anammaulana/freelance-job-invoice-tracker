<x-layouts.app title="Income Report">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Income Report</h1>
            <p class="mt-1 text-sm text-zinc-600">Filter income berdasarkan tanggal payment dan lihat recap status invoice.</p>
        </div>
        @can('reports.export')
            <a href="{{ route('reports.income.export', request()->only(['start_date', 'end_date'])) }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Export XLSX</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('reports.income') }}" class="mb-6 grid gap-4 rounded-lg border border-zinc-200 bg-white p-5 shadow-sm sm:grid-cols-[1fr_1fr_auto]">
        <label class="text-sm font-medium text-zinc-700">
            Start date
            <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="mt-1 w-full rounded-md border-zinc-300 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900">
        </label>
        <label class="text-sm font-medium text-zinc-700">
            End date
            <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="mt-1 w-full rounded-md border-zinc-300 text-sm shadow-sm focus:border-zinc-900 focus:ring-zinc-900">
        </label>
        <div class="flex items-end gap-2">
            <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Filter</button>
            <a href="{{ route('reports.income') }}" class="rounded-md px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Reset</a>
        </div>
    </form>

    <div class="mb-6 rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-zinc-600">Filtered income total</p>
        <p class="mt-2 text-2xl font-semibold text-zinc-950">{{ number_format($totalIncome, 2) }}</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2">
            <h2 class="text-lg font-semibold text-zinc-950">Payments</h2>
            <div class="mt-3 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                @if ($payments->isEmpty())
                    <p class="p-5 text-sm text-zinc-600">Tidak ada payment pada rentang tanggal ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-sm">
                            <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Client</th>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Method</th>
                                    <th class="px-4 py-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td class="px-4 py-3 text-zinc-700">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-zinc-700">{{ $payment->invoice->project->client->name }}</td>
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

        <section>
            <h2 class="text-lg font-semibold text-zinc-950">Invoice status recap</h2>
            <div class="mt-3 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Count</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($statusRecap as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-950">{{ $item['status'] }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $item['count'] }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ number_format($item['total_amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
