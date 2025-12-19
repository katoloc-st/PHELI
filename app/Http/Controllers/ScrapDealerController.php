<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\WasteType;
use App\Models\Transaction;

class ScrapDealerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Thống kê tổng quát
        $totalPosts = Post::where('user_id', $user->id)->count();
        $activePosts = Post::where('user_id', $user->id)->where('status', 'active')->count();
        $totalTransactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->count();

        // Tính tổng giá trị giao dịch
        $totalRevenue = Transaction::where('seller_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Số đánh giá
        $totalReviews = \App\Models\Review::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Điểm trung bình
        $averageRating = \App\Models\Review::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->avg('rating');

        // Bài đăng gần đây
        $myPosts = Post::where('user_id', $user->id)
            ->with(['wasteType', 'collectionPoint'])
            ->latest()
            ->limit(5)
            ->get();

        // Giao dịch gần đây
        $myTransactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->with(['seller', 'buyer', 'post'])
            ->latest()
            ->limit(5)
            ->get();

        // Thống kê theo loại phế liệu
        $postsByWasteType = Post::where('user_id', $user->id)
            ->select('waste_type_id', \DB::raw('count(*) as total'))
            ->groupBy('waste_type_id')
            ->with('wasteType')
            ->get();

        // Thống kê theo tháng (6 tháng gần đây)
        $monthlyPosts = Post::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                \DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('scrap-dealer.dashboard', compact(
            'myPosts',
            'myTransactions',
            'totalPosts',
            'activePosts',
            'totalTransactions',
            'totalRevenue',
            'totalReviews',
            'averageRating',
            'postsByWasteType',
            'monthlyPosts'
        ));
    }

    public function posts()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->with('wasteType')->latest()->paginate(10);

        return view('scrap-dealer.posts', compact('posts'));
    }

    public function buyWaste()
    {
        // Hiển thị các bài đăng bán rác từ doanh nghiệp và phế liệu khác
        $posts = Post::where('type', 'sell')
            ->where('status', 'active')
            ->where('user_id', '!=', Auth::id())
            ->with(['wasteType', 'user'])
            ->latest()
            ->paginate(10);

        $wasteTypes = WasteType::where('is_active', true)->get();

        return view('scrap-dealer.buy', compact('posts', 'wasteTypes'));
    }

    public function inventory()
    {
        // Hiển thị tồn kho của phế liệu (dựa trên các giao dịch đã mua - đã bán)
        $user = Auth::user();

        // Tính toán tồn kho theo loại rác
        $wasteTypes = WasteType::where('is_active', true)->get();
        $inventory = [];

        foreach ($wasteTypes as $wasteType) {
            // Số lượng đã mua
            $bought = Transaction::where('buyer_id', $user->id)
                ->whereHas('post', function($query) use ($wasteType) {
                    $query->where('waste_type_id', $wasteType->id);
                })
                ->where('status', 'completed')
                ->sum('quantity');

            // Số lượng đã bán
            $sold = Transaction::where('seller_id', $user->id)
                ->whereHas('post', function($query) use ($wasteType) {
                    $query->where('waste_type_id', $wasteType->id);
                })
                ->where('status', 'completed')
                ->sum('quantity');

            $inventory[] = [
                'waste_type' => $wasteType,
                'quantity' => $bought - $sold
            ];
        }

        return view('scrap-dealer.inventory', compact('inventory'));
    }
}
