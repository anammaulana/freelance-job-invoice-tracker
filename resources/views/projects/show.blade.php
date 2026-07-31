<x-layouts.app title="{{ $project->name }}">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to projects</a>
            <h1 class="mt-3 text-2xl font-semibold text-zinc-950">{{ $project->name }}</h1>
            <p class="mt-1 text-sm text-zinc-600">Client: {{ $project->client->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Edit</a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete</button>
            </form>
        </div>
    </div>

    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="grid gap-5 md:grid-cols-2">
            <div><p class="text-sm text-zinc-500">Status</p><p class="mt-1 font-medium text-zinc-950">{{ $project->status }}</p></div>
            <div><p class="text-sm text-zinc-500">Project value</p><p class="mt-1 font-medium text-zinc-950">{{ number_format((float) $project->project_value, 2) }}</p></div>
            <div><p class="text-sm text-zinc-500">Start date</p><p class="mt-1 font-medium text-zinc-950">{{ $project->start_date->format('Y-m-d') }}</p></div>
            <div><p class="text-sm text-zinc-500">Deadline</p><p class="mt-1 font-medium text-zinc-950">{{ $project->deadline->format('Y-m-d') }}</p></div>
            <div class="md:col-span-2"><p class="text-sm text-zinc-500">Description</p><p class="mt-1 whitespace-pre-line font-medium text-zinc-950">{{ $project->description ?: '-' }}</p></div>
        </div>
    </section>
</x-layouts.app>
