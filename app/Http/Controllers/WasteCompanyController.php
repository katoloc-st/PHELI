<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\WasteType;
use App\Models\Transaction;

class WasteCompanyController extends Controller
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

        return view('waste-company.dashboard', compact('myPosts', 'myTransactions'));
    }

    public function posts()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->with('wasteType')->latest()->paginate(10);

        return view('waste-company.posts', compact('posts'));
    }

    public function buyWaste()
    {
        // Hiển thị các bài đăng bán rác từ phế liệu và doanh nghiệp khác
        $posts = Post::where('type', 'sell')
            ->where('status', 'active')
            ->where('user_id', '!=', Auth::id())
            ->with(['wasteType', 'user'])
            ->latest()
            ->paginate(10);

        $wasteTypes = WasteType::where('is_active', true)->get();

        return view('waste-company.buy', compact('posts', 'wasteTypes'));
    }
}
