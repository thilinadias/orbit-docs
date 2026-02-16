<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables to ensure clean slate for new RBAC system
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('object_permissions');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permission_role'); // cleanup potential legacy table
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();

        // Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('module'); // For grouping (e.g., 'documents', 'settings')
            $table->timestamps();
        });

        // Role_Permission Pivot
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });

        // Object Permissions (Overrides)
        Schema::create('object_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // 'document', 'credential'
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('permission_type'); // 'view', 'edit', 'reveal'
            $table->boolean('is_allowed')->default(true); // Allow or Deny
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('object_permissions');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
