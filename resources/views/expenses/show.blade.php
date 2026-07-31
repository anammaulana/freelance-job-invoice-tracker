<x-layouts.app title="Expense">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to expenses</a>
            <h1 class="mt-3 text-2xl font-semibold text-zinc-950">{{ $expense->category }}</h1>
            <p class="mt-1 text-sm text-zinc-600">{{ $expense->expense_date->format('Y-m-d') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('expenses.update')
                <a href="{{ route('expenses.edit', $expense) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Edit</a>
            @endcan
            @can('expenses.delete')
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="grid gap-5 md:grid-cols-2">
            <div><p class="text-sm text-zinc-500">Amount</p><p class="mt-1 font-medium text-zinc-950">{{ number_format((float) $expense->amount, 2) }}</p></div>
            <div><p class="text-sm text-zinc-500">Vendor / payee</p><p class="mt-1 font-medium text-zinc-950">{{ $expense->vendor ?: '-' }}</p></div>
            <div><p class="text-sm text-zinc-500">Project</p><p class="mt-1 font-medium text-zinc-950">{{ $expense->project?->name ?: '-' }}</p></div>
            <div><p class="text-sm text-zinc-500">Client</p><p class="mt-1 font-medium text-zinc-950">{{ $expense->project?->client?->name ?: '-' }}</p></div>
            <div class="md:col-span-2"><p class="text-sm text-zinc-500">Description</p><p class="mt-1 whitespace-pre-line font-medium text-zinc-950">{{ $expense->description }}</p></div>
        </div>
    </section>

    @can('documents.view')
        <section class="mt-6">
            @include('documents._panel', [
                'documents' => $expense->documents,
                'title' => 'Expense documents',
                'storeRoute' => route('expenses.documents.store', $expense),
            ])
        </section>
    @endcan
</x-layouts.app>
