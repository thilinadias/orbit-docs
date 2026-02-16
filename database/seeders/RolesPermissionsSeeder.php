<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Permissions
        $permissions = [
            'organization' => ['view', 'manage'],
            'document' => ['view', 'create', 'edit', 'delete'],
            'credential' => ['view', 'reveal', 'create', 'edit', 'delete'],
            'asset' => ['view', 'create', 'edit', 'delete', 'manage'],
            'onboarding' => ['manage'],
            'audit' => ['view'],
            'settings' => ['manage'],
            'user' => ['view', 'manage'],
        ];

        DB::transaction(function () use ($permissions) {
            foreach ($permissions as $module => $actions) {
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'slug' => "$module.$action"
                    ], [
                        'name' => ucfirst($module) . ' ' . ucfirst($action),
                        'module' => $module
                    ]);
                }
            }

            // 2. Create Roles
            $roles = [
                'Super Admin' => 'super-admin',
                'Admin' => 'admin',
                'Technician' => 'technician',
                'Read Only' => 'read-only',
                'Billing' => 'billing',
                'API User' => 'api-user',
            ];

            foreach ($roles as $name => $slug) {
                Role::firstOrCreate(['slug' => $slug], ['name' => $name]);
            }

            // 3. Assign Permissions
            
            // Super Admin - All Permissions
            $allPermissions = Permission::all();
            $superAdmin = Role::where('slug', 'super-admin')->first();
            $superAdmin->permissions()->sync($allPermissions);

            // Admin - All except Audit and maybe restricted settings (but allowed for now)
            // Admins manage their own orgs.
            $adminPermissions = Permission::whereIn('module', ['organization', 'document', 'credential', 'asset', 'onboarding', 'user'])->get();
            $admin = Role::where('slug', 'admin')->first();
            $admin->permissions()->sync($adminPermissions);

            // Technician - View and Reveal only
            // Can view everything, reveal passwords, but cannot create/edit/delete.
            $techPermissions = Permission::where('slug', 'LIKE', '%.view')
                                         ->orWhere('slug', 'credential.reveal')
                                         ->get();
            $technician = Role::where('slug', 'technician')->first();
            $technician->permissions()->sync($techPermissions);

            // Read Only - View only, NO reveal
            $readOnlyPermissions = Permission::where('slug', 'LIKE', '%.view')->get();
            $readOnly = Role::where('slug', 'read-only')->first();
            $readOnly->permissions()->sync($readOnlyPermissions);
            
            // Billing (Placeholder)
            // $billing = Role::where('slug', 'billing')->first();
            // $billing->permissions()->sync([...]); 
            
            // API User
            // Usually specific, leaving empty for now or basic view.
            $apiUser = Role::where('slug', 'api-user')->first();
            $apiUser->permissions()->sync($readOnlyPermissions); // Default to read-only for safety
        });
    }
}
