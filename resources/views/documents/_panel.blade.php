@props([
    'documents',
    'title' => 'Documents',
    'storeRoute' => null,
    'compact' => false,
])

<div class="{{ $compact ? 'space-y-3' : 'rounded-lg border border-zinc-200 bg-white p-6 shadow-sm' }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="{{ $compact ? 'text-sm' : 'text-lg' }} font-semibold text-zinc-950">{{ $title }}</h2>
            @unless ($compact)
                <p class="mt-1 text-sm text-zinc-600">Allowed: PDF, image, text, CSV, Word, and Excel files up to 5 MB.</p>
            @endunless
        </div>
        @can('documents.manage')
            @if ($storeRoute)
                <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2 sm:min-w-72">
                    @csrf
                    <input
                        type="file"
                        name="document"
                        required
                        class="block w-full text-sm text-zinc-700 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-zinc-700"
                    >
                    <button class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Upload</button>
                </form>
            @endif
        @endcan
    </div>

    @if ($documents->isEmpty())
        <p class="mt-4 rounded-md border border-dashed border-zinc-300 p-4 text-sm text-zinc-600">Belum ada document.</p>
    @else
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Size</th>
                        <th class="px-4 py-3">Uploaded</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach ($documents as $document)
                        <tr>
                            <td class="px-4 py-3 font-medium text-zinc-950">{{ $document->original_filename }}</td>
                            <td class="px-4 py-3 text-zinc-700">{{ $document->mime_type }}</td>
                            <td class="px-4 py-3 text-zinc-700">{{ number_format($document->size / 1024, 1) }} KB</td>
                            <td class="px-4 py-3 text-zinc-700">{{ $document->uploadedBy?->name ?: 'System' }}<br><span class="text-xs text-zinc-500">{{ $document->created_at->format('Y-m-d H:i') }}</span></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3">
                                    @can('documents.view')
                                        <a href="{{ route('documents.download', $document) }}" class="font-medium text-zinc-950 hover:underline">Download</a>
                                    @endcan
                                    @can('documents.manage')
                                        <form action="{{ route('documents.destroy', $document) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-medium text-red-600 hover:underline">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
