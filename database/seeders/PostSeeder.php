<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        $wasteType = \App\Models\WasteType::first();
        $collectionPoint = \App\Models\CollectionPoint::first();

        if ($user && $wasteType && $collectionPoint) {
            // Tạo bài đăng của doanh nghiệp xanh
            \App\Models\Post::create([
                'user_id' => $user->id,
                'collection_point_id' => $collectionPoint->id,
                'waste_type_id' => $wasteType->id,
                'title' => 'Bán phế liệu nhựa PET sạch',
                'description' => 'Phế liệu nhựa PET đã được phân loại và làm sạch, phù hợp cho tái chế.',
                'quantity' => 500.00,
                'price' => 8000.00,
                'type' => 'doanh_nghiep_xanh',
                'status' => 'active',
                'images' => null
            ]);

            // Tạo bài đăng của cơ sở phế liệu
            \App\Models\Post::create([
                'user_id' => $user->id,
                'collection_point_id' => $collectionPoint->id,
                'waste_type_id' => $wasteType->id,
                'title' => 'Thu mua phế liệu giấy các loại',
                'description' => 'Chúng tôi thu mua phế liệu giấy với giá cao, giao hàng tận nơi.',
                'quantity' => 1000.00,
                'price' => 12000.00,
                'type' => 'co_so_phe_lieu',
                'status' => 'active',
                'images' => null
            ]);
        }
    }
}
