<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CollectionPoint;

class UpdateCollectionPointsCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả collection points không có tọa độ
        $collectionPoints = CollectionPoint::whereNull('latitude')
            ->orWhereNull('longitude')
            ->get();

        // Tọa độ mẫu cho các tỉnh/thành phố ở Việt Nam
        $provinceCoordinates = [
            'Hà Nội' => ['lat' => 21.0285, 'lng' => 105.8542],
            'TP. Hồ Chí Minh' => ['lat' => 10.8231, 'lng' => 106.6297],
            'Đà Nẵng' => ['lat' => 16.0544, 'lng' => 108.2022],
            'Hải Phòng' => ['lat' => 20.8449, 'lng' => 106.6881],
            'Cần Thơ' => ['lat' => 10.0452, 'lng' => 105.7469],
            'Bình Dương' => ['lat' => 11.3254, 'lng' => 106.4770],
            'Đồng Nai' => ['lat' => 10.9468, 'lng' => 106.8383],
        ];

        foreach ($collectionPoints as $point) {
            $province = $point->province;
            
            // Tìm tọa độ cho tỉnh
            $coords = $provinceCoordinates[$province] ?? null;
            
            if ($coords) {
                // Thêm một chút random để các điểm không trùng nhau
                $latOffset = (rand(-1000, 1000) / 10000); // ±0.1 độ
                $lngOffset = (rand(-1000, 1000) / 10000); // ±0.1 độ
                
                $point->latitude = $coords['lat'] + $latOffset;
                $point->longitude = $coords['lng'] + $lngOffset;
                $point->save();
                
                $this->command->info("Updated coordinates for: {$point->name}");
            } else {
                // Nếu không tìm thấy tọa độ, dùng tọa độ mặc định (Hà Nội)
                $point->latitude = 21.0285 + (rand(-1000, 1000) / 10000);
                $point->longitude = 105.8542 + (rand(-1000, 1000) / 10000);
                $point->save();
                
                $this->command->warn("Used default coordinates for: {$point->name} (Province: {$province})");
            }
        }

        $this->command->info('All collection points have been updated with coordinates!');
    }
}
