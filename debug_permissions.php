<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('name', 'Admin User')->first();

if (!$user) {
    echo "User 'Admin User' not found.\n";
    $user = User::first();
    echo "Using first user: {$user->name} ({$user->email})\n";
} else {
    echo "Found User: {$user->name} ({$user->email})\n";
}

echo "Is Super Admin: " . ($user->is_super_admin ? 'Yes' : 'No') . "\n";

echo "Has 'user.manage' Permission (Global/Null Context):\n";
$hasPerm = $user->hasPermission('user.manage', null);
echo "Result: " . ($hasPerm ? 'YES' : 'NO') . "\n";

echo "Checking specific organizations:\n";
foreach ($user->organizations as $org) {
    echo " - Org: {$org->name} (ID: {$org->id})\n";
    $pivot = $org->pivot;
    echo "   - Role ID: " . ($pivot->role_id ?? 'NULL') . "\n";
    if ($pivot->role_id) {
        $role = Role::find($pivot->role_id);
        echo "   - Role Name: {$role->name}\n";
        $roleHasPerm = $role->permissions()->where('slug', 'user.manage')->exists();
        echo "   - Role has 'user.manage': " . ($roleHasPerm ? 'YES' : 'NO') . "\n";
    }
}

echo "\nGate Check 'user.manage': " . ($user->can('user.manage') ? 'ALLOWED' : 'DENIED') . "\n";
