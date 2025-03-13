<?php

namespace App\Policies;
use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    /**
     * Determine if the user can create blogs.
     */
    public function create(User $user)
    {
        return $user->email === 'kasoziubar97@gmail.com';
    }

    /**
     * Determine if the user can update the blog.
     */
    public function update(User $user, Blog $blog)
    {
        return $user->email === 'kasoziubar97@gmail.com';
    }

    /**
     * Determine if any authenticated user can comment on blogs.
     */
    public function comment(User $user)
    {
        return $user !== null; // Any logged-in user can comment
    }
}
