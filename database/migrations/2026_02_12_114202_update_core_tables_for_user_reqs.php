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
        // 1. Contacts
        Schema::table('contacts', function (Blueprint $table) {
            // 'department' already exists
            $table->string('extension')->nullable();
            $table->string('location')->nullable();
            $table->boolean('mfa_enforced')->default(false);
            $table->string('access_level')->nullable();
            $table->string('linked_user_account')->nullable();
            $table->boolean('emergency_contact_flag')->default(false);
        });

        // 2. Documents
        Schema::table('documents', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->text('tags')->nullable();
            $table->integer('version')->default(1);
            $table->string('author')->nullable();
            $table->string('last_edited_by')->nullable();
            $table->string('visibility_scope')->default('Global');
            $table->string('approval_status')->default('Draft');
            $table->date('review_due_date')->nullable();
        });

        // 3. Sites (Locations)
        Schema::table('sites', function (Blueprint $table) {
            $table->string('address_2')->nullable();
            $table->string('rack_location')->nullable();
            $table->string('country')->nullable();
        });

        // 4. Credentials (Passwords)
        Schema::table('credentials', function (Blueprint $table) {
            $table->string('owner')->nullable();
            $table->string('access_scope')->nullable();
            $table->boolean('access_log_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['department', 'extension', 'location', 'mfa_enforced', 'access_level', 'linked_user_account', 'emergency_contact_flag']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['category', 'tags', 'version', 'author', 'last_edited_by', 'visibility_scope', 'approval_status', 'review_due_date']);
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['address_2', 'rack_location', 'country']);
        });

        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn(['owner', 'access_scope', 'access_log_enabled']);
        });
    }
};
