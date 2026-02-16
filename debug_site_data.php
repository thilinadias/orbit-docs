<?php

use App\Models\Organization;
use App\Models\Site;
use App\Models\Asset;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = Organization::where('slug', 'wpe')->first();

if (!$org) {
    echo "Organization 'wpe' not found.\n";
    exit;
}

echo "Organization: {$org->name} (ID: {$org->id})\n";
echo "Sites Count: " . $org->sites()->count() . "\n";
foreach ($org->sites as $site) {
    echo "- Site: {$site->name} (ID: {$site->id})\n";
}

echo "Assets Count: " . $org->assets()->count() . "\n";
foreach ($org->assets as $asset) {
    echo "- Asset: {$asset->name} (ID: {$asset->id}) -> Site ID: " . ($asset->site_id ?? 'NULL') . "\n";
}
