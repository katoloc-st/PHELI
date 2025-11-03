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
            $table->enum('role', ['waste_company', 'scrap_dealer', 'recycling_plant'])->default('waste_company');
            // waste_company: doanh nghiệp xả rác
            // scrap_dealer: phế liệu
            // recycling_plant: nhà máy tái chế
            $table->string('company_name')->nullable(); // Tên công ty
            $table->string('phone')->nullable(); // Số điện thoại
            $table->text('address')->nullable(); // Địa chỉ
            $table->text('description')->nullable(); // Mô tả công ty
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'company_name', 'phone', 'address', 'description']);
        });
    }
};
