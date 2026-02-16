<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('credential_access_logs');
        Schema::dropIfExists('activity_logs');
        Schema::enableForeignKeyConstraints();

        // Global Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('entity_type')->nullable(); // e.g., 'App\Models\Document'
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action'); // 'create', 'update', 'delete', 'view'
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });

        // Sensitive Credential Access Logs
        Schema::create('credential_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credential_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // 'view', 'reveal', 'copy'
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credential_access_logs');
        Schema::dropIfExists('activity_logs');
    }
};
