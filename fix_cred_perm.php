<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Adding Credential Edit Permissions...\n";

// Ensure 'credential.edit' exists
Permission::firstOrCreate([
    'slug' => 'credential.edit'
], [
    'name' => 'Credential Edit',
    'module' => 'credential'
]);

// Give to Admin
$admin = Role::where('slug', 'admin')->first();
$editPerm = Permission::where('slug', 'credential.edit')->first();

if ($admin && $editPerm) {
    if (!$admin->permissions->contains($editPerm->id)) {
        $admin->permissions()->attach($editPerm->id);
        echo "Added credential.edit to Admin.\n";
    } else {
        echo "Admin already has credential.edit.\n";
    }
}

// Ensure Super Admin has it (synced all)
$superAdmin = Role::where('slug', 'super-admin')->first();
if ($superAdmin) {
    $superAdmin->permissions()->sync(Permission::all()); // Re-sync all
    echo "Super Admin synced.\n";
}

echo "Done.\n";
