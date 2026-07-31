<x-layouts.app title="New Invoice">
    <div class="mb-6">
        <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to invoices</a>
        <h1 class="mt-3 text-2xl font-semibold text-zinc-950">New invoice</h1>
    </div>

    @if ($projects->isEmpty())
        <div class="rounded-lg border border-dashed border-zinc-300 bg-white p-8 text-center">
            <h2 class="text-base font-medium text-zinc-950">Project belum tersedia</h2>
            <p class="mt-2 text-sm text-zinc-600">Tambahkan project sebelum membuat invoice.</p>
            <a href="{{ route('projects.create') }}" class="mt-4 inline-flex rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New project</a>
        </div>
    @else
        <form action="{{ route('invoices.store') }}" method="POST" class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            @include('invoices._form')
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('invoices.index') }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Cancel</a>
                <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Save invoice</button>
            </div>
        </form>
    @endif
</x-layouts.app>
