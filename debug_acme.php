<?php

use App\Models\Organization;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = Organization::where('name', 'like', '%ACME%')->with('parent')->first();

if (!$org) {
    echo "Organization 'ACME' not found.\n";
    exit;
}

echo "Organization: {$org->name} (ID: {$org->id})\n";
if ($org->parent) {
    echo "Parent: {$org->parent->name} (ID: {$org->parent->id})\n";
} else {
    echo "No Parent Organization found.\n";
}
