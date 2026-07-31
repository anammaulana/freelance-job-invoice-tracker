<x-layouts.app title="{{ $invoice->invoice_number }}">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to invoices</a>
            <h1 class="mt-3 text-2xl font-semibold text-zinc-950">{{ $invoice->invoice_number }}</h1>
            <p class="mt-1 text-sm text-zinc-600">Project: {{ $invoice->project->name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('payments.create')
                <a href="{{ route('invoices.payments.create', $invoice) }}" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Record payment</a>
            @endcan
            @can('invoices.update')
                <a href="{{ route('invoices.edit', $invoice) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Edit</a>
            @endcan
            @can('invoices.delete')
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="grid gap-5 md:grid-cols-2">
            <div><p class="text-sm text-zinc-500">Status</p><p class="mt-1 font-medium text-zinc-950">{{ $invoice->status }}</p></div>
            <div><p class="text-sm text-zinc-500">Amount</p><p class="mt-1 font-medium text-zinc-950">{{ number_format((float) $invoice->amount, 2) }}</p></div>
            <div><p class="text-sm text-zinc-500">Issue date</p><p class="mt-1 font-medium text-zinc-950">{{ $invoice->issue_date->format('Y-m-d') }}</p></div>
            <div><p class="text-sm text-zinc-500">Due date</p><p class="mt-1 font-medium text-zinc-950">{{ $invoice->due_date->format('Y-m-d') }}</p></div>
            <div><p class="text-sm text-zinc-500">Client</p><p class="mt-1 font-medium text-zinc-950">{{ $invoice->project->client->name }}</p></div>
            <div><p class="text-sm text-zinc-500">Total paid</p><p class="mt-1 font-medium text-zinc-950">{{ number_format((float) $invoice->payments->sum('amount'), 2) }}</p></div>
            <div class="md:col-span-2"><p class="text-sm text-zinc-500">Notes</p><p class="mt-1 whitespace-pre-line font-medium text-zinc-950">{{ $invoice->notes ?: '-' }}</p></div>
        </div>
    </section>

    @can('documents.view')
        <section class="mt-6">
            @include('documents._panel', [
                'documents' => $invoice->documents,
                'title' => 'Invoice documents',
                'storeRoute' => route('invoices.documents.store', $invoice),
            ])
        </section>
    @endcan

    <section class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-zinc-950">Payments</h2>
            @can('payments.create')
                <a href="{{ route('invoices.payments.create', $invoice) }}" class="text-sm font-medium text-zinc-950 hover:underline">Record payment</a>
            @endcan
        </div>

        @if ($invoice->payments->isEmpty())
            <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-6 text-center text-sm text-zinc-600">Belum ada payment untuk invoice ini.</div>
        @else
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3">Documents</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($invoice->payments as $payment)
                                <tr>
                                    <td class="px-4 py-3 text-zinc-700">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 font-medium text-zinc-950">{{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $payment->method ?: '-' }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $payment->reference ?: '-' }}</td>
                                    <td class="px-4 py-3">
                                        @can('documents.view')
                                            @include('documents._panel', [
                                                'documents' => $payment->documents,
                                                'title' => 'Payment documents',
                                                'storeRoute' => route('invoices.payments.documents.store', [$invoice, $payment]),
                                                'compact' => true,
                                            ])
                                        @endcan
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-3">
                                            @can('payments.update')
                                                <a href="{{ route('invoices.payments.edit', [$invoice, $payment]) }}" class="font-medium text-zinc-950 hover:underline">Edit</a>
                                            @endcan
                                            @can('payments.delete')
                                                <form action="{{ route('invoices.payments.destroy', [$invoice, $payment]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="font-medium text-red-600 hover:underline">Delete</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
