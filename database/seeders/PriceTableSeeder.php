<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PriceTable;
use App\Models\WasteType;
use Carbon\Carbon;

class PriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            'Sắt' => 15000,
            'Nhôm' => 45000,
            'Đồng' => 180000,
            'Inox' => 35000,
            'Kẽm' => 25000,
            'Giấy' => 3000,
            'Nhựa' => 8000
        ];

        foreach ($prices as $wasteTypeName => $price) {
            $wasteType = WasteType::where('name', $wasteTypeName)->first();
            if ($wasteType) {
                PriceTable::create([
                    'waste_type_id' => $wasteType->id,
                    'price' => $price,
                    'effective_date' => Carbon::now(),
                    'expired_date' => null,
                    'is_active' => true,
                    'notes' => 'Giá chuẩn ban đầu'
                ]);
            }
        }
    }
}
