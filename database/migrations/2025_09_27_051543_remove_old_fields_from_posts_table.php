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
        Schema::table('posts', function (Blueprint $table) {
            // Xóa các trường địa chỉ cũ vì đã chuyển sang collection_points
            $table->dropColumn([
                'detailed_address',
                'province', 
                'district',
                'ward',
                'postal_code',
                'address_note'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Khôi phục lại các trường đã xóa
            $table->string('detailed_address')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address_note')->nullable();
        });
    }
};
