<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL for compatibility with older MariaDB versions
        DB::statement('ALTER TABLE assets CHANGE warranty_expire_date warranty_expiration_date DATE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE assets CHANGE warranty_expiration_date warranty_expire_date DATE');
    }
};
