<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_user_can_login_and_logout(): void
    {
        $this->seed();

        $this->post(route('login.store'), [
            'email' => 'demo@example.com',
            'password' => 'password',
        ])->assertRedirect(route('clients.index'));

        $this->assertAuthenticated();

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_application_pages_require_authentication(): void
    {
        $this->get(route('clients.index'))->assertRedirect(route('login'));
        $this->get(route('projects.index'))->assertRedirect(route('login'));
    }

    public function test_invalid_login_returns_validation_error(): void
    {
        User::factory()->create([
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'email' => 'demo@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
