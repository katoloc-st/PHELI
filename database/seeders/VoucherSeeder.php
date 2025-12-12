<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\User;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một số user làm seller (role = seller hoặc scrap_dealer)
        $sellers = User::whereIn('role', ['seller', 'scrap_dealer'])->take(3)->get();

        // 1. Voucher sàn - Giảm 10%
        Voucher::create([
            'code' => 'PHELIEU10',
            'name' => 'Giảm 10% cho đơn hàng',
            'description' => 'Áp dụng cho tất cả đơn hàng từ 500.000đ',
            'type' => 'percent',
            'value' => 10,
            'min_order_value' => 500000,
            'max_discount' => 100000, // Giảm tối đa 100k
            'usage_limit' => 100,
            'usage_count' => 0,
            'per_user_limit' => 3,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(3),
        ]);

        // 2. Voucher sàn - Giảm 50k cố định
        Voucher::create([
            'code' => 'GIAM50K',
            'name' => 'Giảm 50.000đ',
            'description' => 'Giảm 50.000đ cho đơn hàng từ 200.000đ',
            'type' => 'fixed',
            'value' => 50000,
            'min_order_value' => 200000,
            'max_discount' => null,
            'usage_limit' => 50,
            'usage_count' => 0,
            'per_user_limit' => 1,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(1),
        ]);

        // 3. Voucher sàn - Giảm 20% không giới hạn
        Voucher::create([
            'code' => 'KHUYENMAI20',
            'name' => 'Khuyến mãi 20%',
            'description' => 'Giảm 20% cho mọi đơn hàng, giảm tối đa 200k',
            'type' => 'percent',
            'value' => 20,
            'min_order_value' => 0,
            'max_discount' => 200000,
            'usage_limit' => null, // Không giới hạn số lần dùng
            'usage_count' => 0,
            'per_user_limit' => 5,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(6),
        ]);

        // 3.1 Voucher sàn - Freeship
        Voucher::create([
            'code' => 'FREESHIP',
            'name' => 'Miễn phí vận chuyển',
            'description' => 'Miễn phí vận chuyển cho đơn hàng từ 300.000đ',
            'type' => 'freeship',
            'value' => 0, // Không cần value cho freeship
            'min_order_value' => 300000,
            'max_discount' => null,
            'usage_limit' => 100,
            'usage_count' => 0,
            'per_user_limit' => 5,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(3),
        ]);

        // 4. Voucher của người bán (nếu có sellers)
        if ($sellers->count() > 0) {
            foreach ($sellers as $index => $seller) {
                // Voucher giảm % của shop
                Voucher::create([
                    'code' => 'SHOP' . $seller->id . 'SALE15',
                    'name' => 'Giảm 15% từ ' . $seller->name,
                    'description' => 'Giảm 15% cho đơn hàng từ shop này, tối thiểu 100k',
                    'type' => 'percent',
                    'value' => 15,
                    'min_order_value' => 100000,
                    'max_discount' => 150000,
                    'usage_limit' => 30,
                    'usage_count' => 0,
                    'per_user_limit' => 2,
                    'applies_to' => 'seller',
                    'seller_id' => $seller->id,
                    'is_active' => true,
                    'starts_at' => Carbon::now(),
                    'expires_at' => Carbon::now()->addMonths(2),
                ]);

                // Voucher giảm tiền cố định của shop
                Voucher::create([
                    'code' => 'SHOP' . $seller->id . 'DISCOUNT',
                    'name' => 'Giảm 30.000đ từ ' . $seller->name,
                    'description' => 'Giảm 30.000đ cho đơn hàng từ shop này',
                    'type' => 'fixed',
                    'value' => 30000,
                    'min_order_value' => 150000,
                    'max_discount' => null,
                    'usage_limit' => 20,
                    'usage_count' => 0,
                    'per_user_limit' => 1,
                    'applies_to' => 'seller',
                    'seller_id' => $seller->id,
                    'is_active' => true,
                    'starts_at' => Carbon::now(),
                    'expires_at' => Carbon::now()->addMonth(),
                ]);

                // Voucher freeship của shop
                Voucher::create([
                    'code' => 'SHOP' . $seller->id . 'FREESHIP',
                    'name' => 'Miễn phí ship từ ' . $seller->name,
                    'description' => 'Miễn phí vận chuyển cho đơn hàng từ shop này',
                    'type' => 'freeship',
                    'value' => 0,
                    'min_order_value' => 200000,
                    'max_discount' => null,
                    'usage_limit' => 50,
                    'usage_count' => 0,
                    'per_user_limit' => 3,
                    'applies_to' => 'seller',
                    'seller_id' => $seller->id,
                    'is_active' => true,
                    'starts_at' => Carbon::now(),
                    'expires_at' => Carbon::now()->addMonths(2),
                ]);
            }
        }

        // 5. Voucher hết hạn (để test validation)
        Voucher::create([
            'code' => 'EXPIRED',
            'name' => 'Voucher đã hết hạn',
            'description' => 'Voucher này đã hết hạn để test',
            'type' => 'percent',
            'value' => 50,
            'min_order_value' => 0,
            'max_discount' => 500000,
            'usage_limit' => 10,
            'usage_count' => 0,
            'per_user_limit' => 1,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now()->subMonths(2),
            'expires_at' => Carbon::now()->subMonth(), // Đã hết hạn
        ]);

        // 6. Voucher đã hết lượt sử dụng (để test validation)
        Voucher::create([
            'code' => 'SOLDOUT',
            'name' => 'Voucher đã hết lượt',
            'description' => 'Voucher này đã hết lượt sử dụng để test',
            'type' => 'fixed',
            'value' => 100000,
            'min_order_value' => 0,
            'max_discount' => null,
            'usage_limit' => 5,
            'usage_count' => 5, // Đã dùng hết
            'per_user_limit' => 1,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // 7. Voucher chưa đến ngày áp dụng (để test validation)
        Voucher::create([
            'code' => 'COMINGSOON',
            'name' => 'Voucher chưa đến ngày áp dụng',
            'description' => 'Voucher này chưa đến ngày áp dụng để test',
            'type' => 'percent',
            'value' => 25,
            'min_order_value' => 0,
            'max_discount' => 300000,
            'usage_limit' => 50,
            'usage_count' => 0,
            'per_user_limit' => 2,
            'applies_to' => 'all',
            'is_active' => true,
            'starts_at' => Carbon::now()->addDays(7), // Bắt đầu sau 7 ngày
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // 8. Voucher không kích hoạt (để test validation)
        Voucher::create([
            'code' => 'INACTIVE',
            'name' => 'Voucher chưa kích hoạt',
            'description' => 'Voucher này chưa được kích hoạt để test',
            'type' => 'percent',
            'value' => 30,
            'min_order_value' => 0,
            'max_discount' => 200000,
            'usage_limit' => 100,
            'usage_count' => 0,
            'per_user_limit' => 3,
            'applies_to' => 'all',
            'is_active' => false, // Chưa kích hoạt
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(2),
        ]);

        $this->command->info('Đã tạo ' . Voucher::count() . ' vouchers thành công!');
    }
}
