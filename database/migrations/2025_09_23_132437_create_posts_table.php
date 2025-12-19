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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người đăng
            $table->foreignId('waste_type_id')->constrained()->onDelete('cascade'); // Loại rác
            $table->string('title'); // Tiêu đề bài đăng
            $table->text('description'); // Mô tả chi tiết
            $table->decimal('quantity', 10, 2); // Số lượng
            $table->decimal('price', 10, 2); // Giá đăng bán
            $table->string('location'); // Địa điểm
            $table->string('contact_phone'); // Số điện thoại liên hệ
            $table->enum('type', ['sell', 'buy']); // Loại bài đăng: bán hoặc mua
            $table->enum('status', ['active', 'sold', 'inactive'])->default('active'); // Trạng thái
            $table->json('images')->nullable(); // Hình ảnh (lưu dạng JSON array)
            $table->timestamp('expired_at')->nullable(); // Thời gian hết hạn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
