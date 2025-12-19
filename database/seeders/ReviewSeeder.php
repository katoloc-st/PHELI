<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting review seeding...');

        $posts = Post::all();
        $allUsers = User::all();
        $totalReviews = 0;

        foreach ($posts as $post) {
            // Mỗi bài đăng có từ 1-5 reviews
            $reviewCount = rand(1, 5);

            // Lấy users không phải chủ bài đăng và không phải delivery_staff
            $availableUsers = $allUsers->reject(function ($user) use ($post) {
                return $user->id === $post->user_id || $user->role === 'delivery_staff';
            });

            // Nếu không có user nào khác, skip
            if ($availableUsers->isEmpty()) {
                continue;
            }

            // Random chọn users để review (không trùng lặp)
            $reviewers = $availableUsers->random(min($reviewCount, $availableUsers->count()));

            foreach ($reviewers as $reviewer) {
                $rating = $this->generateRating();
                $content = $this->generateReviewContent($rating, $post->type);

                Review::updateOrCreate(
                    [
                        'post_id' => $post->id,
                        'user_id' => $reviewer->id,
                    ],
                    [
                        'rating' => $rating,
                        'content' => $content,
                        'status' => 'approved'
                    ]
                );

                $totalReviews++;
            }

            $this->command->info("✓ Created {$reviewers->count()} reviews for post: {$post->title}");
        }

        $this->command->info("\n✅ Review seeding completed!");
        $this->command->info("Total reviews created: {$totalReviews}");
        $this->command->info("Posts with reviews: " . $posts->count());
    }

    /**
     * Tạo rating ngẫu nhiên với xu hướng tích cực (4-5 sao nhiều hơn)
     */
    private function generateRating(): int
    {
        $rand = rand(1, 100);

        // 50% là 5 sao
        if ($rand <= 50) return 5;
        // 30% là 4 sao
        if ($rand <= 80) return 4;
        // 10% là 3 sao
        if ($rand <= 90) return 3;
        // 7% là 2 sao
        if ($rand <= 97) return 2;
        // 3% là 1 sao
        return 1;
    }

    /**
     * Tạo nội dung review realistic dựa trên rating và loại bài đăng
     */
    private function generateReviewContent(int $rating, string $postType): string
    {
        $isDoanghNghiep = $postType === 'doanh_nghiep_xanh';

        $reviews = [
            5 => [
                "Chất lượng phế liệu xuất sắc, đúng như mô tả. Người bán rất chuyên nghiệp, giao hàng đúng hẹn. Rất hài lòng!",
                "Hàng tốt, giá cả phải chăng. Đã làm việc nhiều lần, luôn uy tín. Chắc chắn sẽ hợp tác dài lâu.",
                "Phế liệu chất lượng cao, đã phân loại kỹ càng. Giao dịch nhanh gọn, thanh toán đúng hẹn. Rất recommend!",
                "Tuyệt vời! Hàng đẹp, giá tốt, dịch vụ chuyên nghiệp. Đội ngũ nhân viên nhiệt tình, hỗ trợ tận tâm.",
                "Rất hài lòng với chất lượng và dịch vụ. Cân đo chính xác, không chặt chém. Sẽ giới thiệu cho bạn bè.",
                "Phế liệu sạch sẽ, đúng quy cách. Người bán uy tín, làm việc nhanh chóng. 5 sao xứng đáng!",
                "Chất lượng vượt mong đợi! Giá cả hợp lý, giao hàng đúng giờ. Đây là đối tác đáng tin cậy.",
                "Hàng tốt, giá đẹp. Đã giao dịch nhiều lần, lần nào cũng ok. Rất tin tưởng và sẽ tiếp tục hợp tác.",
            ],
            4 => [
                "Hàng tốt, đúng mô tả. Giá cả hợp lý. Chỉ có điều giao hàng hơi chậm một chút, nhưng nhìn chung ok.",
                "Chất lượng phế liệu ổn, người bán nhiệt tình. Sẽ mua lại nếu có nhu cầu.",
                "Sản phẩm khá tốt, đúng như đã trao đổi. Một số chỗ lẫn tạp chất nhưng không đáng kể. Tạm ổn.",
                "Giao dịch suôn sẻ, người bán thân thiện. Giá có thể tốt hơn một chút nhưng chấp nhận được.",
                "Phế liệu chất lượng tốt, đã phân loại. Chỉ có việc giao hàng hơi lâu. Nhìn chung hài lòng.",
                "Ổn, đáng để làm việc. Cân đo chính xác, người bán uy tín. Sẽ quay lại nếu có nhu cầu.",
                "Hàng đúng mô tả, giá cả phải chăng. Chỉ tiếc là số lượng không nhiều lắm. Sẽ hợp tác lại.",
                "Khá hài lòng. Phế liệu sạch, giá tốt. Giao dịch nhanh gọn, không có vấn đề gì.",
            ],
            3 => [
                "Hàng tạm được, có một số chỗ không đúng mô tả lắm nhưng cũng chấp nhận được.",
                "Chất lượng trung bình, giá cả bình thường. Không xuất sắc nhưng cũng không tệ.",
                "Giao dịch bình thường, không có gì đặc biệt. Hàng tạm ổn, giá cũng vậy.",
                "Phế liệu có lẫn chút tạp chất, cần làm sạch thêm. Người bán cũng khá ok nhưng chưa thật sự ấn tượng.",
                "Tạm được. Một số thứ đúng mô tả, một số không. Cân nhắc trước khi mua.",
                "Hàng không tốt lắm nhưng giá rẻ nên chấp nhận được. Sẽ xem xét nếu mua lại.",
                "Bình thường. Giao dịch không có vấn đề gì nhưng cũng không có điểm nổi bật.",
            ],
            2 => [
                "Hàng không được như mong đợi. Chất lượng kém hơn mô tả khá nhiều.",
                "Không hài lòng lắm. Phế liệu lẫn nhiều tạp chất, cân nặng cũng không đúng.",
                "Dưới mức mong đợi. Người bán hứa suông, thực tế giao hàng kém hơn nhiều.",
                "Chất lượng không tốt, nhiều phế phẩm lẫn vào. Giá thì cao mà hàng không tương xứng.",
                "Không được như lời giới thiệu. Hơi thất vọng về chất lượng và dịch vụ.",
                "Giao hàng chậm, chất lượng không đúng cam kết. Cần cải thiện nhiều.",
            ],
            1 => [
                "Rất thất vọng! Hàng hoàn toàn không đúng mô tả. Lẫn quá nhiều tạp chất.",
                "Tệ! Đã mất thời gian và tiền bạc. Chất lượng quá kém, người bán không uy tín.",
                "Không đáng tin. Hàng giao không đúng, cân nặng thiếu nhiều. Rất tệ!",
                "Thất vọng hoàn toàn. Phế liệu toàn rác, không thể sử dụng được. Không nên giao dịch.",
                "Cực kỳ tệ! Lừa đảo, hàng không đúng mô tả. Đã báo với admin để xử lý.",
            ],
        ];

        // Chọn random một review từ mảng tương ứng với rating
        $reviewList = $reviews[$rating];
        return $reviewList[array_rand($reviewList)];
    }
}
