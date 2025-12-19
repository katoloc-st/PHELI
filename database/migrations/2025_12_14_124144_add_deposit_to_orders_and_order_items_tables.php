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
        // Add deposit to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('deposit_amount', 15, 2)->default(0)->after('grand_total');
        });

        // Add deposit to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('deposit_amount', 15, 2)->default(0)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('deposit_amount');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('deposit_amount');
        });
    }
};
