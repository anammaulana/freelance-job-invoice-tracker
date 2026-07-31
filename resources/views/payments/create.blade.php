<x-layouts.app title="Record Payment">
    <div class="mb-6">
        <a href="{{ route('invoices.show', $invoice) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to invoice</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">Record payment</h1>
        <p class="mt-1 text-sm text-zinc-600">Invoice {{ $invoice->invoice_number }}</p>
    </div>

    <form action="{{ route('invoices.payments.store', $invoice) }}" method="POST" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        @include('payments._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('invoices.show', $invoice) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
            <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Save payment</button>
        </div>
    </form>
</x-layouts.app>
