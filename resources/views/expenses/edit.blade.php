<x-layouts.app title="Edit Expense">
    <div class="mb-6">
        <a href="{{ route('expenses.show', $expense) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to expense</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">Edit expense</h1>
    </div>

    <form method="POST" action="{{ route('expenses.update', $expense) }}" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('expenses._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('expenses.show', $expense) }}" class="rounded-md px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
            <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Update expense</button>
        </div>
    </form>
</x-layouts.app>
