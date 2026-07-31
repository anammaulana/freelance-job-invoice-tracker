<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuditLogService
{
    /**
     * @var array<int, class-string<Model>>
     */
    private const AUDITED_MODELS = [
        Client::class,
        Project::class,
        Invoice::class,
        Payment::class,
        Expense::class,
        Document::class,
        ProjectMilestone::class,
        ProjectTask::class,
    ];

    /**
     * @var array<int, string>
     */
    private const ALWAYS_EXCLUDED_FIELDS = [
        'password',
        'remember_token',
        'current_password',
        'password_confirmation',
        'stored_path',
        'disk',
    ];

    /**
     * @var array<string, array<int, string>>
     */
    private const MODEL_ALLOWED_FIELDS = [
        Document::class => [
            'attachable_type',
            'attachable_id',
            'original_filename',
            'mime_type',
            'size',
            'uploaded_by_user_id',
        ],
    ];

    public static function auditedModels(): array
    {
        return self::AUDITED_MODELS;
    }

    public function created(Model $model, ?User $actor): void
    {
        $this->record($actor, 'created', $model, [
            'after' => $this->safeAttributes($model, $model->getAttributes()),
        ]);
    }

    public function updated(Model $model, ?User $actor): void
    {
        $changed = array_keys(Arr::except($model->getChanges(), ['updated_at']));

        if ($changed === []) {
            return;
        }

        $before = [];
        $after = [];

        foreach ($changed as $field) {
            $before[$field] = $model->getOriginal($field);
            $after[$field] = $model->getAttribute($field);
        }

        $changes = array_filter([
            'before' => $this->safeAttributes($model, $before),
            'after' => $this->safeAttributes($model, $after),
        ]);

        if ($changes === []) {
            return;
        }

        $this->record($actor, 'updated', $model, $changes);
    }

    public function deleting(Model $model, ?User $actor): void
    {
        $action = method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()
            ? 'soft_deleted'
            : 'deleted';

        $this->record($actor, $action, $model, [
            'before' => $this->safeAttributes($model, $model->getAttributes()),
        ]);
    }

    /**
     * @param  array<string, mixed>|null  $changes
     */
    private function record(?User $actor, string $action, Model $target, ?array $changes): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        AuditLog::create([
            'actor_user_id' => $actor?->id,
            'actor_name' => $actor?->name,
            'actor_email' => $actor?->email,
            'action' => $action,
            'target_type' => $target->getMorphClass(),
            'target_id' => $target->getKey(),
            'changes' => $changes,
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function safeAttributes(Model $model, array $attributes): array
    {
        $allowedFields = self::MODEL_ALLOWED_FIELDS[$model::class] ?? null;

        return collect($attributes)
            ->when($allowedFields !== null, fn ($collection) => $collection->only($allowedFields))
            ->reject(fn (mixed $value, string $field): bool => $this->isSensitiveField($field))
            ->map(fn (mixed $value): mixed => $this->summarizeValue($value))
            ->all();
    }

    private function isSensitiveField(string $field): bool
    {
        if (in_array($field, self::ALWAYS_EXCLUDED_FIELDS, true)) {
            return true;
        }

        return Str::contains(Str::lower($field), [
            'password',
            'token',
            'secret',
            'api_key',
            'apikey',
            'private_key',
            'credential',
            'stored_path',
        ]);
    }

    private function summarizeValue(mixed $value): mixed
    {
        if ($value === null || is_bool($value) || is_int($value) || is_float($value)) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return '[array]';
        }

        if (is_object($value)) {
            return '[object]';
        }

        return Str::limit((string) $value, 160, '...');
    }
}
