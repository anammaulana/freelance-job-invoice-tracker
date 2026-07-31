<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use App\Support\RbacPermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_view_update_and_delete_milestone(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $response = $this->actingAs($user)->post(route('projects.milestones.store', $project), $this->milestonePayload([
            'title' => 'Discovery Complete',
        ]));

        $milestone = ProjectMilestone::firstOrFail();
        $response->assertRedirect(route('projects.show', $project));

        $this->actingAs($user)->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Discovery Complete');

        $this->actingAs($user)->put(route('projects.milestones.update', [$project, $milestone]), $this->milestonePayload([
            'title' => 'Design Complete',
            'weight' => 40,
        ]))->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_milestones', [
            'id' => $milestone->id,
            'title' => 'Design Complete',
            'weight' => 40,
        ]);

        $this->actingAs($user)->delete(route('projects.milestones.destroy', [$project, $milestone]))
            ->assertRedirect(route('projects.show', $project));

        $this->assertSoftDeleted('project_milestones', ['id' => $milestone->id]);
        $this->assertDatabaseHas('project_activities', ['event' => 'milestone.deleted']);
    }

    public function test_milestone_target_date_and_weight_are_validated(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $this->actingAs($user)->post(route('projects.milestones.store', $project), $this->milestonePayload([
            'target_date' => 'not-a-date',
            'weight' => 0,
        ]))->assertSessionHasErrors(['target_date', 'weight']);
    }

    public function test_admin_can_create_view_update_and_delete_task(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();
        $milestone = $project->milestones()->create($this->milestonePayload());

        $response = $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'project_milestone_id' => $milestone->id,
            'title' => 'Build workflow screens',
        ]));

        $task = ProjectTask::firstOrFail();
        $response->assertRedirect(route('projects.show', $project));

        $this->actingAs($user)->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Build workflow screens')
            ->assertSee('Lincon');

        $this->actingAs($user)->put(route('projects.tasks.update', [$project, $task]), $this->taskPayload([
            'project_milestone_id' => $milestone->id,
            'title' => 'Review workflow screens',
            'status' => ProjectTask::STATUS_REVIEW,
            'progress' => 80,
        ]))->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_tasks', [
            'id' => $task->id,
            'title' => 'Review workflow screens',
            'status' => ProjectTask::STATUS_REVIEW,
            'progress' => 80,
        ]);

        $this->actingAs($user)->delete(route('projects.tasks.destroy', [$project, $task]))
            ->assertRedirect(route('projects.show', $project));

        $this->assertSoftDeleted('project_tasks', ['id' => $task->id]);
        $this->assertDatabaseHas('project_activities', ['event' => 'task.deleted']);
    }

    public function test_task_fields_and_status_are_validated(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create();

        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'title' => '',
            'priority' => 'Critical',
            'due_date' => 'invalid-date',
            'progress' => 101,
            'status' => 'Blocked',
        ]))->assertSessionHasErrors(['title', 'priority', 'due_date', 'progress', 'status']);
    }

    public function test_project_progress_uses_weighted_milestone_task_progress(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['progress' => 0]);
        $firstMilestone = $project->milestones()->create($this->milestonePayload(['title' => 'M1', 'weight' => 30]));
        $secondMilestone = $project->milestones()->create($this->milestonePayload(['title' => 'M2', 'weight' => 70]));

        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'project_milestone_id' => $firstMilestone->id,
            'title' => 'Task 1',
            'progress' => 50,
        ]));
        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'project_milestone_id' => $firstMilestone->id,
            'title' => 'Task 2',
            'progress' => 100,
        ]));
        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'project_milestone_id' => $secondMilestone->id,
            'title' => 'Task 3',
            'progress' => 20,
        ]));

        $this->assertSame(37, $project->fresh()->progress);
        $this->assertSame(75, $firstMilestone->fresh()->progress);
        $this->assertSame(20, $secondMilestone->fresh()->progress);
    }

    public function test_project_progress_uses_average_task_progress_without_milestones(): void
    {
        $user = User::factory()->admin()->create();
        $project = Project::factory()->for(Client::factory())->create(['progress' => 0]);

        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'title' => 'Standalone task 1',
            'progress' => 25,
        ]));
        $this->actingAs($user)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'title' => 'Standalone task 2',
            'progress' => 75,
        ]));

        $this->assertSame(50, $project->fresh()->progress);
    }

    public function test_project_workflow_rbac_allows_managers_and_blocks_read_only_or_finance_writes(): void
    {
        $project = Project::factory()->for(Client::factory())->create();
        $admin = User::factory()->admin()->create();
        $manager = User::factory()->role(RbacPermissions::PROJECT_MANAGER)->create();
        $viewer = User::factory()->role(RbacPermissions::VIEWER)->create();
        $finance = User::factory()->role(RbacPermissions::FINANCE)->create();

        $this->actingAs($admin)->post(route('projects.milestones.store', $project), $this->milestonePayload([
            'title' => 'Admin milestone',
        ]))->assertRedirect();

        $this->actingAs($manager)->post(route('projects.tasks.store', $project), $this->taskPayload([
            'title' => 'Manager task',
        ]))->assertRedirect();

        $this->actingAs($viewer)->get(route('projects.show', $project))->assertOk()->assertSee('Manager task');
        $this->actingAs($viewer)->get(route('projects.tasks.create', $project))->assertForbidden();
        $this->actingAs($viewer)->post(route('projects.tasks.store', $project), $this->taskPayload())->assertForbidden();

        $this->actingAs($finance)->get(route('projects.show', $project))->assertForbidden();
        $this->actingAs($finance)->post(route('projects.tasks.store', $project), $this->taskPayload())->assertForbidden();
        $this->actingAs($finance)->post(route('projects.milestones.store', $project), $this->milestonePayload())->assertForbidden();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function milestonePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Implementation Milestone',
            'description' => 'Milestone description.',
            'target_date' => '2026-08-15',
            'weight' => 50,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function taskPayload(array $overrides = []): array
    {
        return array_merge([
            'project_milestone_id' => null,
            'title' => 'Implementation Task',
            'assignee' => 'Lincon',
            'priority' => 'High',
            'due_date' => '2026-08-10',
            'description' => 'Task description.',
            'progress' => 10,
            'status' => ProjectTask::STATUS_BACKLOG,
        ], $overrides);
    }
}
