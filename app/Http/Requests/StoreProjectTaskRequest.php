<?php

namespace App\Http\Requests;

use App\Models\ProjectTask;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_milestone_id' => ['nullable', Rule::exists('project_milestones', 'id')->where('project_id', $this->route('project')->id)],
            'title' => ['required', 'string', 'max:255'],
            'assignee' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', Rule::in(ProjectTask::PRIORITIES)],
            'due_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:4000'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(ProjectTask::STATUSES)],
        ];
    }
}
