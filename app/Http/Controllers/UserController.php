<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Get users
    public function index()
    {
        //return response()->json(User::all(), 200); only retrieves active users in postman
        //return response()->json(User::onlyTrashed()->get(), 200); only retrieves soft-deleted users in postman
        return response()->json(User::withTrashed()->get(), 200); //retrieves active and soft deleted users in postman
    }

    // Create a new user
    public function store(Request $request)
    {
        // Check if email already exists in the database
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'User with this email already exists.',
            ], 409);  // Conflict HTTP status
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Get a specific user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if email already exists (for update scenario)
        if ($request->has('email') && User::where('email', $request->email)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'message' => 'User with this email already exists.',
            ], 409);  // Conflict HTTP status
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);

        $user->save();

        return response()->json($user, 200);
    }

    // Soft delete a user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User soft deleted successfully'], 200);
    }

    //Restore the soft-deleted user
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->restore();

        return response()->json(['message' => 'User restored'], 200);
    }

    // Permanently delete a user
    public function forceDelete($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->forceDelete();

        return response()->json(['message' => 'User permanently deleted'], 200);
    }
}