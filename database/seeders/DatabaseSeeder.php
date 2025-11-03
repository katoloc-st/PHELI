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
        User::create([
            'name' => 'Công ty ABC',
            'email' => 'waste@abc.com',
            'password' => bcrypt('password'),
            'role' => 'waste_company',
            'company_name' => 'Công ty ABC',
            'phone' => '0123456789',
            'address' => 'Hà Nội',
            'description' => 'Công ty xả rác'
        ]);

        User::create([
            'name' => 'Phế liệu XYZ',
            'email' => 'scrap@xyz.com',
            'password' => bcrypt('password'),
            'role' => 'scrap_dealer',
            'company_name' => 'Phế liệu XYZ',
            'phone' => '0987654321',
            'address' => 'TP.HCM',
            'description' => 'Thu mua phế liệu'
        ]);

        User::create([
            'name' => 'Nhà máy tái chế DEF',
            'email' => 'recycle@def.com',
            'password' => bcrypt('password'),
            'role' => 'recycling_plant',
            'company_name' => 'Nhà máy tái chế DEF',
            'phone' => '0111222333',
            'address' => 'Đà Nẵng',
            'description' => 'Nhà máy tái chế'
        ]);
    }
}
