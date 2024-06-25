<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $users = User::get();
            $roles = Role::get();
            return response()->json([
                'users' => $users,
                'roles' => $roles
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('get users error');
        }
    }

    public function createRole(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if the user already has a role
            if ($user->role !== null) {
                return response()->json(['error' => 'User already has a role'], 400);
            }

            // Create a new role for the user
            $user->role = $request->role;
            $user->save();

            // Assign the role to the user


            return response()->json(['message' => 'Role created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Role creation failed'], 500);
        }
    }


    // update role
    public function updateRole(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if the user already has a role
            if ($user->role === null) {
                return response()->json(['error' => 'User does not have a role'], 404);
            }

            // Find the role to update
            $user->update([
                'role' => $request->role
            ]);


            return response()->json(['message' => 'Role updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Role update failed'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return response()->json([
                'message' => 'User Created Successfully.',
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
