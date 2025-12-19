<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Apply voucher code
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_value' => 'required|numeric|min:0',
            'seller_id' => 'nullable|exists:users,id',
            'shipping_fee' => 'nullable|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->code));
        $orderValue = $request->order_value;
        $sellerId = $request->seller_id;
        $shippingFee = $request->shipping_fee ?? 0;

        // Find voucher
        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'
            ], 404);
        }

        // Check if voucher is valid
        if (!$voucher->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn hoặc đã hết lượt sử dụng.'
            ], 400);
        }

        // Check if user can use this voucher
        if (!$voucher->canBeUsedBy(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng hết số lần cho phép của mã giảm giá này.'
            ], 400);
        }

        // Check if voucher applies to this seller
        if ($voucher->applies_to === 'seller' && $voucher->seller_id != $sellerId) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không áp dụng cho người bán này.'
            ], 400);
        }

        // Check minimum order value
        if ($orderValue < $voucher->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đủ giá trị tối thiểu ' . number_format($voucher->min_order_value) . ' VNĐ để áp dụng mã này.'
            ], 400);
        }

        // Calculate discount
        $discountAmount = $voucher->calculateDiscount($orderValue, $shippingFee);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'data' => [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'discount_amount' => $discountAmount,
            ]
        ]);
    }

    /**
     * Remove applied voucher
     */
    public function remove(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Đã hủy áp dụng mã giảm giá.'
        ]);
    }
}
