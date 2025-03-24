<?php

namespace App\Http\Controllers;

use Orion\Http\Controllers\Controller;
use App\Models\Blog;
use Orion\Http\Requests\Request;
use Illuminate\Support\Facades\Storage;

class BlogOrionController extends Controller
{
    protected $softDeletes = true;
    protected $model = Blog::class;

    // Automatically load user and media relationships
    protected $relations = ['user', 'media'];

    /**
     * Customized the store method to handle media uploads using spatie
     * and in s3
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi|max:102400', // 100MB max
        ]);

        $blog = Blog::create($validatedData);

        // Handle media upload using Spatie Media Library
        if ($request->hasFile('image')) {
            $blog->addMedia($request->file('image'))
                 ->toMediaCollection('uploads', 's3'); // Store in S3
        }

        return response()->json([
            'message' => 'Blog created successfully',
            'blog' => $blog->load('media'),
        ], 201);
    }
    
}
