<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteType;
use App\Models\PriceTable;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Cache các data tĩnh 1 giờ
        $wasteTypes = cache()->remember('waste_types_active', 3600, function() {
            return WasteType::where('is_active', true)->orderBy('name')->get();
        });

        // Cache bảng giá hiện tại 30 phút
        $currentPrices = cache()->remember('current_prices', 1800, function() {
            return PriceTable::with('wasteType')
                ->where('is_active', true)
                ->where('effective_date', '<=', now())
                ->where(function($query) {
                    $query->whereNull('expired_date')
                          ->orWhere('expired_date', '>=', now());
                })
                ->orderBy('effective_date', 'desc')
                ->get()
                ->groupBy('waste_type_id')
                ->map(function($group) {
                    return $group->first();
                });
        });

        // Tính toán % thay đổi so với hôm qua (giả lập)
        $priceChanges = [];
        foreach ($currentPrices as $priceTable) {
            // Giả lập tỷ lệ thay đổi ngẫu nhiên từ -3% đến +3%
            $changePercent = rand(-300, 300) / 100;
            $priceChanges[$priceTable->waste_type_id] = $changePercent;
        }

                // Tìm kiếm bài đăng
        $posts = collect();
        $hasSearchParams = $request->filled('location') ||
                          $request->filled('province') ||
                          $request->filled('waste_category');

        if ($hasSearchParams) {
            $query = Post::with(['wasteType', 'user', 'collectionPoint'])
                ->where('status', 'active');

            // Tìm kiếm theo địa điểm (tìm trong collection_points)
            if ($request->filled('location')) {
                $query->whereHas('collectionPoint', function($q) use ($request) {
                    $q->where('address', 'like', '%' . $request->location . '%')
                      ->orWhere('district', 'like', '%' . $request->location . '%')
                      ->orWhere('province', 'like', '%' . $request->location . '%');
                });
            }

            // Tìm kiếm theo tỉnh thành
            if ($request->filled('province')) {
                $query->whereHas('collectionPoint', function($q) use ($request) {
                    $q->where('province', 'like', '%' . $request->province . '%');
                });
            }

            // Tìm kiếm theo loại phế liệu
            if ($request->filled('waste_category')) {
                $wasteType = WasteType::where('name', $request->waste_category)->first();
                if ($wasteType) {
                    $query->where('waste_type_id', $wasteType->id);
                }
            }

            $posts = $query->latest()->get();
        }

        // Cache bài đăng gần đây 5 phút
        $recentPosts = cache()->remember('recent_posts', 300, function() {
            return Post::with(['wasteType', 'user', 'collectionPoint'])
                ->where('status', 'active')
                ->latest()
                ->limit(6)
                ->get();
        });

        // Cache thống kê 10 phút
        $stats = cache()->remember('home_stats', 600, function() {
            return [
                'total_posts' => Post::where('status', 'active')->count(),
                'total_transactions' => \App\Models\Transaction::where('status', 'completed')->count(),
                'total_users' => User::count(),
                'total_waste_types' => WasteType::where('is_active', true)->count(),
            ];
        });

        return view('index', compact(
            'wasteTypes',
            'currentPrices',
            'priceChanges',
            'posts',
            'recentPosts',
            'stats'
        ));
    }
}
