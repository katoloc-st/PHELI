<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.post', 'items.seller'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order (checkout page).
     */
    public function create()
    {
        return view('cart.checkout');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        try {
            // Parse order data from hidden field
            $orderData = json_decode($request->order_data, true);

            if (!$orderData) {
                return back()->with('error', 'Dữ liệu đơn hàng không hợp lệ!');
            }

            // Validate required fields
            if (empty($orderData['email']) || empty($orderData['phone']) || empty($orderData['full_name'])) {
                return back()->with('error', 'Vui lòng điền đầy đủ thông tin!');
            }

            if (empty($orderData['sellers']) || count($orderData['sellers']) === 0) {
                return back()->with('error', 'Giỏ hàng trống!');
            }

            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'full_name' => $orderData['full_name'],
                'address' => $orderData['address'],
                'apartment' => $orderData['apartment'] ?? null,
                'ward' => $orderData['ward'],
                'district' => $orderData['district'],
                'province' => $orderData['province'],
                'full_address' => $orderData['full_address'],
                'subtotal' => $orderData['subtotal'],
                'shipping_total' => $orderData['shipping_total'],
                'discount_total' => $orderData['discount_total'],
                'grand_total' => $orderData['grand_total'],
                'platform_discount_type' => $orderData['platform_discount_type'] ?? null,
                'platform_discount_value' => $orderData['platform_discount_value'] ?? null,
                'status' => 'pending',
            ]);

            // Create order items for each seller
            foreach ($orderData['sellers'] as $seller) {
                foreach ($seller['items'] as $item) {
                    // Verify post exists and is available
                    $post = Post::find($item['post_id']);
                    if (!$post) {
                        throw new \Exception("Sản phẩm không tồn tại!");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'post_id' => $item['post_id'],
                        'seller_id' => $seller['seller_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                        'shipping_method' => $seller['shipping_method'] ?? null,
                        'shipping_fee' => $seller['shipping_fee'] ?? 0,
                        'discount_type' => $seller['discount_type'] ?? null,
                        'discount_value' => $seller['discount_value'] ?? null,
                        'note' => $seller['note'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(string $id)
    {
        $order = Order::with(['items.post', 'items.seller'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Update the specified order status.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Cancel the order.
     */
    public function destroy(string $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xử lý!');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Đã hủy đơn hàng thành công!');
    }
}
