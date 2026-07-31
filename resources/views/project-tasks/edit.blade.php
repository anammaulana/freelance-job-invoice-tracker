<x-layouts.app title="Edit task">
    <div class="mb-6">
        <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to project</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">Edit task</h1>
        <p class="mt-1 text-sm text-zinc-600">{{ $project->name }}</p>
    </div>

    <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('project-tasks._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('projects.show', $project) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
            <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Update task</button>
        </div>
    </form>
</x-layouts.app>
