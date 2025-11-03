<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thay đổi enum type trước
        DB::statement("ALTER TABLE posts MODIFY COLUMN type VARCHAR(50) NOT NULL");

        // Cập nhật dữ liệu hiện tại
        DB::table('posts')->where('type', 'sell')->update(['type' => 'doanh_nghiep_xanh']);
        DB::table('posts')->where('type', 'buy')->update(['type' => 'co_so_phe_lieu']);

        // Thay đổi về enum với giá trị mới
        DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('doanh_nghiep_xanh', 'co_so_phe_lieu') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại enum cũ
        DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('sell', 'buy') NOT NULL");

        // Khôi phục dữ liệu
        DB::table('posts')->where('type', 'doanh_nghiep_xanh')->update(['type' => 'sell']);
        DB::table('posts')->where('type', 'co_so_phe_lieu')->update(['type' => 'buy']);
    }
};
