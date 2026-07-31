<x-layouts.app title="Projects">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-950">Projects</h1>
            <p class="mt-1 text-sm text-zinc-600">Kelola project untuk setiap client.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New project</a>
    </div>

    @if ($projects->isEmpty())
        <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-8 text-center">
            <h2 class="text-base font-medium text-zinc-950">Belum ada project</h2>
            <p class="mt-2 text-sm text-zinc-600">Buat project pertama setelah minimal satu client tersedia.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Deadline</th>
                            <th class="px-4 py-3">Value</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-950">{{ $project->name }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $project->client->name }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ $project->deadline->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-zinc-700">{{ number_format((float) $project->project_value, 2) }}</td>
                                <td class="px-4 py-3"><span class="rounded-full bg-zinc-100 px-2 py-1 text-xs text-zinc-700">{{ $project->status }}</span></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('projects.show', $project) }}" class="font-medium text-zinc-950 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $projects->links() }}</div>
    @endif
</x-layouts.app>
