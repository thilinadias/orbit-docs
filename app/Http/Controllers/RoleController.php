<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display the Role-Permission Matrix.
     */
    public function index()
    {
        if (!Gate::allows('user.manage') && !auth()->user()->is_super_admin) {
             abort(403);
        }

        $roles = Role::where('slug', '!=', 'super-admin')->get(); // Hide Super Admin as it has all permissions
        $permissions = Permission::all()->groupBy('module');

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Update the specified role's permissions.
     */
    public function update(Request $request, Role $role)
    {
        if (!Gate::allows('user.manage') && !auth()->user()->is_super_admin) {
             abort(403);
        }
        
        // Prevent editing Super Admin role
        if ($role->slug === 'super-admin') {
             return back()->with('error', 'Cannot edit Super Admin role.');
        }

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return back()->with('success', 'Role permissions updated successfully.');
    }
}
