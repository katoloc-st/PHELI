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
        Schema::create('collection_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Tên điểm tập kết
            $table->string('detailed_address'); // Địa chỉ cụ thể
            $table->string('province'); // Tỉnh/Thành phố
            $table->string('district'); // Quận/Huyện
            $table->string('ward'); // Phường/Xã
            $table->string('postal_code')->nullable(); // Mã bưu điện
            $table->text('address_note')->nullable(); // Ghi chú địa chỉ
            $table->string('contact_person')->nullable(); // Người liên hệ tại điểm này
            $table->string('contact_phone')->nullable(); // SĐT liên hệ tại điểm này
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Index để tìm kiếm nhanh
            $table->index(['user_id', 'status']);
            $table->index(['province', 'district', 'ward']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_points');
    }
};
