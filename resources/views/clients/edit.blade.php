<x-layouts.app title="Edit Client">
    <div class="mb-6">
        <a href="{{ route('clients.show', $client) }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to detail</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">Edit client</h1>
    </div>

    <form action="{{ route('clients.update', $client) }}" method="POST" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('clients._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('clients.show', $client) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
            <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Update client</button>
        </div>
    </form>
</x-layouts.app>
