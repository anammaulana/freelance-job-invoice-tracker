<x-layouts.app title="{{ $client->name }}">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('clients.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to clients</a>
            <h1 class="mt-3 text-2xl font-semibold text-zinc-950">{{ $client->name }}</h1>
            <p class="mt-1 text-sm text-zinc-600">{{ $client->company ?: 'No company' }}</p>
        </div>
        <div class="flex gap-2">
            @can('clients.update')
                <a href="{{ route('clients.edit', $client) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Edit</a>
            @endcan
            @can('clients.delete')
                <form action="{{ route('clients.destroy', $client) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm lg:col-span-1">
            <h2 class="text-base font-semibold text-zinc-950">Client detail</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div><dt class="text-zinc-500">Email</dt><dd class="font-medium text-zinc-900">{{ $client->email ?: '-' }}</dd></div>
                <div><dt class="text-zinc-500">Phone</dt><dd class="font-medium text-zinc-900">{{ $client->phone_number ?: '-' }}</dd></div>
                <div><dt class="text-zinc-500">Address</dt><dd class="font-medium text-zinc-900">{{ $client->address ?: '-' }}</dd></div>
            </dl>
        </section>
        <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-base font-semibold text-zinc-950">Projects</h2>
            @if ($client->projects->isEmpty())
                <p class="mt-4 rounded-md border border-dashed border-zinc-300 p-4 text-sm text-zinc-600">Client ini belum memiliki project.</p>
            @else
                <div class="mt-4 divide-y divide-zinc-100">
                    @foreach ($client->projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="flex items-center justify-between py-3 text-sm hover:bg-zinc-50">
                            <span class="font-medium text-zinc-950">{{ $project->name }}</span>
                            <span class="rounded-full bg-zinc-100 px-2 py-1 text-xs text-zinc-700">{{ $project->status }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    @can('documents.view')
        <section class="mt-6">
            @include('documents._panel', [
                'documents' => $client->documents,
                'title' => 'Client documents',
                'storeRoute' => route('clients.documents.store', $client),
            ])
        </section>
    @endcan
</x-layouts.app>
