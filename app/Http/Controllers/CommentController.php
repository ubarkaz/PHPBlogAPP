<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Get all comments
    public function index()
    {
        return response()->json(Comment::with(['user', 'commentable'])->get(), 200);
    }

    // Create a new comment (for a user or blog)
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:App\Models\Blog,App\Models\User',
        ]);

    // Check if the commentable_id exists for the given commentable_type
        $commentableModel = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');
        if (!$commentableModel::find($commentableId)) {
        return response()->json(['message' => 'Commentable resource not found'], 404);
    }

        $comment = Comment::create($request->all());

        return response()->json($comment, 201);
    }

    // Get a specific comment
    public function show($id)
    {
        $comment = Comment::with(['user', 'commentable'])->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment, 200);
    }

    // Update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $request->validate([
            'content' => 'sometimes|string',
        ]);

        $comment->update($request->all());

        return response()->json($comment, 200);
    }

    // Delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
