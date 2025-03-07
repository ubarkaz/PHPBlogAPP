<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Blog;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'commentable_id' => Blog::factory(),
            'commentable_type' => Blog::class,
            'content' => $this->faker->sentence(),
        ];
    }
}

