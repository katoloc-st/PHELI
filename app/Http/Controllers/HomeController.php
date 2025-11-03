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
        // Lấy danh sách loại rác
        $wasteTypes = WasteType::where('is_active', true)->orderBy('name')->get();

        // Lấy bảng giá hiện tại với tính toán thay đổi
        $currentPrices = PriceTable::with('wasteType')
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
            $query = Post::with(['wasteType', 'user'])
                ->where('status', 'active');

            // Tìm kiếm theo địa điểm
            if ($request->filled('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            // Tìm kiếm theo tỉnh thành
            if ($request->filled('province')) {
                $query->where('location', 'like', '%' . $request->province . '%');
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

        // Bài đăng gần đây
        $recentPosts = Post::with(['wasteType', 'user'])
            ->where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        // Thống kê
        $stats = [
            'total_posts' => Post::where('status', 'active')->count(),
            'total_transactions' => \App\Models\Transaction::where('status', 'completed')->count(),
            'total_users' => User::count(),
            'total_waste_types' => WasteType::where('is_active', true)->count(),
        ];

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
