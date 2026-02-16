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

// Check total assets in DB for this org ID
$startAssets = Asset::where('organization_id', $org->id)->count();
echo "Total Assets where organization_id = {$org->id}: {$startAssets}\n";

// Check Direct Assets via relation
$directAssetsCount = $org->assets()->count();
echo "Direct Assets Count (via relation): {$directAssetsCount}\n";

// Check Assets via Site relation
$siteAssetsCount = Asset::whereIn('site_id', $org->sites()->select('id'))->count();
echo "Site Assets Count: {$siteAssetsCount}\n";

// Sample a few assets
$assets = Asset::where('organization_id', $org->id)->take(3)->get();
foreach ($assets as $asset) {
    echo "Asset: {$asset->name} - Status: {$asset->status} - Site ID: " . ($asset->site_id ?? 'NULL') . "\n";
}

// Check count of ACTIVE assets that SHOULD be suspended
$activeAssets = Asset::where('organization_id', $org->id)->where('status', '!=', 'suspended')->count();
echo "Active Assets (Should be 0 if suspended): {$activeAssets}\n";

// Force suspend via script to see if it works
if ($activeAssets > 0) {
    echo "Attempting force suspend via model method...\n";
    $org->suspend();
    $activeAssetsAfter = Asset::where('organization_id', $org->id)->where('status', '!=', 'suspended')->count();
    echo "Active Assets After Suspend: {$activeAssetsAfter}\n";
}
