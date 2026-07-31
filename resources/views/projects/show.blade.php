<x-layouts.app title="{{ $project->name }}">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950">Back to projects</a>
            <h1 class="mt-3 text-2xl font-semibold text-zinc-950">{{ $project->name }}</h1>
            <p class="mt-1 text-sm text-zinc-600">Client: {{ $project->client->name }}</p>
        </div>
        <div class="flex gap-2">
            @can('projects.update')
                <a href="{{ route('projects.edit', $project) }}" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">Edit</a>
            @endcan
            @can('projects.delete')
                <form action="{{ route('projects.destroy', $project) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="grid gap-5 md:grid-cols-2">
            <div><p class="text-sm text-zinc-500">Status</p><p class="mt-1 font-medium text-zinc-950">{{ $project->status }}</p></div>
            <div><p class="text-sm text-zinc-500">Progress</p><p class="mt-1 font-medium text-zinc-950">{{ $project->progress }}%</p></div>
            <div><p class="text-sm text-zinc-500">Project value</p><p class="mt-1 font-medium text-zinc-950">{{ number_format((float) $project->project_value, 2) }}</p></div>
            <div><p class="text-sm text-zinc-500">Start date</p><p class="mt-1 font-medium text-zinc-950">{{ $project->start_date->format('Y-m-d') }}</p></div>
            <div><p class="text-sm text-zinc-500">Deadline</p><p class="mt-1 font-medium text-zinc-950">{{ $project->deadline->format('Y-m-d') }}</p></div>
            <div class="md:col-span-2"><p class="text-sm text-zinc-500">Description</p><p class="mt-1 whitespace-pre-line font-medium text-zinc-950">{{ $project->description ?: '-' }}</p></div>
        </div>
    </section>

    @can('documents.view')
        <section class="mt-6">
            @include('documents._panel', [
                'documents' => $project->documents,
                'title' => 'Project documents',
                'storeRoute' => route('projects.documents.store', $project),
            ])
        </section>
    @endcan

    @can('project-workflow.view')
        <section class="mt-6 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-950">Milestones</h2>
                    <p class="mt-1 text-sm text-zinc-600">Progress = weighted average milestone progress. Milestone progress = average active task progress.</p>
                </div>
                @can('project-workflow.manage')
                    <a href="{{ route('projects.milestones.create', $project) }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New milestone</a>
                @endcan
            </div>

            @if ($project->milestones->isEmpty())
                <p class="text-sm text-zinc-600">Belum ada milestone.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                            <tr>
                                <th class="px-4 py-3">Milestone</th>
                                <th class="px-4 py-3">Target</th>
                                <th class="px-4 py-3">Weight</th>
                                <th class="px-4 py-3">Progress</th>
                                @can('project-workflow.manage')
                                    <th class="px-4 py-3 text-right">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($project->milestones as $milestone)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-zinc-950">{{ $milestone->title }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $milestone->target_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $milestone->weight }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $milestone->progress }}%</td>
                                    @can('project-workflow.manage')
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('projects.milestones.edit', [$project, $milestone]) }}" class="font-medium text-zinc-950 hover:underline">Edit</a>
                                                <form action="{{ route('projects.milestones.destroy', [$project, $milestone]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="font-medium text-red-600 hover:underline">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="mt-6 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-950">Tasks</h2>
                    <p class="mt-1 text-sm text-zinc-600">Form-based workflow for Backlog, To Do, In Progress, Review, Done, and Cancelled.</p>
                </div>
                @can('project-workflow.manage')
                    <a href="{{ route('projects.tasks.create', $project) }}" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">New task</a>
                @endcan
            </div>

            @if ($project->tasks->isEmpty())
                <p class="text-sm text-zinc-600">Belum ada task.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                            <tr>
                                <th class="px-4 py-3">Task</th>
                                <th class="px-4 py-3">Milestone</th>
                                <th class="px-4 py-3">Assignee</th>
                                <th class="px-4 py-3">Priority</th>
                                <th class="px-4 py-3">Due</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Progress</th>
                                <th class="px-4 py-3">Documents</th>
                                @can('project-workflow.manage')
                                    <th class="px-4 py-3 text-right">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($project->tasks as $task)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-zinc-950">{{ $task->title }}</p>
                                        <p class="mt-1 max-w-sm text-xs text-zinc-600">{{ $task->description ?: '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $task->milestone?->title ?: '-' }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $task->assignee ?: '-' }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $task->priority }}</td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $task->due_date?->format('Y-m-d') ?: '-' }}</td>
                                    <td class="px-4 py-3"><span class="rounded-full bg-zinc-100 px-2 py-1 text-xs text-zinc-700">{{ $task->status }}</span></td>
                                    <td class="px-4 py-3 text-zinc-700">{{ $task->progress }}%</td>
                                    <td class="px-4 py-3">
                                        @can('documents.view')
                                            @include('documents._panel', [
                                                'documents' => $task->documents,
                                                'title' => 'Task documents',
                                                'storeRoute' => route('projects.tasks.documents.store', [$project, $task]),
                                                'compact' => true,
                                            ])
                                        @endcan
                                    </td>
                                    @can('project-workflow.manage')
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="font-medium text-zinc-950 hover:underline">Edit</a>
                                                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="font-medium text-red-600 hover:underline">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="mt-6 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-zinc-950">Activity timeline</h2>
            @if ($project->activities->isEmpty())
                <p class="mt-3 text-sm text-zinc-600">Belum ada aktivitas workflow.</p>
            @else
                <div class="mt-4 divide-y divide-zinc-100">
                    @foreach ($project->activities as $activity)
                        <div class="py-3">
                            <p class="text-sm font-medium text-zinc-950">{{ $activity->description }}</p>
                            <p class="mt-1 text-xs text-zinc-500">{{ $activity->event }} oleh {{ $activity->user?->name ?: 'System' }} pada {{ $activity->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endcan
</x-layouts.app>
