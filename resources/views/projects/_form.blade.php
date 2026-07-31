@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="client_id" class="block text-sm font-medium text-zinc-700">Client</label>
        <select id="client_id" name="client_id" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            <option value="">Select client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-zinc-700">Status</label>
        <select id="status" name="status" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $project->status ?? 'Draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-zinc-700">Project name</label>
        <input id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-zinc-700">Description</label>
        <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div>
        <label for="start_date" class="block text-sm font-medium text-zinc-700">Start date</label>
        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', isset($project) ? $project->start_date->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="deadline" class="block text-sm font-medium text-zinc-700">Deadline</label>
        <input id="deadline" name="deadline" type="date" value="{{ old('deadline', isset($project) ? $project->deadline->format('Y-m-d') : '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="project_value" class="block text-sm font-medium text-zinc-700">Project value</label>
        <input id="project_value" name="project_value" type="number" min="0" step="0.01" value="{{ old('project_value', $project->project_value ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
</div>
