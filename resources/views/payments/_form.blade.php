@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="payment_date" class="block text-sm font-medium text-zinc-700">Payment date</label>
        <input id="payment_date" name="payment_date" type="date" value="{{ old('payment_date', isset($payment) ? $payment->payment_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="amount" class="block text-sm font-medium text-zinc-700">Amount</label>
        <input id="amount" name="amount" type="number" min="0.01" step="0.01" value="{{ old('amount', $payment->amount ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="method" class="block text-sm font-medium text-zinc-700">Method</label>
        <input id="method" name="method" value="{{ old('method', $payment->method ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="reference" class="block text-sm font-medium text-zinc-700">Reference</label>
        <input id="reference" name="reference" value="{{ old('reference', $payment->reference ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-zinc-700">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('notes', $payment->notes ?? '') }}</textarea>
    </div>
</div>
