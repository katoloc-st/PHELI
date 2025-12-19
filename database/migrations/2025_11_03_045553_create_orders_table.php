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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            
            // Customer information
            $table->string('email');
            $table->string('phone');
            $table->string('full_name');
            
            // Shipping address
            $table->string('address');
            $table->string('apartment')->nullable();
            $table->string('ward');
            $table->string('district');
            $table->string('province');
            $table->text('full_address');
            
            // Order totals
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_total', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            
            // Platform discount
            $table->string('platform_discount_type')->nullable(); // percent, fixed, none
            $table->decimal('platform_discount_value', 15, 2)->nullable();
            
            // Order status
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
