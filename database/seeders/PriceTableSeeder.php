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
            'Thiếc' => 320000,      // Giá thiếc phế liệu ~320k/kg
            'Hợp kim' => 150000,    // Giá hợp kim trung bình ~150k/kg
            'Chì' => 45000,         // Giá chì phế liệu ~40-50k/kg
            'Niken' => 280000,      // Giá niken phế liệu ~280k/kg
            'Sắt' => 15000,         // Giá sắt phế liệu ~15k/kg
            'Nhôm' => 45000,        // Giá nhôm phế liệu ~45k/kg
            'Đồng' => 180000,       // Giá đồng phế liệu ~180k/kg
            'Inox' => 35000,        // Giá inox phế liệu ~35k/kg
            'Kẽm' => 25000,         // Giá kẽm phế liệu ~25k/kg
            'Cao su' => 12000,      // Giá cao su phế liệu ~10-15k/kg
            'Giấy' => 3000,         // Giá giấy phế liệu ~3k/kg
            'Nhựa' => 8000          // Giá nhựa phế liệu ~8k/kg
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
