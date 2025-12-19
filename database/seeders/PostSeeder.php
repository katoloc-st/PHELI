<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WasteType;
use App\Models\PriceTable;
use App\Models\Post;
use App\Models\CollectionPoint;

class PostSeeder extends Seeder
{
    use HasSampleImages;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Starting post seeding...');

        // Lấy tất cả waste types và price tables
        $wasteTypes = WasteType::all();
        $priceTables = PriceTable::with('wasteType')->get()->keyBy('waste_type_id');

        // Lấy users theo role
        $wasteCompanies = User::where('role', 'waste_company')->get();
        $scrapDealers = User::where('role', 'scrap_dealer')->get();

        $allPostsData = []; // Thu thập tất cả bài đăng

        // Duyệt qua từng loại phế liệu (12 loại)
        foreach ($wasteTypes as $wasteType) {
            // Lấy giá chuẩn từ bảng giá
            $standardPrice = $priceTables[$wasteType->id]->price ?? 10000;

            // 3 bài đăng từ waste companies (giá thấp hơn hoặc bằng giá bảng)
            for ($i = 0; $i < 3; $i++) {
                $user = $wasteCompanies->random();
                $collectionPoints = CollectionPoint::where('user_id', $user->id)->get();

                if ($collectionPoints->isEmpty()) {
                    continue;
                }

                $collectionPoint = $collectionPoints->random();

                // Giá = giá bảng - (0% đến 10%)
                $priceReduction = rand(0, 10) / 100;
                $price = $standardPrice * (1 - $priceReduction);

                // Get sample images for waste_company role
                $sampleImages = $this->getAvailableImages('posts', $wasteType->name, 'waste_company');

                $postData = $this->generateWasteCompanyPost($wasteType, $user, $collectionPoint, $price);
                $postData['images'] = $this->getRandomImages($sampleImages, rand(1, 3));

                $allPostsData[] = $postData; // Thêm vào mảng thay vì tạo ngay
            }

            // 3 bài đăng từ scrap dealers (bán với giá cao hơn giá bảng)
            for ($i = 0; $i < 3; $i++) {
                $user = $scrapDealers->random();
                $collectionPoints = CollectionPoint::where('user_id', $user->id)->get();

                if ($collectionPoints->isEmpty()) {
                    continue;
                }

                $collectionPoint = $collectionPoints->random();

                // Giá = giá bảng + (0% đến 10%)
                $priceIncrease = rand(0, 10) / 100;
                $price = $standardPrice * (1 + $priceIncrease);

                // Get sample images for scrap_dealer role
                $sampleImages = $this->getAvailableImages('posts', $wasteType->name, 'scrap_dealer');

                $postData = $this->generateScrapDealerPost($wasteType, $user, $collectionPoint, $price);
                $postData['images'] = $this->getRandomImages($sampleImages, rand(1, 3));

                $allPostsData[] = $postData; // Thêm vào mảng thay vì tạo ngay
            }
        }

        // Shuffle để xáo trộn thứ tự
        shuffle($allPostsData);

        // Tạo bài đăng với created_at ngẫu nhiên trong 30 ngày qua
        $totalPosts = 0;
        $now = now();

        foreach ($allPostsData as $postData) {
            // Random created_at trong khoảng 30 ngày trước đến hiện tại
            $daysAgo = rand(0, 30);
            $hoursAgo = rand(0, 23);
            $minutesAgo = rand(0, 59);

            $postData['created_at'] = $now->copy()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes($minutesAgo);
            $postData['updated_at'] = $postData['created_at'];

            Post::create($postData);
            $totalPosts++;

            $this->command->info("✓ Created post: {$postData['title']}");
        }

        $this->command->info("\n✅ Post seeding completed!");
        $this->command->info("Total posts created: {$totalPosts}");
        $this->command->info("Waste types: " . $wasteTypes->count());
    }

    /**
     * Tạo bài đăng cho waste company (bán phế liệu)
     */
    private function generateWasteCompanyPost(WasteType $wasteType, User $user, CollectionPoint $collectionPoint, float $price): array
    {
        $titles = [
            'Thiếc' => [
                "Thanh lý thiếc phế liệu chất lượng cao từ {company}",
                "Cần bán nhanh thiếc vụn từ sản xuất điện tử",
                "Bán thiếc phế liệu giá tốt - {company}",
            ],
            'Hợp kim' => [
                "Thanh lý hợp kim nhôm phế phẩm từ {company}",
                "Bán hợp kim đồng-kẽm vụn từ gia công cơ khí",
                "Phế liệu hợp kim chất lượng - {company}",
            ],
            'Chì' => [
                "Thanh lý chì vụn từ ắc quy cũ - {company}",
                "Bán chì phế liệu an toàn, đã xử lý",
                "Cần thanh lý chì từ sản xuất pin",
            ],
            'Niken' => [
                "Phế liệu niken từ xi mạ điện - {company}",
                "Bán niken vụn chất lượng cao",
                "Thanh lý niken phế phẩm sản xuất",
            ],
            'Sắt' => [
                "Thanh lý sắt thép phế liệu từ công trình - {company}",
                "Bán sắt vụn số lượng lớn",
                "Phế liệu sắt thép xây dựng chất lượng",
            ],
            'Nhôm' => [
                "Thanh lý nhôm phế liệu từ sản xuất - {company}",
                "Bán nhôm vụn đã phân loại sạch sẽ",
                "Phế liệu nhôm chất lượng cao",
            ],
            'Đồng' => [
                "Thanh lý đồng phế liệu từ dây cáp - {company}",
                "Bán đồng vụn giá tốt, số lượng lớn",
                "Phế liệu đồng điện từ nhà máy",
            ],
            'Inox' => [
                "Thanh lý inox phế liệu 304 - {company}",
                "Bán inox vụn từ sản xuất bếp công nghiệp",
                "Phế liệu inox chất lượng cao",
            ],
            'Kẽm' => [
                "Thanh lý kẽm mạ vụn - {company}",
                "Bán kẽm phế liệu từ tôn lợp",
                "Phế liệu kẽm số lượng lớn",
            ],
            'Cao su' => [
                "Thanh lý cao su phế liệu từ sản xuất - {company}",
                "Bán cao su vụn làm sạch",
                "Phế liệu cao su công nghiệp",
            ],
            'Giấy' => [
                "Thanh lý giấy carton vụn từ {company}",
                "Bán giấy phế liệu văn phòng số lượng lớn",
                "Phế liệu giấy OCC chất lượng",
            ],
            'Nhựa' => [
                "Thanh lý nhựa PET phế liệu - {company}",
                "Bán nhựa vụn đã phân loại theo màu",
                "Phế liệu nhựa HDPE chất lượng",
            ],
        ];

        $descriptions = [
            'Thiếc' => [
                "Thiếc phế liệu từ quy trình sản xuất điện tử, độ tinh khiết cao, đã làm sạch và phân loại. Phù hợp cho tái chế và nấu luyện. Số lượng: {quantity} kg, giao hàng tận nơi trong nội thành.",
                "Chúng tôi cần thanh lý lô thiếc vụn từ sản xuất, chất lượng tốt, không lẫn tạp chất. Giá cả hợp lý, có thể thương lượng cho đơn hàng lớn. Liên hệ ngay!",
                "Thiếc phế liệu công nghiệp, nguồn gốc rõ ràng từ {company}. Đã kiểm tra chất lượng và sẵn sàng xuất hàng. Hỗ trợ vận chuyển.",
            ],
            'Hợp kim' => [
                "Hợp kim phế liệu từ gia công cơ khí chính xác, bao gồm hợp kim nhôm và đồng. Đã phân loại theo loại hợp kim, sẵn sàng cho tái chế. Giao hàng nhanh chóng.",
                "Thanh lý hợp kim vụn từ sản xuất khuôn mẫu, chất lượng cao, phù hợp cho nấu luyện lại. Số lượng {quantity} kg, giá tốt nhất thị trường.",
                "Phế liệu hợp kim đa dạng từ {company}, bao gồm các loại hợp kim thông dụng. Có chứng từ nguồn gốc, xuất VAT đầy đủ.",
            ],
            'Chì' => [
                "Chì phế liệu từ ắc quy cũ đã được xử lý và làm sạch theo đúng quy trình. An toàn cho môi trường, phù hợp cho tái chế. Liên hệ để biết giá tốt nhất.",
                "Thanh lý chì vụn từ sản xuất pin, độ tinh khiết cao, không lẫn tạp chất. Số lượng {quantity} kg, giao hàng tận nơi.",
                "Phế liệu chì công nghiệp từ {company}, đã qua kiểm định chất lượng. Hỗ trợ vận chuyển và làm thủ tục theo quy định.",
            ],
            'Niken' => [
                "Niken phế liệu từ xi mạ điện, độ tinh khiết cao, phù hợp cho tái chế và sản xuất hợp kim. Giá cả cạnh tranh, giao hàng nhanh.",
                "Thanh lý niken vụn từ sản xuất pin lithium, chất lượng tốt. Số lượng {quantity} kg, có thể đặt hàng định kỳ.",
                "Phế liệu niken công nghiệp từ {company}, nguồn gốc rõ ràng. Xuất hóa đơn VAT đầy đủ.",
            ],
            'Sắt' => [
                "Sắt thép phế liệu từ công trình xây dựng, bao gồm sắt thép hình, thanh, tấm. Đã cắt gọn và phân loại. Số lượng lớn, giá tốt.",
                "Thanh lý sắt vụn từ nhà xưởng cũ, chất lượng tốt, không gỉ sét. {quantity} kg sẵn sàng xuất hàng ngay.",
                "Phế liệu sắt thép từ {company}, bao gồm nhiều loại sắt khác nhau. Hỗ trợ cân đo và vận chuyển miễn phí.",
            ],
            'Nhôm' => [
                "Nhôm phế liệu từ sản xuất, đã phân loại theo độ tinh khiết. Gồm nhôm tấm, nhôm hình, nhôm đúc. Giá cạnh tranh nhất thị trường.",
                "Thanh lý nhôm vụn từ gia công CNC, chất lượng cao, không lẫn sơn. {quantity} kg, giao hàng tận xưởng.",
                "Phế liệu nhôm công nghiệp từ {company}, bao gồm nhôm phoi và nhôm vụn. Có chứng từ đầy đủ.",
            ],
            'Đồng' => [
                "Đồng phế liệu từ dây cáp điện, đã bóc vỏ và làm sạch. Độ tinh khiết cao, phù hợp cho nấu luyện. Giá tốt cho số lượng lớn.",
                "Thanh lý đồng vụn từ máy móc, thiết bị điện tử cũ. Chất lượng đảm bảo, {quantity} kg sẵn sàng giao.",
                "Phế liệu đồng điện từ {company}, bao gồm đồng đỏ và đồng vàng. Xuất VAT theo yêu cầu.",
            ],
            'Inox' => [
                "Inox phế liệu 304 từ sản xuất bếp công nghiệp, chất lượng cao, không gỉ sét. Đã cắt gọn và phân loại. Số lượng {quantity} kg.",
                "Thanh lý inox vụn nhiều loại: 201, 304, 316. Giá cả hợp lý, giao hàng nhanh chóng trong nội thành.",
                "Phế liệu inox từ {company}, nguồn gốc rõ ràng. Hỗ trợ kiểm tra chất lượng trước khi giao.",
            ],
            'Kẽm' => [
                "Kẽm phế liệu từ tôn mạ, đã tách riêng kẽm và sắt. Chất lượng tốt, phù hợp cho tái chế. Liên hệ để có giá tốt nhất.",
                "Thanh lý kẽm vụn từ gia công kim loại, độ tinh khiết cao. {quantity} kg sẵn sàng xuất hàng.",
                "Phế liệu kẽm mạ từ {company}, chất lượng đảm bảo. Giao hàng tận nơi, thanh toán linh hoạt.",
            ],
            'Cao su' => [
                "Cao su phế liệu từ sản xuất lốp xe, băng tải công nghiệp. Đã làm sạch và phân loại. Số lượng lớn, giá tốt.",
                "Thanh lý cao su vụn từ nhà máy, chất lượng cao, không lẫn tạp chất. {quantity} kg, giao hàng nhanh.",
                "Phế liệu cao su công nghiệp từ {company}, phù hợp cho tái chế. Xuất hóa đơn đầy đủ.",
            ],
            'Giấy' => [
                "Giấy carton OCC phế liệu từ bao bì, đã phân loại loại bỏ keo băng và nhãn. Số lượng {quantity} kg, giá tốt nhất.",
                "Thanh lý giấy văn phòng phế liệu, đã hủy tài liệu bảo mật. Chất lượng cao, phù hợp cho tái chế.",
                "Phế liệu giấy từ {company}, bao gồm giấy trắng, giấy báo, carton. Giao hàng tận xưởng.",
            ],
            'Nhựa' => [
                "Nhựa PET phế liệu từ chai lọ, đã phân loại theo màu và làm sạch. Chất lượng cao, phù hợp cho tái chế bottle-to-bottle.",
                "Thanh lý nhựa HDPE vụn từ sản xuất, độ tinh khiết tốt. {quantity} kg, giá cạnh tranh.",
                "Phế liệu nhựa PP/PE từ {company}, đã nghiền nhỏ và phân loại. Hỗ trợ vận chuyển toàn quốc.",
            ],
        ];

        $wasteName = $wasteType->name;
        $titleTemplates = $titles[$wasteName] ?? ["Thanh lý {waste} phế liệu - {company}"];
        $descTemplates = $descriptions[$wasteName] ?? ["Phế liệu {waste} chất lượng từ {company}. Liên hệ để biết thêm chi tiết."];

        $title = str_replace(
            ['{waste}', '{company}'],
            [$wasteName, $user->company_name],
            $titleTemplates[array_rand($titleTemplates)]
        );

        $quantity = rand(10, 200);
        $description = str_replace(
            ['{waste}', '{company}', '{quantity}'],
            [$wasteName, $user->company_name, $quantity],
            $descTemplates[array_rand($descTemplates)]
        );

        return [
            'user_id' => $user->id,
            'collection_point_id' => $collectionPoint->id,
            'waste_type_id' => $wasteType->id,
            'title' => $title,
            'description' => $description,
            'quantity' => $quantity,
            'price' => round($price, 0),
            'type' => 'doanh_nghiep_xanh',
            'status' => 'active',
        ];
    }

    /**
     * Tạo bài đăng cho scrap dealer (bán phế liệu với giá cao)
     */
    private function generateScrapDealerPost(WasteType $wasteType, User $user, CollectionPoint $collectionPoint, float $price): array
    {
        $titles = [
            'Thiếc' => [
                "Bán thiếc phế liệu chất lượng cao - {company}",
                "Thiếc vụn số lượng lớn giá tốt tại {location}",
                "Phế liệu thiếc sẵn sàng giao hàng ngay",
            ],
            'Hợp kim' => [
                "Bán hợp kim phế liệu các loại - {company}",
                "Hợp kim vụn chất lượng cao giá tốt",
                "Phế liệu hợp kim số lượng lớn",
            ],
            'Chì' => [
                "Bán chì phế liệu an toàn - {company}",
                "Chì vụn chất lượng tốt tại {location}",
                "Phế liệu chì sẵn sàng xuất hàng",
            ],
            'Niken' => [
                "Bán niken phế liệu chất lượng - {company}",
                "Niken vụn số lượng lớn giá tốt",
                "Phế liệu niken giao hàng nhanh",
            ],
            'Sắt' => [
                "Bán sắt thép phế liệu - {company}",
                "Sắt vụn chất lượng tốt tại {location}",
                "Phế liệu sắt thép số lượng lớn",
            ],
            'Nhôm' => [
                "Bán nhôm phế liệu chất lượng - {company}",
                "Nhôm vụn các loại giá tốt tại {location}",
                "Phế liệu nhôm sẵn sàng giao hàng",
            ],
            'Đồng' => [
                "Bán đồng phế liệu chất lượng cao - {company}",
                "Đồng vụn số lượng lớn tại {location}",
                "Phế liệu đồng giao hàng nhanh",
            ],
            'Inox' => [
                "Bán inox phế liệu các loại - {company}",
                "Inox vụn chất lượng tốt tại {location}",
                "Phế liệu inox sẵn sàng xuất hàng",
            ],
            'Kẽm' => [
                "Bán kẽm phế liệu chất lượng - {company}",
                "Kẽm vụn số lượng lớn giá tốt",
                "Phế liệu kẽm giao hàng tận nơi",
            ],
            'Cao su' => [
                "Bán cao su phế liệu - {company}",
                "Cao su vụn chất lượng tốt tại {location}",
                "Phế liệu cao su sẵn sàng xuất hàng",
            ],
            'Giấy' => [
                "Bán giấy phế liệu chất lượng - {company}",
                "Giấy carton vụn số lượng lớn tại {location}",
                "Phế liệu giấy giao hàng nhanh",
            ],
            'Nhựa' => [
                "Bán nhựa phế liệu các loại - {company}",
                "Nhựa vụn chất lượng cao tại {location}",
                "Phế liệu nhựa sẵn sàng giao hàng",
            ],
        ];

        $descriptions = [
            'Thiếc' => [
                "{company} chuyên cung cấp thiếc phế liệu chất lượng cao. Hàng sẵn kho, giao hàng nhanh chóng. Giá {price} đồng/kg, có thể thương lượng cho đơn hàng lớn.",
                "Bán thiếc vụn số lượng lớn, độ tinh khiết cao. {company} cam kết chất lượng, hỗ trợ vận chuyển toàn quốc. Liên hệ ngay để được tư vấn.",
                "Vựa phế liệu {company} có sẵn thiếc phế liệu chất lượng. Đã phân loại kỹ càng, giá tốt nhất thị trường. Giao hàng tận nơi.",
            ],
            'Hợp kim' => [
                "{company} cung cấp hợp kim phế liệu các loại: nhôm, đồng, kẽm. Hàng có sẵn, chất lượng đảm bảo, giá cạnh tranh.",
                "Bán hợp kim vụn số lượng lớn với giá {price} đồng/kg. Đã kiểm tra chất lượng, có chứng từ nguồn gốc. Giao hàng nhanh.",
                "Phế liệu hợp kim từ {company}, chất lượng cao, phù hợp cho tái chế. Hỗ trợ vận chuyển và xuất hóa đơn đầy đủ.",
            ],
            'Chì' => [
                "{company} chuyên cung cấp chì phế liệu đã xử lý an toàn. Có giấy phép đầy đủ, giá {price} đồng/kg, giao hàng nhanh.",
                "Bán chì vụn chất lượng cao, đã làm sạch và phân loại. {company} cam kết nguồn gốc rõ ràng, tuân thủ quy định môi trường.",
                "Phế liệu chì số lượng lớn tại vựa {company}. Hàng sẵn kho, giá tốt, hỗ trợ vận chuyển an toàn.",
            ],
            'Niken' => [
                "{company} cung cấp niken phế liệu chất lượng cao. Hàng có sẵn, giá {price} đồng/kg, giao hàng nhanh chóng.",
                "Bán niken vụn số lượng lớn, độ tinh khiết cao. Đã kiểm tra bằng máy móc hiện đại. {company} uy tín nhiều năm.",
                "Phế liệu niken từ {company}, nguồn gốc rõ ràng. Hỗ trợ xuất hóa đơn VAT, giao hàng toàn quốc.",
            ],
            'Sắt' => [
                "{company} chuyên cung cấp sắt thép phế liệu. Giá {price} đồng/kg, hàng có sẵn kho, giao hàng nhanh chóng.",
                "Bán sắt vụn số lượng lớn: sắt thép hình, thanh, tấm, phoi. Chất lượng tốt, giá cạnh tranh, dịch vụ chuyên nghiệp.",
                "Vựa phế liệu {company} có sẵn sắt thép chất lượng. Đã cắt gọn, phân loại kỹ. Hỗ trợ vận chuyển miễn phí.",
            ],
            'Nhôm' => [
                "{company} cung cấp nhôm phế liệu các loại: tấm, hình, đúc, phoi. Giá {price} đồng/kg, hàng sẵn kho.",
                "Bán nhôm vụn số lượng lớn, đã phân loại theo độ tinh khiết. {company} cam kết chất lượng, giá tốt.",
                "Phế liệu nhôm uy tín từ {company}. Hàng có sẵn, báo giá minh bạch, giao hàng nhanh chóng.",
            ],
            'Đồng' => [
                "{company} chuyên cung cấp đồng phế liệu chất lượng cao. Đồng đỏ, đồng vàng, giá {price} đồng/kg, hàng sẵn kho.",
                "Bán đồng vụn số lượng lớn, chất lượng đảm bảo. {company} có nguồn hàng ổn định, giá cạnh tranh.",
                "Phế liệu đồng tất cả các loại từ {company}. Hàng chất lượng, giao hàng nhanh, hỗ trợ vận chuyển.",
            ],
            'Inox' => [
                "{company} chuyên cung cấp inox phế liệu: 201, 304, 316. Giá {price} đồng/kg, hàng có sẵn, giao nhanh.",
                "Bán inox vụn số lượng lớn, đã phân loại theo chất lượng. {company} cam kết giá tốt, dịch vụ chuyên nghiệp.",
                "Phế liệu inox các loại tại vựa {company}. Hàng sẵn kho, chất lượng cao, hỗ trợ vận chuyển.",
            ],
            'Kẽm' => [
                "{company} cung cấp kẽm phế liệu chất lượng. Kẽm mạ, kẽm tấm, giá {price} đồng/kg, giao hàng tận nơi.",
                "Bán kẽm vụn số lượng lớn, hàng có sẵn kho. {company} cam kết chất lượng, giá cạnh tranh.",
                "Phế liệu kẽm uy tín từ {company}. Hàng sẵn có, giao hàng nhanh, hỗ trợ vận chuyển.",
            ],
            'Cao su' => [
                "{company} chuyên cung cấp cao su phế liệu công nghiệp. Giá {price} đồng/kg, hàng có sẵn, giao hàng nhanh.",
                "Bán cao su vụn số lượng lớn, đã làm sạch và phân loại. {company} cam kết chất lượng, giá tốt.",
                "Phế liệu cao su các loại từ {company}. Hàng sẵn kho, giao hàng toàn quốc, giá cạnh tranh.",
            ],
            'Giấy' => [
                "{company} chuyên cung cấp giấy phế liệu: carton, giấy trắng, giấy báo. Giá {price} đồng/kg, hàng sẵn kho.",
                "Bán giấy vụn số lượng lớn, chất lượng cao. {company} có nguồn hàng ổn định, dịch vụ tận tâm.",
                "Phế liệu giấy từ {company}, đã phân loại kỹ càng. Hàng có sẵn, giá tốt, hỗ trợ vận chuyển.",
            ],
            'Nhựa' => [
                "{company} cung cấp nhựa phế liệu: PET, HDPE, PP, PE. Giá {price} đồng/kg, đã phân loại theo màu sắc.",
                "Bán nhựa vụn số lượng lớn, chất lượng cao. {company} có hàng sẵn kho, giao hàng nhanh chóng.",
                "Phế liệu nhựa các loại từ {company}. Hàng chất lượng, giá cạnh tranh, làm việc chuyên nghiệp.",
            ],
        ];

        $wasteName = $wasteType->name;
        $titleTemplates = $titles[$wasteName] ?? ["Thu mua {waste} phế liệu - {company}"];
        $descTemplates = $descriptions[$wasteName] ?? ["Thu mua {waste} phế liệu giá cao tại {company}. Liên hệ ngay!"];

        $title = str_replace(
            ['{waste}', '{company}', '{location}'],
            [$wasteName, $user->company_name, $collectionPoint->district],
            $titleTemplates[array_rand($titleTemplates)]
        );

        $description = str_replace(
            ['{waste}', '{company}', '{price}'],
            [$wasteName, $user->company_name, number_format($price, 0, ',', '.')],
            $descTemplates[array_rand($descTemplates)]
        );

        return [
            'user_id' => $user->id,
            'collection_point_id' => $collectionPoint->id,
            'waste_type_id' => $wasteType->id,
            'title' => $title,
            'description' => $description,
            'quantity' => rand(100, 2000),
            'price' => round($price, 0),
            'type' => 'co_so_phe_lieu',
            'status' => 'active',
        ];
    }
}
