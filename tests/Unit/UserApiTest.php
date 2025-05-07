<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_unauthorized_user_cannot_access_protected_route()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401); // Non authentifié
    }

    public function test_authenticated_user_can_access_index()
    {
        $user = User::factory()->create([
            'role' => 'Super-Administrateur',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->getJson('/api/users');

        $response->assertStatus(200);
    }

    public function test_super_admin_can_update_user()
    {
        $superAdmin = User::factory()->create(['role' => 'Super-Administrateur']);
        $user = User::factory()->create();

        $token = $superAdmin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->putJson("/api/users/{$user->id}", [
                            'firstname' => 'UpdatedName',
                            'email' => 'newemail@example.com'
                        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'UpdatedName',
            'email' => 'newemail@example.com'
        ]);
    }

    public function test_user_cannot_update_other_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->putJson("/api/users/{$otherUser->id}", [
                            'firstname' => 'ShouldFail'
                        ]);

        $response->assertStatus(403);
    }

    public function test_super_admin_can_delete_user()
    {
        $superAdmin = User::factory()->create(['role' => 'Super-Administrateur']);
        $user = User::factory()->create();

        $token = $superAdmin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_other_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403);
    }
}
