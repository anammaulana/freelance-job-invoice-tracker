@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="title" class="block text-sm font-medium text-zinc-700">Title</label>
        <input id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="project_milestone_id" class="block text-sm font-medium text-zinc-700">Milestone</label>
        <select id="project_milestone_id" name="project_milestone_id" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            <option value="">No milestone</option>
            @foreach ($milestones as $milestone)
                <option value="{{ $milestone->id }}" @selected(old('project_milestone_id', $task->project_milestone_id ?? '') == $milestone->id)>{{ $milestone->title }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="assignee" class="block text-sm font-medium text-zinc-700">Assignee</label>
        <input id="assignee" name="assignee" value="{{ old('assignee', $task->assignee ?? '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="priority" class="block text-sm font-medium text-zinc-700">Priority</label>
        <select id="priority" name="priority" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @foreach ($priorities as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $task->priority ?? 'Medium') === $priority)>{{ $priority }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="due_date" class="block text-sm font-medium text-zinc-700">Due date</label>
        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-zinc-700">Status</label>
        <select id="status" name="status" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $task->status ?? 'Backlog') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="progress" class="block text-sm font-medium text-zinc-700">Progress</label>
        <input id="progress" name="progress" type="number" min="0" max="100" step="1" value="{{ old('progress', $task->progress ?? 0) }}" required class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-zinc-700">Description</label>
        <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $task->description ?? '') }}</textarea>
    </div>
</div>
