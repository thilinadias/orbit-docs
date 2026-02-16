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
        Schema::table('users', function (Blueprint $table) {
            $table->text('google2fa_secret')->nullable()->after('password');
            $table->boolean('is_2fa_enforced')->default(false)->after('google2fa_secret');
            $table->text('two_factor_recovery_codes')->nullable()->after('is_2fa_enforced');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google2fa_secret',
                'is_2fa_enforced',
                'two_factor_recovery_codes',
            ]);
        });
    }
};
