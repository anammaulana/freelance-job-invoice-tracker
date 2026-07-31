<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['project_id', 'project_milestone_id', 'title', 'assignee', 'priority', 'due_date', 'description', 'progress', 'status'])]
class ProjectTask extends Model
{
    use SoftDeletes;

    public const STATUS_BACKLOG = 'Backlog';

    public const STATUS_TO_DO = 'To Do';

    public const STATUS_IN_PROGRESS = 'In Progress';

    public const STATUS_REVIEW = 'Review';

    public const STATUS_DONE = 'Done';

    public const STATUS_CANCELLED = 'Cancelled';

    public const STATUSES = [
        self::STATUS_BACKLOG,
        self::STATUS_TO_DO,
        self::STATUS_IN_PROGRESS,
        self::STATUS_REVIEW,
        self::STATUS_DONE,
        self::STATUS_CANCELLED,
    ];

    public const PRIORITIES = ['Low', 'Medium', 'High', 'Urgent'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'progress' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'project_milestone_id');
    }
}
