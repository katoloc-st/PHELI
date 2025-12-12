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
     * Display a listing of the user's order items (purchased posts).
     */
    public function index(Request $request)
    {
        // Query order items của user thông qua orders
        $query = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['post.wasteType', 'seller', 'order']);

        // Filter theo status nếu có
        if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orderItems = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orderItems'));
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

            // Handle voucher if provided
            $voucherId = null;
            $voucher = null;
            if (!empty($orderData['voucher_id'])) {
                $voucher = \App\Models\Voucher::find($orderData['voucher_id']);
                if ($voucher) {
                    // Verify voucher is still valid
                    if ($voucher->isValid() && $voucher->canBeUsedBy(Auth::id())) {
                        $voucherId = $voucher->id;
                    }
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'full_name' => $orderData['full_name'],
                'address' => $orderData['address'],
                'notes' => $orderData['notes'] ?? null,
                'ward' => $orderData['ward'],
                'district' => $orderData['district'],
                'province' => $orderData['province'],
                'full_address' => $orderData['full_address'],
                'latitude' => $orderData['latitude'] ?? null,
                'longitude' => $orderData['longitude'] ?? null,
                'subtotal' => $orderData['subtotal'],
                'shipping_total' => $orderData['shipping_total'],
                'discount_total' => $orderData['discount_total'],
                'grand_total' => $orderData['grand_total'],
                'payment_method' => $orderData['payment_method'] ?? 'cod',
                'voucher_id' => $voucherId,
                'status' => 'pending',
            ]);

            // Create order items for each seller
            foreach ($orderData['sellers'] as $seller) {
                // Validate seller voucher if provided
                $sellerVoucherId = null;
                $sellerVoucher = null;
                if (!empty($seller['voucher_id'])) {
                    $sellerVoucher = \App\Models\Voucher::find($seller['voucher_id']);
                    if ($sellerVoucher) {
                        // Verify voucher is still valid and belongs to this seller
                        if ($sellerVoucher->isValid() &&
                            $sellerVoucher->canBeUsedBy(Auth::id()) &&
                            ($sellerVoucher->applies_to === 'seller' && $sellerVoucher->seller_id == $seller['seller_id'])) {
                            $sellerVoucherId = $sellerVoucher->id;
                        }
                    }
                }

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
                        'note' => $seller['note'] ?? null,
                        'voucher_id' => $sellerVoucherId,
                    ]);
                }

                // Track seller voucher usage if applied
                if ($sellerVoucher && $sellerVoucherId) {
                    // Create voucher usage record for seller voucher
                    \App\Models\VoucherUsage::create([
                        'voucher_id' => $sellerVoucherId,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'discount_amount' => $seller['discount_amount'] ?? 0,
                    ]);

                    // Increment voucher usage count
                    $sellerVoucher->increment('usage_count');
                }
            }

            // Track voucher usage if applied
            if ($voucher && $voucherId) {
                // Create voucher usage record
                \App\Models\VoucherUsage::create([
                    'voucher_id' => $voucherId,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $orderData['discount_total'] ?? 0,
                ]);

                // Increment voucher usage count
                $voucher->increment('usage_count');
            }

            // Clear cart after successful order
            \App\Models\Cart::where('user_id', Auth::id())->delete();

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
     * Cancel the order - updates all order items to cancelled status.
     */
    public function destroy(string $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        // Kiểm tra xem có item nào đang chờ xử lý không
        $hasPendingItems = $order->items()->where('status', 'pending')->exists();

        if (!$hasPendingItems) {
            return back()->with('error', 'Không có sản phẩm nào đang chờ xử lý để hủy!');
        }

        // Cập nhật tất cả items đang pending thành cancelled
        $order->items()->where('status', 'pending')->update(['status' => 'cancelled']);

        return back()->with('success', 'Đã hủy đơn hàng thành công!');
    }
}
