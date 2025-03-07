<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_be_fetched()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_a_user_can_be_created()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'johndoe@example.com']);
    }

    public function test_a_user_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_a_user_can_be_soft_deleted()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_a_user_can_be_restored()
    {
        $user = User::factory()->create();
        $user->delete(); // Soft delete the user
        
        $response = $this->patchJson("/api/users/{$user->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    public function test_a_user_can_be_permanently_deleted()
    {
        $user = User::factory()->create();
        $user->delete(); // Soft delete first

        $response = $this->deleteJson("/api/users/{$user->id}/force-delete");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
