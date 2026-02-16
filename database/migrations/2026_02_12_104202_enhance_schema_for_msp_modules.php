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
        // 1. Enhance Assets
        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_tag')->nullable()->after('name');
            $table->string('manufacturer')->nullable()->after('serial_number');
            $table->string('model')->nullable()->after('manufacturer');
            $table->date('end_of_life')->nullable()->after('warranty_expire_date');
            $table->string('assigned_to')->nullable()->after('status'); // Text for now, could be user FK later
            $table->string('ip_address')->nullable()->after('assigned_to');
            $table->string('mac_address')->nullable()->after('ip_address');
            $table->string('os_version')->nullable()->after('mac_address');
            $table->boolean('monitoring_enabled')->default(false)->after('os_version');
            $table->boolean('rmm_agent_installed')->default(false)->after('monitoring_enabled');
        });

        // 2. Enhance Sites (Locations)
        Schema::table('sites', function (Blueprint $table) {
            $table->string('site_manager')->nullable()->after('postcode');
            $table->string('internet_provider')->nullable()->after('site_manager');
            $table->string('circuit_id')->nullable()->after('internet_provider');
            $table->string('alarm_code')->nullable()->after('circuit_id');
            $table->text('after_hours_access')->nullable()->after('alarm_code');
            $table->string('timezone')->nullable()->after('after_hours_access');
        });

        // 3. Enhance Credentials
        Schema::table('credentials', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title'); // e.g. RDP, SSH, Web
            $table->text('encrypted_2fa_secret')->nullable()->after('encrypted_password');
            $table->date('expiry_date')->nullable()->after('url');
            $table->date('last_rotated_at')->nullable()->after('expiry_date');
            $table->boolean('auto_rotate')->default(false)->after('last_rotated_at');
        });

        // 4. Enhance Organizations (MSP Info)
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->after('name');
            $table->string('reg_number')->nullable()->after('legal_name');
            $table->string('tax_id')->nullable()->after('reg_number');
            $table->string('primary_email')->nullable()->after('tax_id');
            $table->string('website')->nullable()->after('primary_email');
            $table->string('phone')->nullable()->after('website');
        });

        // 5. Create Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_office')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['legal_name', 'reg_number', 'tax_id', 'primary_email', 'website', 'phone']);
        });

        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn(['category', 'encrypted_2fa_secret', 'expiry_date', 'last_rotated_at', 'auto_rotate']);
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['site_manager', 'internet_provider', 'circuit_id', 'alarm_code', 'after_hours_access', 'timezone']);
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'asset_tag', 
                'manufacturer', 
                'model', 
                'end_of_life', 
                'assigned_to', 
                'ip_address', 
                'mac_address', 
                'os_version', 
                'monitoring_enabled', 
                'rmm_agent_installed'
            ]);
        });
    }
};
