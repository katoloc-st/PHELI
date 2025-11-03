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
        $myPosts = Post::where('user_id', $user->id)->latest()->limit(5)->get();
        $myTransactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('scrap-dealer.dashboard', compact('myPosts', 'myTransactions'));
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
