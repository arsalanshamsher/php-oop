<?php

namespace App\Controllers\Dashboard;

use App\Models\User;
use App\Models\Role;
use App\Core\Http\Request;
use App\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('Dashboard.User.index', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User::with('role')->find($id);
        if (!$user) {
            return redirect()->route('dashboard.users.index')->with('error', 'User not found.');
        }
        return view('Dashboard.User.show', ['user' => $user]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('Dashboard.User.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = password_hash($validatedData['password'], PASSWORD_DEFAULT);
        $user->role_id = $validatedData['role_id'];
        $user->save();

        return redirect()->route('dashboard.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('Dashboard.User.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);

        $user = User::find($id);
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        if ($request->input('password')) {
            $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }
        
        $user->save();

        return redirect()->route('dashboard.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('dashboard.users.index')->with('success', 'User deleted successfully.');
        }
        return redirect()->route('dashboard.users.index')->with('error', 'User not found.');
    }
}
