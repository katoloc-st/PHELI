<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use HasSampleImages;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Copy sample images to storage before seeding
        $this->copySampleImages();

        // Get available images for users
        $avatars = $this->getAvailableImages('avatars');
        $scrapLogos = $this->getAvailableImages('company-logos/scrap');
        $wasteLogos = $this->getAvailableImages('company-logos/waste');
        $recyclingLogos = $this->getAvailableImages('company-logos/recycling');

        $this->call([
            WasteTypeSeeder::class,
            PriceTableSeeder::class,
        ]);

        // Tạo user mẫu cho từng role
        User::updateOrCreate(
            ['email' => 'waste@abc.com'],
            [
                'name' => 'Admin ABC',
                'password' => bcrypt('password'),
                'role' => 'waste_company',
                'company_name' => 'Công ty Sản Xuất ABC',
                'company_address' => 'KCN Tân Bình, Q. Tân Bình, TP.HCM',
                'company_phone' => '02838123456',
                'company_email' => 'contact@abc.com',
                'business_license' => '0301112222',
                'tax_code' => '0301112222',
                'company_description' => 'Chuyên sản xuất bao bì và nhựa, cần thanh lý phế liệu định kỳ.',
                'company_logo' => 'company-logos/waste/VinFast.jpg',
                'phone' => '0901234567',
                'address' => 'KCN Tân Bình, Q. Tân Bình, TP.HCM', // Địa chỉ liên hệ
                'description' => 'Quản lý kho phế liệu',
                'avatar' => $this->getRandomImage($avatars),
                'green_score' => rand(100, 1000),
            ]
        );

        User::updateOrCreate(
            ['email' => 'scrap@xyz.com'],
            [
                'name' => 'Chủ Vựa XYZ',
                'password' => bcrypt('password'),
                'role' => 'scrap_dealer',
                'company_name' => 'Vựa Phế Liệu XYZ',
                'company_address' => 'Xa Lộ Hà Nội, TP. Thủ Đức, TP.HCM',
                'company_phone' => '02839998888',
                'company_email' => 'muahang@xyz.com',
                'business_license' => '0303334444',
                'tax_code' => '0303334444',
                'company_description' => 'Thu mua phế liệu giá cao, uy tín tại TP.HCM.',
                'company_logo' => 'company-logos/scrap/phelieuvn.png',
                'phone' => '0909888777',
                'address' => 'Xa Lộ Hà Nội, TP. Thủ Đức, TP.HCM',
                'description' => 'Chủ cơ sở thu mua',
                'avatar' => $this->getRandomImage($avatars),
            ]
        );

        User::updateOrCreate(
            ['email' => 'recycle@def.com'],
            [
                'name' => 'Giám đốc DEF',
                'password' => bcrypt('password'),
                'role' => 'recycling_plant',
                'company_name' => 'Nhà máy tái chế DEF',
                'company_address' => 'KCN Hòa Khánh, Q. Liên Chiểu, Đà Nẵng',
                'company_phone' => '02363777888',
                'company_email' => 'info@def.com',
                'business_license' => '0405556666',
                'tax_code' => '0405556666',
                'company_description' => 'Chuyên tái chế nhựa và giấy công nghiệp.',
                'company_logo' => 'company-logos/recycling/Pomina.jpg',
                'phone' => '0905111222',
                'address' => 'KCN Hòa Khánh, Q. Liên Chiểu, Đà Nẵng',
                'description' => 'Giám đốc vận hành',
                'avatar' => $this->getRandomImage($avatars),
            ]
        );

        User::updateOrCreate(
            ['email' => 'delivery@staff.com'],
            [
                'name' => 'Nguyễn Văn Giao',
                'password' => bcrypt('password'),
                'role' => 'delivery_staff',
                'phone' => '0999666333',
                'address' => 'Quận 10, TP.HCM',
                'description' => 'Tài xế xe tải 2.5 tấn, kinh nghiệm 5 năm.',
                'avatar' => $this->getRandomImage($avatars),
                // Các trường company để null hoặc điền thông tin cá nhân nếu làm tự do
                'company_name' => 'Vận Tải Nguyễn Giao',
                'company_address' => 'Quận 10, TP.HCM',
                'company_phone' => '0999666333',
                'company_email' => 'delivery@staff.com',
            ]
        );

        // --- 1. DANH SÁCH ĐẠI LÝ PHẾ LIỆU (SCRAP DEALERS) ---
        $scrapDealersData = [
            [
                'contact_name' => 'Quang Đạt',
                'company_name' => 'Phế Liệu Quang Đạt',
                'email' => 'contact@quangdat.vn',
                'address' => '225 Lê Trọng Tấn, P. Bình Hưng Hoà, Q. Bình Tân, TPHCM',
                'phone' => '0978299112',
                'tax_code' => '0314256789',
                'desc' => 'Công ty Quang Đạt chuyên thu mua phế liệu đồng, nhôm, inox, sắt thép giá cao, thanh toán nhanh gọn.',
                'logo' => 'company-logos/scrap/QuangDat.jpg',
            ],
            [
                'contact_name' => 'Việt Đức',
                'company_name' => 'Phế Liệu Việt Đức',
                'email' => 'info@phelieuvietduc.com',
                'address' => '105/1 Đường M1, P. Bình Hưng Hòa, Q. Bình Tân, Tp. HCM',
                'phone' => '0971519789',
                'tax_code' => '0311234567',
                'desc' => 'Đơn vị thu mua phế liệu uy tín hàng đầu, có xe chuyên dụng, dọn dẹp kho bãi sạch sẽ.',
                'logo' => 'company-logos/scrap/VietDuc.jpg',
            ],
            [
                'contact_name' => 'Tuấn Phát',
                'company_name' => 'Phế Liệu Tuấn Phát',
                'email' => 'muahang@phelieutuanphat.com',
                'address' => 'Khu Công Nghiệp Sóng Thần 1, TP. Dĩ An, Bình Dương',
                'phone' => '0982656103',
                'tax_code' => '3700123456',
                'desc' => 'Chuyên thu mua nhà xưởng, xác nhà cũ, phế liệu công trình và kim loại màu số lượng lớn.',
                'logo' => 'company-logos/scrap/TuanPhat.jpg',
            ],
            [
                'contact_name' => 'Mr. Phát',
                'company_name' => 'Phế Liệu Việt Phát',
                'email' => 'sales@phelieuvietphat.vn',
                'address' => '68 Quốc Lộ 1A, Q. 12, TP.HCM',
                'phone' => '0903000555',
                'tax_code' => '0319876543',
                'desc' => 'Thu mua phế liệu giá cao, cam kết cân đo uy tín, hoa hồng hấp dẫn cho người giới thiệu.',
                'logo' => 'company-logos/scrap/VietPhat.png',
            ],
            [
                'contact_name' => 'Thịnh Phát',
                'company_name' => 'Phế Liệu Thịnh Phát',
                'email' => 'thinhphat@scrap.vn',
                'address' => '347 Phú Lợi, Tp. Thủ Dầu Một, Bình Dương',
                'phone' => '0907824824',
                'tax_code' => '3700999888',
                'desc' => 'Nhận thu mua phế liệu tổng hợp: đồng, chì, kẽm, vải cây, vải khúc, nhựa phế liệu.',
                'logo' => 'company-logos/scrap/ThinhPhat.png',
            ],
        ];

        foreach ($scrapDealersData as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['contact_name'], // Tên người liên hệ
                    'password' => bcrypt('password'),
                    'role' => 'scrap_dealer',
                    'company_name' => $data['company_name'],
                    'company_address' => $data['address'],
                    'company_phone' => $data['phone'], // Số điện thoại công ty
                    'company_email' => $data['email'],
                    'business_license' => $data['tax_code'], // Giả lập số ĐKKD giống MST
                    'tax_code' => $data['tax_code'],
                    'company_description' => $data['desc'],
                    'company_logo' => $data['logo'],
                    'phone' => $data['phone'], // Số di động người liên hệ (lấy tạm giống cty)
                    'address' => $data['address'],
                    'description' => 'Quản lý thu mua tại ' . $data['company_name'],
                    'avatar' => $this->getRandomImage($avatars),
                ]
            );
        }

        // --- 2. DANH SÁCH CÔNG TY XẢ THẢI (WASTE COMPANIES) ---
        $wasteCompaniesData = [
            [
                'contact_name' => 'Mr. Hùng',
                'company_name' => 'Xây dựng Hòa Bình',
                'email' => 'project@hoabinh.com',
                'address' => 'Tòa nhà Pax Sky, 123 Nguyễn Đình Chiểu, Q.3, TP.HCM',
                'phone' => '02839325030',
                'tax_code' => '0300445566',
                'desc' => 'Tập đoàn xây dựng hàng đầu VN. Thường xuyên thanh lý sắt thép vụn, giàn giáo, tôn cũ từ công trình.',
                'logo' => 'company-logos/waste/hoabinh.jpg',
            ],
            [
                'contact_name' => 'Ms. Lan',
                'company_name' => 'CT May Việt Tiến',
                'email' => 'factory@viettien.com.vn',
                'address' => '7 Lê Minh Xuân, Q. Tân Bình, TP.HCM',
                'phone' => '02838640800',
                'tax_code' => '0300123456',
                'desc' => 'Nhà máy may mặc quy mô lớn. Cần bán vải vụn, vải khúc 100% cotton, và thùng carton đóng gói.',
                'logo' => 'company-logos/waste/VietTien.jpg',
            ],
            [
                'contact_name' => 'Phong Ba',
                'company_name' => 'Nhựa Tiền Phong (Nam)',
                'email' => 'contact@nhuatienphong.vn',
                'address' => 'KCN Đồng An 2, TP. Thủ Dầu Một, Bình Dương',
                'phone' => '02743813979',
                'tax_code' => '0200111222',
                'desc' => 'Sản xuất ống nhựa PVC, HDPE. Cần thanh lý nhựa phế phẩm (bavia) và khuôn mẫu kim loại cũ.',
                'logo' => 'company-logos/waste/TienPhong.png',
            ],
            [
                'contact_name' => 'Anh Bình',
                'company_name' => 'Bao Bì Tân Tiến',
                'email' => 'info@tantien.com',
                'address' => 'KCN Tân Bình, Q. Tân Bình, TP.HCM',
                'phone' => '02838160777',
                'tax_code' => '0300777888',
                'desc' => 'Chuyên in ấn bao bì màng ghép. Xả thải giấy vụn, lõi giấy, màng nhựa BOPP/PET lỗi in.',
                'logo' => 'company-logos/waste/TanTien.jpg',
            ],
            [
                'contact_name' => 'Duy Khanh',
                'company_name' => 'Cơ Khí Duy Khanh',
                'email' => 'duykhanh@cokhi.com',
                'address' => 'Khu Công Nghệ Cao, TP. Thủ Đức, TP.HCM',
                'phone' => '02837308001',
                'tax_code' => '0300999000',
                'desc' => 'Gia công khuôn mẫu chính xác. Xả phôi nhôm, phôi sắt CNC, dao cụ hỏng hàng ngày.',
                'logo' => 'company-logos/waste/DuyKhanh.jpg',
            ],
        ];

        foreach ($wasteCompaniesData as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['contact_name'],
                    'password' => bcrypt('password'),
                    'role' => 'waste_company',
                    'company_name' => $data['company_name'],
                    'company_address' => $data['address'],
                    'company_phone' => $data['phone'],
                    'company_email' => $data['email'],
                    'business_license' => $data['tax_code'],
                    'tax_code' => $data['tax_code'],
                    'company_description' => $data['desc'],
                    'company_logo' => $data['logo'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'description' => 'Phụ trách xử lý phế liệu tại ' . $data['company_name'],
                    'avatar' => $this->getRandomImage($avatars),
                    'green_score' => rand(100, 1000),
                ]
            );
        }

        // --- 3. DANH SÁCH NHÀ MÁY TÁI CHẾ (RECYCLING PLANTS) ---
        $recyclingPlantsData = [
            [
                'contact_name' => 'Nam Bình',
                'company_name' => 'CTCP Saigon Paper',
                'email' => 'purchase@saigonpaper.com',
                'address' => 'KCN Mỹ Xuân A, Tân Thành, Bà Rịa - Vũng Tàu',
                'phone' => '02543899333',
                'tax_code' => '3500123123',
                'desc' => 'Nhà máy giấy lớn nhất VN. Cần thu mua giấy bìa carton cũ (OCC) và giấy hồ sơ văn phòng số lượng cực lớn.',
                'logo' => 'company-logos/recycling/GiaySG.jpg',
            ],
            [
                'contact_name' => 'Ms. Thủy',
                'company_name' => 'Nhựa Tái Chế Duy Tân',
                'email' => 'recycle@duytan.com',
                'address' => 'Cụm CN Nhựa Đức Hòa Hạ, Đức Hòa, Long An',
                'phone' => '02723779999',
                'tax_code' => '1100678678',
                'desc' => 'Nhà máy tái chế nhựa công nghệ cao Bottle-to-Bottle. Thu mua chai nhựa PET, HDPE đã qua sử dụng.',
                'logo' => 'company-logos/recycling/DuyTan.png',
            ],
            [
                'contact_name' => 'Mr. David',
                'company_name' => 'Thép Pomina',
                'email' => 'nvl@pomina-steel.com',
                'address' => 'KCN Phú Mỹ I, Tân Thành, Bà Rịa - Vũng Tàu',
                'phone' => '02543924444',
                'tax_code' => '3500888999',
                'desc' => 'Nhà máy luyện thép công nghệ cao. Thu mua sắt thép phế liệu các loại để nấu luyện phôi thép.',
                'logo' => 'company-logos/recycling/Pomina.jpg',
            ],
            [
                'contact_name' => 'Việt Úc',
                'company_name' => 'Môi Trường Việt Úc',
                'email' => 'info@vietuc-waste.com',
                'address' => 'KCN Lê Minh Xuân, Q. Bình Chánh, TP.HCM',
                'phone' => '02837661111',
                'tax_code' => '0300555444',
                'desc' => 'Chuyên xử lý chất thải nguy hại công nghiệp, tái chế dầu nhớt thải, dung môi và ắc quy chì.',
                'logo' => 'company-logos/recycling/VietUc.jpg',
            ],
            [
                'contact_name' => 'Cullet',
                'company_name' => 'Thủy Tinh O-I BJC Việt Nam',
                'email' => 'cullet@oibjc.com.vn',
                'address' => 'KCN Mỹ Xuân A, Tân Thành, Bà Rịa - Vũng Tàu',
                'phone' => '02543931111',
                'tax_code' => '3500333222',
                'desc' => 'Nhà máy sản xuất chai lọ thủy tinh quốc tế. Thu mua thủy tinh vụn (cullet) trắng, nâu, xanh để tái chế.',
                'logo' => 'company-logos/recycling/BJC.png',
            ],
        ];

        foreach ($recyclingPlantsData as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['contact_name'],
                    'password' => bcrypt('password'),
                    'role' => 'recycling_plant',
                    'company_name' => $data['company_name'],
                    'company_address' => $data['address'],
                    'company_phone' => $data['phone'],
                    'company_email' => $data['email'],
                    'business_license' => $data['tax_code'],
                    'tax_code' => $data['tax_code'],
                    'company_description' => $data['desc'],
                    'company_logo' => $data['logo'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'description' => 'Đại diện nhà máy ' . $data['company_name'],
                    'avatar' => $this->getRandomImage($avatars),
                ]
            );
        }

        // Gọi seeders sau khi đã có users
        $this->call([
            CollectionPointSeeder::class,
            PostSeeder::class,
            VoucherSeeder::class,
            ReviewSeeder::class,
        ]);
    }

    /**
     * Copy sample images from database/seeders/sample-images to storage/app/public
     */
    private function copySampleImages(): void
    {
        $this->command->info('Copying sample images to storage...');

        // Delete existing images in storage to avoid duplicates
        $storagePath = storage_path('app/public');
        $foldersToClean = ['avatars', 'company-logos', 'posts'];

        foreach ($foldersToClean as $folder) {
            $folderPath = $storagePath . '/' . $folder;
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
                $this->command->info("  ✖ Deleted existing {$folder}/ folder");
            }
        }

        // Define waste types for posts
        $wasteTypes = ['Thiec', 'Hopkim', 'Chi', 'Niken', 'Sat', 'Nhom', 'Dong', 'Inox', 'Kem', 'Caosu', 'Giay', 'Nhua'];
        $roles = ['wastecompany', 'scrapdealer'];

        // Build image mappings dynamically
        $imageMappings = [
            'avatars' => 'avatars',
            'company-logos/scrap' => 'company-logos/scrap',
            'company-logos/waste' => 'company-logos/waste',
            'company-logos/recycling' => 'company-logos/recycling',
        ];
        // Add all waste type + role combinations
        foreach ($wasteTypes as $type) {
            foreach ($roles as $role) {
                $imageMappings["posts/{$type}/{$role}"] = "posts/{$type}/{$role}";
            }
        }


        foreach ($imageMappings as $source => $destination) {
            $sourcePath = database_path("seeders/sample-images/{$source}");
            $destinationPath = storage_path("app/public/{$destination}");

            // Create destination directory if not exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Check if source directory exists and has images
            if (File::exists($sourcePath)) {
                $files = File::files($sourcePath);
                $copiedCount = 0;

                foreach ($files as $file) {
                    // Skip .gitkeep
                    if ($file->getFilename() === '.gitkeep') {
                        continue;
                    }

                    // Only copy image files
                    $extension = strtolower($file->getExtension());
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $destinationFile = $destinationPath . '/' . $file->getFilename();
                        File::copy($file->getPathname(), $destinationFile);
                        $copiedCount++;
                    }
                }

                if ($copiedCount > 0) {
                    $this->command->info("  ✓ Copied {$copiedCount} image(s) to {$destination}/");
                } else {
                    $this->command->warn("  ⚠ No images found in sample-images/{$source}/");
                }
            }
        }

        $this->command->info('Sample images copied successfully!');
    }
}
