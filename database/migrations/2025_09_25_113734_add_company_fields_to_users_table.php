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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'company_address')) {
                $table->string('company_address')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('users', 'company_phone')) {
                $table->string('company_phone')->nullable()->after('company_address');
            }
            if (!Schema::hasColumn('users', 'company_email')) {
                $table->string('company_email')->nullable()->after('company_phone');
            }
            if (!Schema::hasColumn('users', 'business_license')) {
                $table->string('business_license')->nullable()->after('company_email');
            }
            if (!Schema::hasColumn('users', 'tax_code')) {
                $table->string('tax_code')->nullable()->after('business_license');
            }
            if (!Schema::hasColumn('users', 'company_description')) {
                $table->text('company_description')->nullable()->after('tax_code');
            }
            if (!Schema::hasColumn('users', 'company_logo')) {
                $table->string('company_logo')->nullable()->after('company_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_address',
                'company_phone',
                'company_email',
                'business_license',
                'tax_code',
                'company_description',
                'company_logo'
            ]);
        });
    }
};
