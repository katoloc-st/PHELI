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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá (VD: SAVE10, FREESHIP)
            $table->string('name'); // Tên voucher
            $table->text('description')->nullable(); // Mô tả voucher
            $table->enum('type', ['percent', 'fixed']); // Loại: phần trăm hoặc số tiền cố định
            $table->decimal('value', 15, 2); // Giá trị giảm (% hoặc VNĐ)
            $table->decimal('min_order_value', 15, 2)->default(0); // Giá trị đơn hàng tối thiểu
            $table->decimal('max_discount', 15, 2)->nullable(); // Giảm giá tối đa (cho loại %)
            $table->integer('usage_limit')->nullable(); // Số lần sử dụng tối đa (null = không giới hạn)
            $table->integer('usage_count')->default(0); // Số lần đã sử dụng
            $table->integer('per_user_limit')->default(1); // Số lần 1 user có thể dùng
            $table->enum('applies_to', ['all', 'seller', 'product'])->default('all'); // Áp dụng cho: tất cả/người bán/sản phẩm
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade'); // Nếu áp dụng cho seller cụ thể
            $table->json('product_ids')->nullable(); // Nếu áp dụng cho sản phẩm cụ thể
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->dateTime('starts_at')->nullable(); // Ngày bắt đầu
            $table->dateTime('expires_at')->nullable(); // Ngày hết hạn
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index(['starts_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
