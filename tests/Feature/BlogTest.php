<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase; // This will reset the database after each test

    /** @test */
    public function a_blog_can_be_created()
    {
        $user = User::factory()->create(); // Create a user
        $blogData = [
            'title' => 'My First Blog',
            'content' => 'This is the content of the blog.',
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/blogs', $blogData);

        $response->assertStatus(201)
                 ->assertJson(['title' => 'My First Blog']);

        $this->assertDatabaseHas('blogs', ['title' => 'My First Blog']);
    }

    /** @test */
    public function all_blogs_can_be_fetched()
    {
        Blog::factory()->count(3)->create();

        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function a_blog_can_be_updated()
    {
        $blog = Blog::factory()->create();

        $response = $this->putJson("/api/blogs/{$blog->id}", [
            'title' => 'Updated Blog Title',
            'content' => 'Updated blog content'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('blogs', ['title' => 'Updated Blog Title']);
    }

    /** @test */
    public function a_blog_can_be_deleted()
    {
        $blog = Blog::factory()->create();

        $response = $this->deleteJson("/api/blogs/{$blog->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('blogs', ['id' => $blog->id]);

    }

    public function test_a_blog_can_be_restored()
    {
        $blog = Blog::factory()->create();
        $blog->delete();

        $response = $this->patchJson("/api/blogs/{$blog->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'deleted_at' => null]);
    }

    public function test_a_blog_can_be_permanently_deleted()
    {
        $blog = Blog::factory()->create();
        $blog->delete(); // Soft delete first

        $response = $this->deleteJson("/api/blogs/{$blog->id}/force-delete");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }
}
