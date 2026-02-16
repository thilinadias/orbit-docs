<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Organization;
use App\Models\Asset;

$org = Organization::where('name', 'Aabyss')->first();

if (!$org) {
    echo "Organization Aabyss not found.\n";
    exit;
}

echo "Organization: {$org->name} (ID: {$org->id})\n";
echo "Status: {$org->status}\n";

// Check current status
$activeCount = Asset::where('organization_id', $org->id)->where('status', 'active')->count();
echo "Active Assets: {$activeCount}\n";

// Manually run the update logic we WANT to use
echo "Running direct update query...\n";
$updated = Asset::where('organization_id', $org->id)->update(['status' => 'suspended']);
echo "Updated {$updated} assets.\n";

// Verify
$activeCountAfter = Asset::where('organization_id', $org->id)->where('status', 'active')->count();
$suspendedCountAfter = Asset::where('organization_id', $org->id)->where('status', 'suspended')->count();

echo "Active Assets After: {$activeCountAfter}\n";
echo "Suspended Assets After: {$suspendedCountAfter}\n";
