<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_list_view_update_and_delete_project(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Acme Client']);

        $createResponse = $this->actingAs($user)->post(route('projects.store'), [
            'client_id' => $client->id,
            'name' => 'Website Build',
            'description' => 'Company profile website.',
            'start_date' => '2026-08-01',
            'deadline' => '2026-08-15',
            'project_value' => '12000.50',
            'status' => Project::STATUS_DRAFT,
        ]);

        $project = Project::firstOrFail();
        $createResponse->assertRedirect(route('projects.show', $project));

        $this->actingAs($user)->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Website Build')
            ->assertSee('Acme Client');

        $this->actingAs($user)->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Company profile website.');

        $this->actingAs($user)->put(route('projects.update', $project), [
            'client_id' => $client->id,
            'name' => 'Website Retainer',
            'description' => 'Monthly website support.',
            'start_date' => '2026-08-01',
            'deadline' => '2026-09-01',
            'project_value' => '15000',
            'status' => Project::STATUS_ACTIVE,
        ])->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Website Retainer',
            'status' => Project::STATUS_ACTIVE,
        ]);

        $this->actingAs($user)->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_project_status_must_be_limited_to_approved_values(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $this->actingAs($user)->post(route('projects.store'), [
            'client_id' => $client->id,
            'name' => 'Invalid Status Project',
            'start_date' => '2026-08-01',
            'deadline' => '2026-08-15',
            'project_value' => '1000',
            'status' => 'Paused',
        ])->assertSessionHasErrors('status');
    }

    public function test_project_deadline_cannot_be_earlier_than_start_date(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $this->actingAs($user)->post(route('projects.store'), [
            'client_id' => $client->id,
            'name' => 'Invalid Date Project',
            'start_date' => '2026-08-15',
            'deadline' => '2026-08-01',
            'project_value' => '1000',
            'status' => Project::STATUS_DRAFT,
        ])->assertSessionHasErrors('deadline');
    }
}
