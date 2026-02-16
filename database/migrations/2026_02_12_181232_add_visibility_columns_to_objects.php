<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'user_id')) {
                 $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('documents', 'visibility')) {
                $table->string('visibility')->default('org')->after('updated_at'); // private, org, restricted
            }
        });

        Schema::table('credentials', function (Blueprint $table) {
             if (!Schema::hasColumn('credentials', 'user_id')) {
                 $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
            }
             if (!Schema::hasColumn('credentials', 'visibility')) {
                $table->string('visibility')->default('org')->after('updated_at'); // org, restricted
            }
        });
    }

    public function down(): void
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};
