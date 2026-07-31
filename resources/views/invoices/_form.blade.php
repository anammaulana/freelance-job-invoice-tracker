@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="project_id" class="block text-sm font-medium text-zinc-700">Project</label>
        <select id="project_id" name="project_id" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            <option value="">Select project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id', $invoice->project_id ?? '') == $project->id)>{{ $project->name }} - {{ $project->client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-zinc-700">Status</label>
        <select id="status" name="status" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $invoice->status ?? 'Draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    @isset($invoice)
        <div>
            <label class="block text-sm font-medium text-zinc-700">Invoice number</label>
            <p class="mt-2 rounded-md border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-700">{{ $invoice->invoice_number }}</p>
        </div>
    @endisset
    <div>
        <label for="amount" class="block text-sm font-medium text-zinc-700">Amount</label>
        <input id="amount" name="amount" type="number" min="0.01" step="0.01" value="{{ old('amount', $invoice->amount ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="issue_date" class="block text-sm font-medium text-zinc-700">Issue date</label>
        <input id="issue_date" name="issue_date" type="date" value="{{ old('issue_date', isset($invoice) ? $invoice->issue_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="due_date" class="block text-sm font-medium text-zinc-700">Due date</label>
        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', isset($invoice) ? $invoice->due_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-zinc-700">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('notes', $invoice->notes ?? '') }}</textarea>
    </div>
</div>
