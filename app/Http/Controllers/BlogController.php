<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi|max:102400', // 100MB max
    ]);

        $data = $request->except('image');

        // Handle file upload
        if ($request->hasFile('image')) {
        $path = $request->file('image')->store('uploads', 's3'); // Store in S3
        $url = Storage::disk('s3')->url($path); // Get public URL
        $data['image'] = $url; // Store URL in database
    }

        $blog = Blog::create($data);

        return response()->json(['message' => 'Blog created successfully', 'blog' => $blog], 201);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
    
        $data = $request->only(['title', 'content']); // Get fields that can be updated
    
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
    
            // Store new image in `storage/app/public/blog_images`
            $imagePath = $request->file('image')->store('blog_images', 'public');
            $data['image'] = $imagePath; // Update image path
        }
    
        // Update the blog with new data
        $blog->update($data);
    
        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog
        ], 200);
    }

     // Soft - delete a blog
     public function destroy($id)
     {
         $blog = Blog::find($id);
         if (!$blog) {
             return response()->json(['message' => 'Blog not found'], 404);
         }
 
         $blog->delete();
 
         return response()->json(['message' => 'Blogsoft - deleted successfully'], 200);
     }


    //Restore the soft-deleted blog
    public function restore($id)
    {
        $blog = Blog::withTrashed()->find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->restore();

        return response()->json(['message' => 'Blog restored'], 200);
    }

    // Permanently delete a blog
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
