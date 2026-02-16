<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AssetType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $roles = ['Super Admin', 'Admin', 'Technician', 'Read-Only'];
        foreach ($roles as $role) {
            Role::create(['name' => $role, 'label' => $role]);
        }

        $this->call([
            ITGlueAssetSeeder::class,
            ITGlueDemoDataSeeder::class,
        ]);

        // Default Admin User
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@orbitdocs.com',
            'password' => Hash::make('password'),
        ]);

        // Default Organization
        $org = Organization::create([
            'name' => 'Demo MSP',
            'slug' => 'demo-msp',
        ]);

        // Assign User to Org with Admin Role
        $adminRole = Role::where('name', 'Admin')->first();
        $org->users()->attach($user->id, ['role_id' => $adminRole->id]);

        // Asset Types
        $types = ['Server', 'Workstation', 'Firewall', 'Switch', 'License', 'Domain'];
        foreach ($types as $type) {
            AssetType::create(['name' => $type]);
        }
    }
}
