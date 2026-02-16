<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force the first user to be a super admin to resolve installation issues
        if (Schema::hasTable('users')) {
            DB::table('users')
                ->where('id', 1)
                ->update(['is_super_admin' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as we don't want to accidentally revoke admins
    }
};
