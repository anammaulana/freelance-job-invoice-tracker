@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="title" class="block text-sm font-medium text-zinc-700">Title</label>
        <input id="title" name="title" value="{{ old('title', $milestone->title ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="target_date" class="block text-sm font-medium text-zinc-700">Target date</label>
        <input id="target_date" name="target_date" type="date" value="{{ old('target_date', isset($milestone) ? $milestone->target_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="weight" class="block text-sm font-medium text-zinc-700">Weight</label>
        <input id="weight" name="weight" type="number" min="1" max="100" step="1" value="{{ old('weight', $milestone->weight ?? 10) }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-zinc-700">Description</label>
        <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $milestone->description ?? '') }}</textarea>
    </div>
</div>
