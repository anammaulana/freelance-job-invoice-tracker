<x-layouts.app title="New Project">
    <div class="mb-6">
        <a href="{{ route('projects.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to projects</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">New project</h1>
    </div>

    @if ($clients->isEmpty())
        <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-8 text-center">
            <h2 class="text-base font-medium text-zinc-950">Client belum tersedia</h2>
            <p class="mt-2 text-sm text-zinc-600">Tambahkan client sebelum membuat project.</p>
            @can('clients.create')
                <a href="{{ route('clients.create') }}" class="mt-4 inline-flex rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New client</a>
            @endcan
        </div>
    @else
        <form action="{{ route('projects.store') }}" method="POST" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            @include('projects._form')
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('projects.index') }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
                <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Save project</button>
            </div>
        </form>
    @endif
</x-layouts.app>
