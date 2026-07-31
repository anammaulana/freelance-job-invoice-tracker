<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectMilestoneRequest;
use App\Http\Requests\UpdateProjectMilestoneRequest;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Services\ProjectWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectMilestoneController extends Controller
{
    public function create(Project $project): View
    {
        return view('project-milestones.create', compact('project'));
    }

    public function store(StoreProjectMilestoneRequest $request, Project $project, ProjectWorkflowService $workflow): RedirectResponse
    {
        $milestone = $project->milestones()->create($request->validated());

        $workflow->refreshProgress($project);
        $workflow->record($project, $request->user(), $milestone, 'milestone.created', "Milestone {$milestone->title} dibuat.");

        return redirect()->route('projects.show', $project)->with('status', 'Milestone berhasil dibuat.');
    }

    public function edit(Project $project, ProjectMilestone $milestone): View
    {
        abort_unless($milestone->project_id === $project->id, 404);

        return view('project-milestones.edit', compact('project', 'milestone'));
    }

    public function update(UpdateProjectMilestoneRequest $request, Project $project, ProjectMilestone $milestone, ProjectWorkflowService $workflow): RedirectResponse
    {
        abort_unless($milestone->project_id === $project->id, 404);

        $milestone->update($request->validated());
        $workflow->refreshProgress($project);
        $workflow->record($project, $request->user(), $milestone, 'milestone.updated', "Milestone {$milestone->title} diperbarui.");

        return redirect()->route('projects.show', $project)->with('status', 'Milestone berhasil diperbarui.');
    }

    public function destroy(Project $project, ProjectMilestone $milestone, ProjectWorkflowService $workflow): RedirectResponse
    {
        abort_unless($milestone->project_id === $project->id, 404);

        $title = $milestone->title;
        $milestone->delete();
        $workflow->refreshProgress($project);
        $workflow->record($project, request()->user(), $milestone, 'milestone.deleted', "Milestone {$title} dihapus.");

        return redirect()->route('projects.show', $project)->with('status', 'Milestone berhasil dihapus.');
    }
}
