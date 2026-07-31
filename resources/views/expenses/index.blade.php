<x-layouts.app title="Expenses">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Expenses</h1>
            <p class="mt-1 text-sm text-zinc-600">Kelola biaya operasional, vendor, dan receipt project.</p>
        </div>
        @can('expenses.create')
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New expense</a>
        @endcan
    </div>

    @if ($expenses->isEmpty())
        <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-8 text-center">
            <h2 class="text-base font-medium text-zinc-950">Belum ada expense</h2>
            <p class="mt-2 text-sm text-zinc-600">Catat expense umum atau hubungkan ke project.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3 text-zinc-700">{{ $expense->expense_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 font-medium text-zinc-950">{{ $expense->category }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $expense->project?->name ?: '-' }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $expense->vendor ?: '-' }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ number_format((float) $expense->amount, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('expenses.show', $expense) }}" class="font-medium text-zinc-950 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $expenses->links() }}</div>
    @endif
</x-layouts.app>
