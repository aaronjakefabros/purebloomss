<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself (admin)
        if ($user->email === 'admin@pureblooms.com') {
            return back()->with('error', 'Cannot delete the main admin account!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}