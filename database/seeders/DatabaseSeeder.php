<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WasteTypeSeeder::class,
            PriceTableSeeder::class,
        ]);

        // Tạo user mẫu cho từng role
        User::firstOrCreate(
            ['email' => 'waste@abc.com'],
            [
                'name' => 'Công ty ABC',
                'password' => bcrypt('password'),
                'role' => 'waste_company',
                'company_name' => 'Công ty ABC',
                'phone' => '0123456789',
                'address' => 'Hà Nội',
                'description' => 'Công ty xả rác'
            ]
        );

        User::firstOrCreate(
            ['email' => 'scrap@xyz.com'],
            [
                'name' => 'Phế liệu XYZ',
                'password' => bcrypt('password'),
                'role' => 'scrap_dealer',
                'company_name' => 'Phế liệu XYZ',
                'phone' => '0987654321',
                'address' => 'TP.HCM',
                'description' => 'Thu mua phế liệu'
            ]
        );

        User::firstOrCreate(
            ['email' => 'recycle@def.com'],
            [
                'name' => 'Nhà máy tái chế DEF',
                'password' => bcrypt('password'),
                'role' => 'recycling_plant',
                'company_name' => 'Nhà máy tái chế DEF',
                'phone' => '0111222333',
                'address' => 'Đà Nẵng',
                'description' => 'Nhà máy tái chế'
            ]
        );

        User::firstOrCreate(
            ['email' => 'delivery@staff.com'],
            [
                'name' => 'Nguyễn Giao',
                'password' => bcrypt('password'),
                'role' => 'delivery_staff',
                'company_name' => 'Nhân viên giao hàng',
                'phone' => '0999888777',
                'address' => 'Hà Nội',
                'description' => 'Nhân viên giao hàng'
            ]
        );
    }
}
