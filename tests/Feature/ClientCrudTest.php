<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_list_view_update_and_delete_client(): void
    {
        $user = User::factory()->create();

        $createResponse = $this->actingAs($user)->post(route('clients.store'), [
            'name' => 'Acme Client',
            'email' => 'client@example.com',
            'phone_number' => '08123456789',
            'company' => 'Acme Studio',
            'address' => 'Jalan Sprint 1',
        ]);

        $client = Client::firstOrFail();
        $createResponse->assertRedirect(route('clients.show', $client));

        $this->actingAs($user)->get(route('clients.index'))
            ->assertOk()
            ->assertSee('Acme Client');

        $this->actingAs($user)->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee('client@example.com');

        $this->actingAs($user)->put(route('clients.update', $client), [
            'name' => 'Updated Client',
            'email' => 'updated@example.com',
            'phone_number' => '089999999',
            'company' => 'Updated Co',
            'address' => 'Updated Address',
        ])->assertRedirect(route('clients.show', $client));

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Updated Client',
        ]);

        $this->actingAs($user)->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_client_requires_valid_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('clients.store'), [
            'name' => '',
            'email' => 'not-an-email',
        ])->assertSessionHasErrors(['name', 'email']);
    }

    public function test_client_cannot_be_deleted_when_it_has_active_project(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        Project::factory()->for($client)->create(['status' => Project::STATUS_ACTIVE]);

        $this->actingAs($user)->delete(route('clients.destroy', $client))
            ->assertSessionHasErrors('client');

        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }
}
