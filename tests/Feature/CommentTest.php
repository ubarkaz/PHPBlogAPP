<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comments_can_be_fetched()
    {
        $blog = Blog::factory()->create();
        Comment::factory()->count(3)->create([
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
        ]);

        $response = $this->getJson('/api/comments');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_a_comment_can_be_created()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $response = $this->postJson('/api/comments', [
            'user_id' => $user->id,
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
            'content' => 'This is a test comment',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', ['content' => 'This is a test comment']);
    }

    public function test_a_comment_can_be_updated()
    {
        $comment = Comment::factory()->create();

        $response = $this->putJson("/api/comments/{$comment->id}", [
            'content' => 'Updated Comment',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', ['content' => 'Updated Comment']);
    }

    public function test_a_comment_can_be_soft_deleted()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_a_comment_can_be_restored()
    {
        $comment = Comment::factory()->create();
        $comment->delete();

        $response = $this->patchJson("/api/comments/{$comment->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    public function test_a_comment_can_be_permanently_deleted()
    {
        $comment = Comment::factory()->create();
        $comment->delete(); // Soft delete first

        $response = $this->deleteJson("/api/comments/{$comment->id}/force-delete");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
