<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProjectWorkflowService
{
    /**
     * Project progress formula:
     * - If active milestones exist, use the weighted average of milestone progress.
     * - Milestone progress is the average progress of its active tasks.
     * - A milestone without active tasks contributes 0%.
     * - If no active milestones exist, use the average progress of active project tasks.
     * - If no workflow records exist, project progress is 0%.
     */
    public function refreshProgress(Project $project): int
    {
        $project->loadMissing(['milestones.tasks', 'tasks']);

        foreach ($project->milestones as $milestone) {
            $taskProgress = $milestone->tasks->avg('progress');
            $milestone->forceFill([
                'progress' => $taskProgress === null ? 0 : (int) round($taskProgress),
            ])->save();
        }

        $project->load(['milestones', 'tasks']);

        if ($project->milestones->isNotEmpty()) {
            $totalWeight = max(1, (int) $project->milestones->sum('weight'));
            $progress = $project->milestones->sum(fn (ProjectMilestone $milestone) => $milestone->progress * $milestone->weight) / $totalWeight;
        } elseif ($project->tasks->isNotEmpty()) {
            $progress = $project->tasks->avg('progress') ?? 0;
        } else {
            $progress = 0;
        }

        $project->forceFill(['progress' => (int) round($progress)])->save();

        return $project->progress;
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function record(Project $project, ?User $user, ?Model $subject, string $event, string $description, array $properties = []): ProjectActivity
    {
        return ProjectActivity::create([
            'project_id' => $project->id,
            'user_id' => $user?->id,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }
}
