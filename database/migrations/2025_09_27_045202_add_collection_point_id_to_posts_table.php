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
            $table->foreignId('collection_point_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->index(['collection_point_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['collection_point_id']);
            $table->dropIndex(['collection_point_id', 'status']);
            $table->dropColumn('collection_point_id');
        });
    }
};
