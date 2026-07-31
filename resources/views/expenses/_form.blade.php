@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="project_id" class="block text-sm font-medium text-zinc-700">Project</label>
        <select id="project_id" name="project_id" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            <option value="">No project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id', $expense->project_id ?? '') == $project->id)>{{ $project->name }} - {{ $project->client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="category" class="block text-sm font-medium text-zinc-700">Category</label>
        <input id="category" name="category" type="text" value="{{ old('category', $expense->category ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="expense_date" class="block text-sm font-medium text-zinc-700">Date</label>
        <input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', isset($expense) ? $expense->expense_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="amount" class="block text-sm font-medium text-zinc-700">Amount</label>
        <input id="amount" name="amount" type="number" min="0.01" step="0.01" value="{{ old('amount', $expense->amount ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="vendor" class="block text-sm font-medium text-zinc-700">Vendor / payee</label>
        <input id="vendor" name="vendor" type="text" value="{{ old('vendor', $expense->vendor ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-zinc-700">Description</label>
        <textarea id="description" name="description" rows="4" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $expense->description ?? '') }}</textarea>
    </div>
</div>
