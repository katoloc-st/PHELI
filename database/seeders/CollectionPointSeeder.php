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
        // Tọa độ chính xác cho các tỉnh/thành phố và quận/huyện của Việt Nam
        $locationCoordinates = [
            'TP Hồ Chí Minh' => [
                'center' => ['lat' => 10.8231, 'lng' => 106.6297],
                'districts' => [
                    'Quận 1' => ['lat' => 10.7756, 'lng' => 106.7019],
                    'Quận 3' => ['lat' => 10.7825, 'lng' => 106.6909],
                    'Quận 10' => ['lat' => 10.7731, 'lng' => 106.6675],
                    'Quận 12' => ['lat' => 10.8627, 'lng' => 106.6518],
                    'Quận Bình Thạnh' => ['lat' => 10.8142, 'lng' => 106.7054],
                    'Quận Tân Bình' => ['lat' => 10.7992, 'lng' => 106.6528],
                    'Quận Bình Tân' => ['lat' => 10.7406, 'lng' => 106.6127],
                    'Q. Bình Tân' => ['lat' => 10.7406, 'lng' => 106.6127],
                    'Huyện Bình Chánh' => ['lat' => 10.7220, 'lng' => 106.6372],
                    'Thành phố Thủ Đức' => ['lat' => 10.8509, 'lng' => 106.7717],
                    'TP. Thủ Đức' => ['lat' => 10.8509, 'lng' => 106.7717],
                    'TP Thủ Đức' => ['lat' => 10.8509, 'lng' => 106.7717],
                ],
            ],
            'Hà Nội' => [
                'center' => ['lat' => 21.0285, 'lng' => 105.8542],
                'districts' => [
                    'Quận Hoàn Kiếm' => ['lat' => 21.0285, 'lng' => 105.8542],
                    'Quận Long Biên' => ['lat' => 21.0435, 'lng' => 105.8898],
                    'Quận Ba Đình' => ['lat' => 21.0343, 'lng' => 105.8195],
                    'Quận Đống Đa' => ['lat' => 21.0182, 'lng' => 105.8270],
                    'Quận Hai Bà Trưng' => ['lat' => 21.0069, 'lng' => 105.8511],
                ],
            ],
            'Đà Nẵng' => [
                'center' => ['lat' => 16.0544, 'lng' => 108.2022],
                'districts' => [
                    'Quận Hải Châu' => ['lat' => 16.0471, 'lng' => 108.2068],
                    'Quận Thanh Khê' => ['lat' => 16.0614, 'lng' => 108.1522],
                    'Quận Sơn Trà' => ['lat' => 16.0735, 'lng' => 108.2438],
                    'Quận Ngũ Hành Sơn' => ['lat' => 16.0010, 'lng' => 108.2508],
                    'Quận Liên Chiểu' => ['lat' => 16.0754, 'lng' => 108.1527],
                ],
            ],
            'Bình Dương' => [
                'center' => ['lat' => 11.3254, 'lng' => 106.4770],
                'districts' => [
                    'Thành phố Thủ Dầu Một' => ['lat' => 10.9804, 'lng' => 106.6519],
                    'Tp. Thủ Dầu Một' => ['lat' => 10.9804, 'lng' => 106.6519],
                    'TP Thủ Dầu Một' => ['lat' => 10.9804, 'lng' => 106.6519],
                    'TP. Thủ Dầu Một' => ['lat' => 10.9804, 'lng' => 106.6519],
                    'Thành phố Dĩ An' => ['lat' => 10.9067, 'lng' => 106.7690],
                    'TP. Dĩ An' => ['lat' => 10.9067, 'lng' => 106.7690],
                    'Thành phố Thuận An' => ['lat' => 10.9048, 'lng' => 106.6891],
                    'Huyện Dầu Tiếng' => ['lat' => 11.3867, 'lng' => 106.4258],
                ],
            ],
            'Bà Rịa - Vũng Tàu' => [
                'center' => ['lat' => 10.5417, 'lng' => 107.2429],
                'districts' => [
                    'Thành phố Vũng Tàu' => ['lat' => 10.3460, 'lng' => 107.0843],
                    'Thành phố Bà Rịa' => ['lat' => 10.5115, 'lng' => 107.1839],
                    'Huyện Tân Thành' => ['lat' => 10.5786, 'lng' => 107.2113],
                ],
            ],
            'Long An' => [
                'center' => ['lat' => 10.6956, 'lng' => 106.2431],
                'districts' => [
                    'Huyện Đức Hòa' => ['lat' => 10.8772, 'lng' => 106.4054],
                ],
            ],
            'Cần Thơ' => [
                'center' => ['lat' => 10.0452, 'lng' => 105.7469],
                'districts' => [
                    'Quận Ninh Kiều' => ['lat' => 10.0371, 'lng' => 105.7730],
                ],
            ],
            'Hải Phòng' => [
                'center' => ['lat' => 20.8449, 'lng' => 106.6881],
                'districts' => [
                    'Quận Hồng Bàng' => ['lat' => 20.8639, 'lng' => 106.6813],
                ],
            ],
            'Bình Định' => [
                'center' => ['lat' => 13.7830, 'lng' => 109.2196],
                'districts' => [
                    'Thành phố Quy Nhơn' => ['lat' => 13.7830, 'lng' => 109.2196],
                ],
            ],
        ];

        // Lấy tất cả users thuộc waste_company và scrap_dealer
        $wasteCompanies = User::where('role', 'waste_company')->get();
        $scrapDealers = User::where('role', 'scrap_dealer')->get();

        $allUsers = $wasteCompanies->merge($scrapDealers);

        foreach ($allUsers as $user) {
            // Random số lượng điểm tập kết từ 1-3
            $numberOfPoints = rand(1, 3);

            // Xác định tỉnh/thành phố từ địa chỉ công ty
            $companyAddress = $user->company_address ?? '';
            $province = $this->extractProvince($companyAddress);
            $district = $this->extractDistrict($companyAddress);

            // Lấy tọa độ chuẩn
            $coords = $this->getCoordinates($province, $district, $locationCoordinates);

            for ($i = 1; $i <= $numberOfPoints; $i++) {
                // Tạo tên điểm tập kết
                $pointName = $this->generatePointName($user->role, $user->company_name, $i, $numberOfPoints);

                // Tạo địa chỉ chi tiết
                $addressDetails = $this->generateDetailedAddress($i, $district, $province);

                // Thêm offset nhỏ cho mỗi điểm (trong bán kính ~1-2km)
                $latOffset = (rand(-20, 20) / 1000); // ±0.02 độ (~2km)
                $lngOffset = (rand(-20, 20) / 1000);

                CollectionPoint::create([
                    'user_id' => $user->id,
                    'name' => $pointName,
                    'detailed_address' => $addressDetails['detailed_address'],
                    'province' => $province,
                    'district' => $district ?: ($addressDetails['district'] ?? 'Chưa xác định'),
                    'ward' => $addressDetails['ward'],
                    'postal_code' => $this->getPostalCode($province),
                    'address_note' => $addressDetails['note'],
                    'latitude' => $coords['lat'] + $latOffset,
                    'longitude' => $coords['lng'] + $lngOffset,
                ]);

                $this->command->info("✓ Created: {$pointName} for {$user->company_name}");
            }
        }

        $this->command->info("\n✅ Collection points seeding completed!");
        $this->command->info("Total users: " . $allUsers->count());
        $this->command->info("Total points created: " . CollectionPoint::count());
    }

    /**
     * Trích xuất tên tỉnh/thành phố từ địa chỉ
     */
    private function extractProvince(string $address): string
    {
        $provinces = [
            'TP Hồ Chí Minh' => ['TP.HCM', 'Hồ Chí Minh', 'TPHCM', 'HCM'],
            'Hà Nội' => ['Hà Nội', 'Ha Noi', 'Hanoi'],
            'Đà Nẵng' => ['Đà Nẵng', 'Da Nang'],
            'Bình Dương' => ['Bình Dương', 'Binh Duong'],
            'Bà Rịa - Vũng Tàu' => ['Bà Rịa', 'Vũng Tàu', 'BRVT'],
            'Long An' => ['Long An'],
            'Cần Thơ' => ['Cần Thơ', 'Can Tho'],
            'Hải Phòng' => ['Hải Phòng', 'Hai Phong'],
            'Bình Định' => ['Bình Định', 'Binh Dinh'],
        ];

        foreach ($provinces as $standardName => $variations) {
            foreach ($variations as $variation) {
                if (stripos($address, $variation) !== false) {
                    return $standardName;
                }
            }
        }

        return 'TP Hồ Chí Minh'; // Mặc định
    }

    /**
     * Trích xuất quận/huyện từ địa chỉ
     * Logic: Quận/huyện là phần ngay trước tỉnh/thành phố trong địa chỉ
     * VD: "Khu Công Nghệ Cao, TP. Thủ Đức, TP.HCM" -> Quận = "TP. Thủ Đức"
     */
    private function extractDistrict(string $address): ?string
    {
        // Tách địa chỉ theo dấu phẩy
        $parts = array_map('trim', explode(',', $address));

        if (count($parts) < 2) {
            return null;
        }

        // Danh sách tỉnh/thành phố để tìm
        $provinces = [
            'TP.HCM', 'TPHCM', 'Tp. HCM', 'TP HCM', 'TP. HCM',
            'Hà Nội', 'Ha Noi', 'Hanoi',
            'Đà Nẵng', 'Da Nang',
            'Bình Dương', 'Binh Duong',
            'Bà Rịa - Vũng Tàu', 'BRVT', 'Vũng Tàu', 'Bà Rịa',
            'Long An',
            'Cần Thơ', 'Can Tho',
            'Hải Phòng', 'Hai Phong',
            'Bình Định', 'Binh Dinh',
        ];

        // Tìm vị trí của tỉnh trong địa chỉ
        $provinceIndex = -1;
        foreach ($parts as $index => $part) {
            foreach ($provinces as $province) {
                if (stripos($part, $province) !== false) {
                    $provinceIndex = $index;
                    break 2;
                }
            }
        }

        // Nếu tìm thấy tỉnh và có phần tử trước đó, đó chính là quận/huyện
        if ($provinceIndex > 0) {
            return trim($parts[$provinceIndex - 1]);
        }

        return null;
    }

    /**
     * Lấy tọa độ chính xác
     */
    private function getCoordinates(string $province, ?string $district, array $locationCoordinates): array
    {
        if (isset($locationCoordinates[$province])) {
            // Nếu có thông tin quận/huyện và có trong dữ liệu
            if ($district && isset($locationCoordinates[$province]['districts'][$district])) {
                return $locationCoordinates[$province]['districts'][$district];
            }

            // Tìm quận/huyện gần đúng
            if ($district && isset($locationCoordinates[$province]['districts'])) {
                foreach ($locationCoordinates[$province]['districts'] as $districtName => $coords) {
                    if (stripos($district, $districtName) !== false || stripos($districtName, $district) !== false) {
                        return $coords;
                    }
                }
            }

            // Trả về tọa độ trung tâm tỉnh
            return $locationCoordinates[$province]['center'];
        }

        // Mặc định trả về HCM
        return ['lat' => 10.8231, 'lng' => 106.6297];
    }

    /**
     * Tạo tên điểm tập kết
     */
    private function generatePointName(string $role, string $companyName, int $index, int $total): string
    {
        $types = [
            'waste_company' => ['Điểm Thu Gom', 'Kho Tập Kết', 'Điểm Xả Thải'],
            'scrap_dealer' => ['Cơ Sở Thu Mua', 'Vựa Phế Liệu', 'Điểm Thu Mua'],
        ];

        $type = $types[$role][array_rand($types[$role])];

        if ($total == 1) {
            return "{$type} {$companyName}";
        } else {
            return "{$type} {$companyName} - Chi Nhánh {$index}";
        }
    }

    /**
     * Tạo địa chỉ chi tiết
     */
    private function generateDetailedAddress(int $index, ?string $district, string $province): array
    {
        $streetNumbers = [rand(1, 500), rand(1, 500) . '/' . rand(1, 20)];
        $streetNames = [
            'Nguyễn Huệ', 'Lê Lợi', 'Trần Hưng Đạo', 'Hai Bà Trưng', 'Lý Thường Kiệt',
            'Điện Biên Phủ', 'Hoàng Văn Thụ', 'Nguyễn Thị Minh Khai', 'Võ Văn Tần',
            'Xa Lộ Hà Nội', 'Quốc Lộ 1A', 'Phan Văn Trị', 'Cách Mạng Tháng 8'
        ];

        $wards = [
            'Phường ' . rand(1, 28),
            'Phường An Phú', 'Phường Bình An', 'Phường Tân Phú', 'Phường Thảo Điền',
            'Phường Bình Trưng Đông', 'Phường Bình Trưng Tây', 'Phường Linh Trung',
            'Phường Tân Bình', 'Phường Nguyễn Thái Bình', 'Phường Võ Thị Sáu'
        ];

        $notes = [
            'Cổng chính, bên trái',
            'Kho B, tầng trệt',
            'Đối diện chợ đầu mối',
            'Gần KCN',
            'Cạnh cầu vượt',
            'Trong khu công nghiệp',
            'Cuối đường, rẽ phải',
        ];

        return [
            'detailed_address' => $streetNumbers[array_rand($streetNumbers)] . ' ' . $streetNames[array_rand($streetNames)],
            'district' => $district,
            'ward' => $wards[array_rand($wards)],
            'note' => $notes[array_rand($notes)],
        ];
    }

    /**
     * Lấy mã bưu điện
     */
    private function getPostalCode(string $province): string
    {
        $postalCodes = [
            'TP Hồ Chí Minh' => '700000',
            'Hà Nội' => '100000',
            'Đà Nẵng' => '550000',
            'Hải Phòng' => '180000',
            'Cần Thơ' => '900000',
            'Bình Dương' => '820000',
            'Đồng Nai' => '810000',
            'Bà Rịa - Vũng Tàu' => '790000',
            'Long An' => '850000',
            'Bình Định' => '590000',
        ];

        return $postalCodes[$province] ?? '000000';
    }
}
