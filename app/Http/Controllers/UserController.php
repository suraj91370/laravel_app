<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function edit(User $user)
    {
        if (auth()->user()->isManager() && $user->role->name !== 'User') {
            abort(403, 'Unauthorized action. Managers can only edit Users.');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->isManager() && $user->role->name !== 'User') {
            abort(403, 'Unauthorized action. Managers can only edit Users.');
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required',
            'isactive' => 'sometimes|boolean'
        ]);

        $isactive = $request->has('isactive') ? true : false;

        $user->update($request->all());
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Find user by ID
        $user = User::findOrFail($id);

        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return response()->json([
                'error' => 'You cannot delete your own account!'
            ], 403);
        }

        try {
            $user->delete();
            return response()->json(['success' => 'User deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getUsers(Request $request)
    {
        $users = User::with('role')->select('users.*');

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                $editBtn = '';
                $deleteBtn = '';

                $authUser = auth()->user();

                // Admin can edit/delete all users
                if ($authUser->isAdmin()) {
                    // Only show delete for other users
                    if ($authUser->id !== $user->id) {
                        $deleteBtn = '<button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">Delete</button>';
                    }

                    // Edit button for all
                    $editBtn = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                }
                // Manager can only edit Users (not other Managers or Admins)
                elseif ($authUser->isManager()) {
                    if ($user->role && $user->role->name === 'User') {
                        $editBtn = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                    }
                }

                return $editBtn . ' ' . $deleteBtn;
            })
            ->editColumn('isactive', function ($user) {
                return $user->isactive
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-danger">No</span>';
            })
            ->rawColumns(['isactive', 'action'])
            ->make(true);
    }
}
