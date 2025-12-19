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
        Schema::table('collection_points', function (Blueprint $table) {
            // Xóa các trường không cần thiết
            $table->dropColumn(['contact_person', 'contact_phone', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_points', function (Blueprint $table) {
            // Khôi phục lại các trường đã xóa
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }
};
