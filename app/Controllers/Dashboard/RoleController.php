<?php

namespace App\Controllers\Dashboard;

use App\Models\Role;
use App\Models\Permission;
use App\Core\Http\Request;
use App\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        // Get pagination parameters
        $perPage = (int)($_GET['per_page'] ?? 10);
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';

        // Build query
        $query = Role::query();

        // Apply search if provided
        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        // Get paginated results
        $total = $query->count();
        $offset = ($page - 1) * $perPage;
        $roles = $query->limit($perPage)->offset($offset)->get();

        // Calculate pagination
        $totalPages = ceil($total / $perPage);

        return view('Dashboard.Role.index', [
            'roles' => $roles,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search
        ]);
    }

    /**
     * Show form for creating a new role
     */
    public function create()
    {
        // Get all available permissions
        $permissions = $this->getAvailablePermissions();

        return view('Dashboard.Role.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'permissions' => 'nullable|array'
        ]);

        try {
            // Create new role
            $role = new Role();
            $role->name = strtolower($validatedData['name']);
            $role->description = $validatedData['description'] ?? null;
            $role->status = $validatedData['status'] ?? 'active';
            $role->save();

            // Attach permissions if any
            if (!empty($validatedData['permissions'])) {
                $permissionIds = Permission::getIdsByNames($validatedData['permissions']);
                $role->permissions()->sync($permissionIds);
            }

            // Set success message
            session()->flash('success', 'Role created successfully.');

            return redirect()->route('dashboard.roles.index');

        } catch (\Exception $e) {
            // Set error message
            session()->flash('error', 'Failed to create role. Please try again.');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return redirect()->route('dashboard.roles.index');
        }

        // Get role's current permissions
        $rolePermissions = $role->permissions()->pluck('name');

        return view('Dashboard.Role.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show form for editing a role
     */
    public function edit($id)
    {
        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return redirect()->route('dashboard.roles.index');
        }

        // Get all available permissions
        $permissions = $this->getAvailablePermissions();

        // Get role's current permissions
        $rolePermissions = $role->permissions()->pluck('name');


        return view('Dashboard.Role.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return redirect()->route('dashboard.roles.index');
        }

        // Prevent editing admin role name
        if ($role->name === 'admin') {
            session()->flash('error', 'The admin role is protected and cannot be modified.');
            return redirect()->route('dashboard.roles.index');
        }

        // Validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'permissions' => 'nullable|array'
        ]);

        try {
            // Update role
            $role->name = strtolower($validatedData['name']);
            $role->description = $validatedData['description'] ?? null;
            $role->status = $validatedData['status'] ?? 'active';
            $role->save();

            // Sync permissions
            if (isset($validatedData['permissions'])) {
                $permissionIds = Permission::getIdsByNames($validatedData['permissions']);
                $role->permissions()->sync($permissionIds);
            } else {
                $role->permissions()->detach();
            }

            session()->flash('success', 'Role updated successfully.');

            return redirect()->route('dashboard.roles.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update role. Please try again.');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete the specified role
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return redirect()->route('dashboard.roles.index');
        }

        // Protect admin role from deletion
        if ($role->name === 'admin') {
            session()->flash('error', 'The admin role is protected and cannot be deleted.');
            return redirect()->route('dashboard.roles.index');
        }

        // Check if role has users
        $userCount = $role->users()->count();
        if ($userCount > 0) {
            session()->flash('error', "Cannot delete role '{$role->name}' because it has {$userCount} assigned user(s).");
            return redirect()->route('dashboard.roles.index');
        }

        try {
            // Delete role and detach permissions
            $role->permissions()->detach();
            $role->delete();

            session()->flash('success', 'Role deleted successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete role. Please try again.');
        }

        return redirect()->route('dashboard.roles.index');
    }

    /**
     * Get available permissions
     */
    private function getAvailablePermissions()
    {
        // Define available permissions
        return [
            'users' => [
                'name' => 'User Management',
                'icon' => 'fa-users',
                'permissions' => [
                    'view_users' => 'View Users',
                    'create_users' => 'Create Users',
                    'edit_users' => 'Edit Users',
                    'delete_users' => 'Delete Users'
                ]
            ],
            'roles' => [
                'name' => 'Role Management',
                'icon' => 'fa-user-tag',
                'permissions' => [
                    'view_roles' => 'View Roles',
                    'create_roles' => 'Create Roles',
                    'edit_roles' => 'Edit Roles',
                    'delete_roles' => 'Delete Roles'
                ]
            ],
            'content' => [
                'name' => 'Content Management',
                'icon' => 'fa-file-lines',
                'permissions' => [
                    'view_content' => 'View Content',
                    'create_content' => 'Create Content',
                    'edit_content' => 'Edit Content',
                    'delete_content' => 'Delete Content'
                ]
            ],
            'settings' => [
                'name' => 'System Settings',
                'icon' => 'fa-gear',
                'permissions' => [
                    'view_settings' => 'View Settings',
                    'edit_settings' => 'Edit Settings',
                    'system_config' => 'System Configuration'
                ]
            ]
        ];
    }

    /**
     * Update role status
     */
    public function updateStatus(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        if ($role->name === 'admin') {
            return response()->json(['error' => 'Admin role status cannot be changed'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $role->status = $validatedData['status'];
        $role->save();

        return response()->json(['success' => 'Role status updated successfully']);
    }
}
