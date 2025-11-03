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
        Schema::create('price_table', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_type_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2); // Giá chuẩn
            $table->date('effective_date'); // Ngày có hiệu lực
            $table->date('expired_date')->nullable(); // Ngày hết hiệu lực
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_table');
    }
};
