<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Review::create([
            'post_id' => 1,
            'user_id' => 2,
            'rating' => 5,
            'content' => 'Chất lượng phế liệu rất tốt, đúng như mô tả. Người bán thân thiện và giao hàng đúng hẹn.',
            'status' => 'approved'
        ]);

        \App\Models\Review::create([
            'post_id' => 1,
            'user_id' => 3,
            'rating' => 4,
            'content' => 'Sản phẩm ổn, giá cả hợp lý. Sẽ mua lại nếu có nhu cầu.',
            'status' => 'approved'
        ]);
    }
}
