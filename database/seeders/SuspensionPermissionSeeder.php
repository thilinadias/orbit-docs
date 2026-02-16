<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class SuspensionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the permission
        $perm = Permission::firstOrCreate(
            ['slug' => 'suspend-organization'],
            ['name' => 'Suspend Organization', 'description' => 'Can suspend and activate organizations and sites.', 'module' => 'organization']
        );

        // 2. Assign to Admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            if (!$adminRole->permissions->contains($perm->id)) {
                $adminRole->permissions()->attach($perm->id);
                $this->command->info('Attached suspend-organization to Admin role.');
            } else {
                $this->command->info('Admin role already has suspend-organization permission.');
            }
        }
    }
}
