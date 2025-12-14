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
                'deposit_amount' => $orderData['deposit_amount'] ?? 0,
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
                $sellerDiscountAmount = $seller['discount_amount'] ?? 0;

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

                // Tính tổng subtotal của seller để chia discount theo tỷ lệ
                $sellerSubtotal = 0;
                foreach ($seller['items'] as $item) {
                    $sellerSubtotal += $item['subtotal'];
                }

                foreach ($seller['items'] as $item) {
                    // Verify post exists and is available
                    $post = Post::find($item['post_id']);
                    if (!$post) {
                        throw new \Exception("Sản phẩm không tồn tại!");
                    }

                    // Tính discount amount cho item này (chia theo tỷ lệ subtotal)
                    $itemDiscountAmount = 0;
                    if ($sellerDiscountAmount > 0 && $sellerSubtotal > 0) {
                        $itemDiscountAmount = round(($item['subtotal'] / $sellerSubtotal) * $sellerDiscountAmount, 2);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'post_id' => $item['post_id'],
                        'seller_id' => $seller['seller_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                        'deposit_amount' => $item['deposit_amount'] ?? 0,
                        'shipping_method' => $seller['shipping_method'] ?? null,
                        'shipping_fee' => $seller['shipping_fee'] ?? 0,
                        'discount_amount' => $itemDiscountAmount,
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

        // Cập nhật tất cả items đang pending thành cancelled (bởi người mua)
        $order->items()->where('status', 'pending')->update([
            'status' => 'cancelled',
            'cancelled_by' => 'buyer',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Đã hủy đơn hàng thành công!');
    }

    /**
     * Display sales/order requests for sellers
     */
    public function salesIndex(Request $request)
    {
        // Get order items where current user is the seller
        $query = OrderItem::where('seller_id', Auth::id())
            ->with(['post.wasteType', 'order.user', 'voucher']);

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'awaiting_pickup', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orderItems = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sales.index', compact('orderItems'));
    }

    /**
     * Update order item status
     */
    public function updateStatus(Request $request, $orderItemId)
    {
        $request->validate([
            'status' => 'required|in:awaiting_pickup,cancelled'
        ]);

        $orderItem = OrderItem::where('id', $orderItemId)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        // Only allow updating pending orders
        if ($orderItem->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể cập nhật đơn hàng đang chờ xử lý!');
        }

        $orderItem->status = $request->status;

        // Nếu hủy, lưu thông tin người hủy là seller
        if ($request->status === 'cancelled') {
            $orderItem->cancelled_by = 'seller';
            $orderItem->cancelled_at = now();
        }

        $orderItem->save();

        // Recalculate order totals after item cancellation
        if ($request->status === 'cancelled') {
            $this->recalculateOrderTotals($orderItem->order);
        }

        $message = $request->status === 'awaiting_pickup'
            ? 'Đã chuyển đơn hàng sang trạng thái Chờ lấy hàng!'
            : 'Đã hủy đơn hàng!';

        return back()->with('success', $message);
    }

    /**
     * Display delivery management page for delivery staff
     */
    public function deliveryIndex(Request $request)
    {
        // Get order items that are awaiting_pickup, processing, completed or cancelled
        $query = OrderItem::whereIn('status', ['awaiting_pickup', 'processing', 'completed', 'cancelled'])
            ->with(['post.wasteType', 'order.user', 'seller']);

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['awaiting_pickup', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orderItems = $query->orderBy('order_id', 'asc')
            ->orderBy('seller_id', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('delivery.index', compact('orderItems'));
    }

    /**
     * Update delivery status
     */
    public function updateDeliveryStatus(Request $request, $orderItemId)
    {
        $request->validate([
            'status' => 'required|in:processing,completed,cancelled'
        ]);

        $orderItem = OrderItem::findOrFail($orderItemId);

        // Validate status transitions
        if ($request->status === 'processing' && $orderItem->status !== 'awaiting_pickup') {
            return back()->with('error', 'Chỉ có thể chuyển sang Chờ giao hàng từ trạng thái Chờ lấy hàng!');
        }

        if ($request->status === 'completed' && $orderItem->status !== 'processing') {
            return back()->with('error', 'Chỉ có thể hoàn thành đơn hàng từ trạng thái Chờ giao hàng!');
        }

        $orderItem->status = $request->status;

        // Nếu hủy, xác định người hủy dựa vào trạng thái hiện tại
        if ($request->status === 'cancelled') {
            // Nếu đang ở trạng thái chờ lấy hàng -> hủy do người bán
            if ($orderItem->status === 'awaiting_pickup') {
                $orderItem->cancelled_by = 'seller';
            }
            // Nếu đang ở trạng thái chờ giao hàng -> hủy do người mua
            elseif ($orderItem->status === 'processing') {
                $orderItem->cancelled_by = 'buyer';
            }
            // Trường hợp khác (nếu có) -> ghi nhận là delivery
            else {
                $orderItem->cancelled_by = 'delivery';
            }
            $orderItem->cancelled_at = now();
        }

        $orderItem->save();

        // Recalculate order totals after item cancellation
        if ($request->status === 'cancelled') {
            $this->recalculateOrderTotals($orderItem->order);
        }

        $messages = [
            'processing' => 'Đã chuyển sang trạng thái Chờ giao hàng!',
            'completed' => 'Đã hoàn thành giao hàng!',
            'cancelled' => 'Đã hủy đơn hàng!'
        ];

        return back()->with('success', $messages[$request->status]);
    }

    /**
     * Recalculate order totals based on active (non-cancelled) items
     */
    private function recalculateOrderTotals($order)
    {
        // Get active items only (not cancelled)
        $activeItems = $order->orderItems()->where('status', '!=', 'cancelled')->get();

        // Recalculate subtotal from active items
        $newSubtotal = $activeItems->sum(function($item) {
            return $item->quantity * $item->price_per_unit;
        });

        // Recalculate discount if voucher exists
        $newDiscount = 0;
        if ($order->voucher_id) {
            $voucher = $order->voucher;
            if ($voucher) {
                if ($voucher->discount_type === 'percentage') {
                    $newDiscount = ($newSubtotal * $voucher->discount_value) / 100;
                    if ($voucher->max_discount_amount) {
                        $newDiscount = min($newDiscount, $voucher->max_discount_amount);
                    }
                } else {
                    $newDiscount = min($voucher->discount_value, $newSubtotal);
                }
            }
        }

        // Recalculate deposit from active items
        $newDeposit = $activeItems->sum('deposit_amount');

        // Shipping remains the same
        $shippingTotal = $order->shipping_total;

        // Calculate new grand total
        $newGrandTotal = $newSubtotal + $shippingTotal - $newDiscount + $newDeposit;

        // Update order totals
        $order->update([
            'subtotal' => $newSubtotal,
            'discount_total' => $newDiscount,
            'deposit_amount' => $newDeposit,
            'grand_total' => $newGrandTotal
        ]);
    }
}

