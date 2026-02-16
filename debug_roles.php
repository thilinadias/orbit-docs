<?php

use App\Models\Role;
use App\Models\Permission;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Role Permissions...\n\n";

$roles = Role::with('permissions')->get();

foreach ($roles as $role) {
    echo "Role: [{$role->name}] (Slug: {$role->slug})\n";
    if ($role->permissions->isEmpty()) {
        echo "  - No permissions assigned.\n";
    } else {
        foreach ($role->permissions as $perm) {
            echo "  - {$perm->slug} ({$perm->description})\n";
        }
    }
    echo "\n";
}
