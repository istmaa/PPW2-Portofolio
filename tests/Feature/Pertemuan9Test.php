<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Pertemuan9Test extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_protected_routes(): void
    {
        $this->get('/post/profile-saya')->assertRedirect('/login');
        $this->get('/post/projects')->assertRedirect('/login');
        $this->get('/projects/trash')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
    }

    public function test_registration_route_is_disabled_and_returns_404(): void
    {
        $this->get('/register')->assertNotFound();

        $response = $this->post('/register', [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertNotFound();
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
    }

    public function test_public_pages_are_accessible_to_guest(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Login');
        $this->get(route('about'))->assertOk()->assertSee('Login');
        $this->get(route('education'))->assertOk()->assertSee('Login');
        $this->get(route('projects.index'))->assertOk();
    }

    public function test_navbar_displays_authenticated_user_name_and_logout_button(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertSee('Logout');
        $response->assertDontSee('>Login<', false);
    }

    public function test_authenticated_user_can_access_profile_page_with_correct_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Rahma',
            'email' => 'siti@example.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/post/profile-saya');

        $response->assertOk();
        $response->assertSee('Profil Saya');
        $response->assertSee('Siti Rahma');
        $response->assertSee('siti@example.com');
        $response->assertSee('User');
    }

    public function test_user_with_non_admin_role_receives_403_on_admin_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)->get('/projects/trash')->assertForbidden();
        $this->actingAs($user)->get('/projects/create')->assertForbidden();
    }

    public function test_admin_role_can_access_admin_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/projects/trash')->assertOk();
        $this->actingAs($admin)->get('/projects/create')->assertOk();
        $this->actingAs($admin)->get('/post/profile-saya')->assertOk();
    }

    public function test_ensure_admin_checks_role_column_not_hardcoded_email(): void
    {
        $fakeAdminEmail = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'user',
        ]);

        $this->actingAs($fakeAdminEmail)->get('/projects/trash')->assertForbidden();

        $realAdminOtherEmail = User::factory()->create([
            'email' => 'pengajar@ugm.ac.id',
            'role' => 'admin',
        ]);

        $this->actingAs($realAdminOtherEmail)->get('/projects/trash')->assertOk();
    }

    public function test_login_authenticates_and_redirects_appropriately(): void
    {
        $user = User::factory()->create([
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'mahasiswa@example.com',
            'password' => 'rahasia123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/post/projects');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'mahasiswa@example.com',
            'password' => 'salah',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_logout_clears_session_and_redirects_to_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');

        $this->get(route('home'))->assertSee('Login');
    }
}

