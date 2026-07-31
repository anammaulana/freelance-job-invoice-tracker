<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Services\ProjectWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectTaskController extends Controller
{
    public function create(Project $project): View
    {
        return view('project-tasks.create', [
            'project' => $project,
            'milestones' => $project->milestones()->orderBy('target_date')->get(),
            'statuses' => ProjectTask::STATUSES,
            'priorities' => ProjectTask::PRIORITIES,
        ]);
    }

    public function store(StoreProjectTaskRequest $request, Project $project, ProjectWorkflowService $workflow): RedirectResponse
    {
        $task = $project->tasks()->create($request->validated());

        $workflow->refreshProgress($project);
        $workflow->record($project, $request->user(), $task, 'task.created', "Task {$task->title} dibuat.");

        return redirect()->route('projects.show', $project)->with('status', 'Task berhasil dibuat.');
    }

    public function edit(Project $project, ProjectTask $task): View
    {
        abort_unless($task->project_id === $project->id, 404);

        return view('project-tasks.edit', [
            'project' => $project,
            'task' => $task,
            'milestones' => $project->milestones()->orderBy('target_date')->get(),
            'statuses' => ProjectTask::STATUSES,
            'priorities' => ProjectTask::PRIORITIES,
        ]);
    }

    public function update(UpdateProjectTaskRequest $request, Project $project, ProjectTask $task, ProjectWorkflowService $workflow): RedirectResponse
    {
        abort_unless($task->project_id === $project->id, 404);

        $task->update($request->validated());
        $workflow->refreshProgress($project);
        $workflow->record($project, $request->user(), $task, 'task.updated', "Task {$task->title} diperbarui.");

        return redirect()->route('projects.show', $project)->with('status', 'Task berhasil diperbarui.');
    }

    public function destroy(Project $project, ProjectTask $task, ProjectWorkflowService $workflow): RedirectResponse
    {
        abort_unless($task->project_id === $project->id, 404);

        $title = $task->title;
        $task->delete();
        $workflow->refreshProgress($project);
        $workflow->record($project, request()->user(), $task, 'task.deleted', "Task {$title} dihapus.");

        return redirect()->route('projects.show', $project)->with('status', 'Task berhasil dihapus.');
    }
}
