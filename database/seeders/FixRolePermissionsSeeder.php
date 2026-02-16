<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class FixRolePermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure user.manage permission exists
        $perm = Permission::firstOrCreate(
            ['slug' => 'user.manage'],
            ['name' => 'User Manage', 'description' => 'Can manage users and roles.', 'module' => 'user']
        );

        // 2. Assign to Admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            if (!$adminRole->permissions->contains($perm->id)) {
                $adminRole->permissions()->attach($perm->id);
                $this->command->info('Attached user.manage to Admin role.');
            } else {
                $this->command->info('Admin role already has user.manage permission.');
            }
        }
    }
}
