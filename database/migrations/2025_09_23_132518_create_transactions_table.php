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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Bài đăng liên quan
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Người mua
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade'); // Người bán
            $table->decimal('quantity', 10, 2); // Số lượng giao dịch
            $table->decimal('price', 10, 2); // Giá giao dịch
            $table->decimal('total_amount', 10, 2); // Tổng tiền
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Trạng thái
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamp('completed_at')->nullable(); // Thời gian hoàn thành
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
