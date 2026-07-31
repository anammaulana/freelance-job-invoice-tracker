<x-layouts.app title="Clients">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Clients</h1>
            <p class="mt-1 text-sm text-zinc-600">Kelola data client freelancer.</p>
        </div>
        @can('clients.create')
            <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New client</a>
        @endcan
    </div>

    @if ($clients->isEmpty())
        <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-8 text-center">
            <h2 class="text-base font-medium text-zinc-950">Belum ada client</h2>
            <p class="mt-2 text-sm text-zinc-600">Tambahkan client pertama untuk mulai membuat project.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Projects</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($clients as $client)
                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-950">{{ $client->name }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $client->company ?: '-' }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $client->email ?: '-' }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $client->projects_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('clients.show', $client) }}" class="font-medium text-zinc-950 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $clients->links() }}</div>
    @endif
</x-layouts.app>
