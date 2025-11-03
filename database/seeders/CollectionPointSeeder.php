<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CollectionPoint;
use App\Models\User;

class CollectionPointSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Lấy user đầu tiên để tạo collection points
        $user = User::first();

        if ($user) {
            CollectionPoint::create([
                'user_id' => $user->id,
                'name' => 'Điểm tập kết Quận 1',
                'detailed_address' => '123 Nguyễn Huệ',
                'province' => 'TP.HCM',
                'district' => 'Quận 1',
                'ward' => 'Phường Nguyễn Thái Bình',
                'postal_code' => '70000',
                'address_note' => 'Cổng chính, bên trái'
            ]);

            CollectionPoint::create([
                'user_id' => $user->id,
                'name' => 'Điểm tập kết Bình Thạnh',
                'detailed_address' => '456 Điện Biên Phủ',
                'province' => 'TP.HCM',
                'district' => 'Quận Bình Thạnh',
                'ward' => 'Phường 25',
                'postal_code' => '70000',
                'address_note' => 'Kho B, tầng 2'
            ]);
        }
    }
}
