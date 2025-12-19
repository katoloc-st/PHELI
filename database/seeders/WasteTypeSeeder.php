<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WasteType;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wasteTypes = [
            [
                'name' => 'Thiếc',
                'unit' => 'kg',
                'description' => 'Thiếc phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Hợp kim',
                'unit' => 'kg',
                'description' => 'Hợp kim phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Sắt',
                'unit' => 'kg',
                'description' => 'Sắt thép phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Nhôm',
                'unit' => 'kg',
                'description' => 'Nhôm phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Đồng',
                'unit' => 'kg',
                'description' => 'Đồng phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Inox',
                'unit' => 'kg',
                'description' => 'Inox phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Kẽm',
                'unit' => 'kg',
                'description' => 'Kẽm phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Niken',
                'unit' => 'kg',
                'description' => 'Niken phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Chì',
                'unit' => 'kg',
                'description' => 'Chì phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Cao su',
                'unit' => 'kg',
                'description' => 'Cao su phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Giấy',
                'unit' => 'kg',
                'description' => 'Giấy phế liệu các loại',
                'is_active' => true
            ],
            [
                'name' => 'Nhựa',
                'unit' => 'kg',
                'description' => 'Nhựa phế liệu các loại',
                'is_active' => true
            ]
        ];

        foreach ($wasteTypes as $wasteType) {
            WasteType::create($wasteType);
        }
    }
}
