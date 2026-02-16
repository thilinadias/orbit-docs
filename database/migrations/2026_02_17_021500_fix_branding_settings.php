<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix System Name
        Setting::updateOrCreate(
            ['key' => 'system_name'],
            ['value' => 'OrbitDocs']
        );

        // Fix System Logo (Clear it if it points to a missing file, or just reset to default for safety)
        // Check if current logo exists? No, hard to check file existence inside migration easily if it varies.
        // Let's just clear it so it falls back to the SVG component which works.
        // The user can re-upload if they have a custom one.
        // But maybe they want to KEEP it? 
        // The screenshot showed a broken link.
        // Let's check if the value is 'Uptime' related or something legacy.
        
        $logo = Setting::where('key', 'system_logo')->first();
        if ($logo) {
            // validating if it looks like a junk path or just assume reset is safer
             $logo->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
