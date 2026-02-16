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
        DB::statement("ALTER TABLE activity_logs CHANGE COLUMN entity_type subject_type VARCHAR(255) NULL");
        DB::statement("ALTER TABLE activity_logs CHANGE COLUMN entity_id subject_id BIGINT UNSIGNED NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->renameColumn('subject_type', 'entity_type');
            $table->renameColumn('subject_id', 'entity_id');
        });
    }
};
