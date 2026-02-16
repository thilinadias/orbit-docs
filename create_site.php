<?php

use App\Models\Organization;
use App\Models\Site;
use App\Models\Asset;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = Organization::where('slug', 'wpe')->first();
if (!$org) exit("Org not found\n");

// Create site
$site = Site::firstOrCreate(
    ['organization_id' => $org->id, 'name' => 'Main Office'],
    [
        'slug' => 'main-office',
        'address' => '123 Tech Park',
        'city' => 'Business City',
        'state' => 'NY',
        'postcode' => '10001',
        'country' => 'USA',
        'timezone' => 'UTC',
        'is_active' => true,
    ]
);

echo "Created Site: {$site->name} (ID: {$site->id})\n";

// Assign orphaned assets
$assets = $org->assets()->whereNull('site_id')->get();
foreach ($assets as $asset) {
    $asset->site_id = $site->id;
    $asset->save();
    echo "Assigned Asset: {$asset->name} to Site: {$site->name}\n";
}
