<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Lấy giỏ hàng của user hiện tại (API endpoint)
    public function getCart()
    {
        try {
            // Auth middleware đã bảo vệ route này rồi
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập',
                    'items' => [],
                    'total' => 0,
                    'count' => 0
                ]);
            }

            $cartItems = Cart::with(['post.wasteType', 'post.user', 'post.collectionPoint'])
                ->where('user_id', Auth::id())
                ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->post->price * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'items' => [],
                'total' => 0,
                'count' => 0
            ], 500);
        }
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'quantity' => 'integer|min:1'
        ]);

        $post = Post::findOrFail($request->post_id);

        // Không cho phép thêm bài đăng của chính mình vào giỏ hàng
        if ($post->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể mua bài đăng của chính mình!'
            ], 400);
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('post_id', $request->post_id)
            ->first();

        // Xác định số lượng và is_fixed_quantity dựa trên type của post
        // doanh_nghiep_xanh: thêm toàn bộ số lượng của bài đăng, không thể điều chỉnh
        // co_so_phe_lieu: thêm số lượng 1, có thể điều chỉnh
        $isFixedQuantity = $post->type === 'doanh_nghiep_xanh';
        $quantity = $isFixedQuantity ? $post->quantity : ($request->quantity ?? 1);

        if ($cartItem) {
            // Nếu đã có trong giỏ hàng
            if ($isFixedQuantity) {
                // Nếu là waste_company, không cho phép thêm lại
                return response()->json([
                    'success' => false,
                    'message' => 'Bài đăng này đã có trong giỏ hàng!'
                ], 400);
            } else {
                // Nếu là scrap_dealer, tăng số lượng
                $cartItem->quantity += $quantity;
                $cartItem->save();
            }
        } else {
            // Nếu chưa có, tạo mới
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
                'quantity' => $quantity,
                'is_fixed_quantity' => $isFixedQuantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng!',
            'cartCount' => Cart::where('user_id', Auth::id())->count()
        ]);
    }

    // Cập nhật số lượng
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Không cho phép cập nhật số lượng nếu is_fixed_quantity = true
        if ($cartItem->is_fixed_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi số lượng của bài đăng này!'
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cartItems = Cart::with(['post'])->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(function ($item) {
            return $item->post->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng!',
            'total' => $total,
            'subtotal' => $cartItem->post->price * $cartItem->quantity
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        $cartCount = Cart::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng!',
            'cartCount' => $cartCount
        ]);
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng!'
        ]);
    }

    // Trang xem giỏ hàng đầy đủ
    public function index()
    {
        $cartItems = Cart::with(['post.wasteType', 'post.user', 'post.collectionPoint'])
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->post->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }
}
