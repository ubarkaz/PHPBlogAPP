<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Get all blogs
    public function index()
    {
        return response()->json(Blog::with('user')->get(), 200);
    }

    // Create a new blog
    public function store(Request $request)
    {
        
        $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200', // Validate image
    ]);

        // Handle file upload
        if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('blog_images', 'public');
        }   else {
            $imagePath = null;
        }

        $blog = Blog::create([
        'title' => $request->title,
        'content' => $request->content,
        'user_id' => $request->user_id,
        'image' => $imagePath, // Store image path in DB
    ]);

    return response()->json($blog, 201);
    }

    // Get a specific blog
    public function show($id)
    {
        $blog = Blog::with('user')->find($id);
        if (!$blog) {
        return response()->json(['message' => 'Blog not found'], 404);
        }

        // Add full image URL
        $blog->image = $blog->image ? asset('storage/' . $blog->image) : null;

        return response()->json($blog, 200);
    }

    // Update a blog
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        $data = $request->all();

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }

            $imagePath = $request->file('image')->store('blog_images', 'public');
            $data['image'] = $imagePath;
        }

        $blog->update($data); 

        return response()->json(['message' => 'Blog updated successfully', 'blog' => $blog], 200);
    }


    //soft - delete a blog  
    public function destroy($id)
    {   
        $blog = Blog::find($id);
            if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

            // Delete image from storage
            if ($blog->image) {
            \Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog soft - deleted successfully'], 200);
    }


    //Restore the soft-deleted user
    public function restore($id)
    {
        $blog = Blog::withTrashed()->find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->restore();

        return response()->json(['message' => 'Blog restored'], 200);
    }

    // Permanently delete a user
    public function forceDelete($id)
    {
        $blog = Blog::withTrashed()->find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->forceDelete();

        return response()->json(['message' => 'Blog permanently deleted'], 200);
    }
}
