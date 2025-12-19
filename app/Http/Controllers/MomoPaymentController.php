<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoPaymentController extends Controller
{
    /**
     * Create MoMo payment and return QR code
     */
    public function createPayment(Request $request)
    {
        try {
            // Parse order data
            $orderData = json_decode($request->order_data, true);

            if (!$orderData) {
                return response()->json(['error' => 'Dữ liệu đơn hàng không hợp lệ!'], 400);
            }

            // Validate required fields
            if (empty($orderData['email']) || empty($orderData['phone']) || empty($orderData['full_name'])) {
                return response()->json(['error' => 'Vui lòng điền đầy đủ thông tin!'], 400);
            }

            if (empty($orderData['sellers']) || count($orderData['sellers']) === 0) {
                return response()->json(['error' => 'Giỏ hàng trống!'], 400);
            }

            DB::beginTransaction();

            // Handle voucher if provided
            $voucherId = null;
            $voucher = null;
            if (!empty($orderData['voucher_id'])) {
                $voucher = \App\Models\Voucher::find($orderData['voucher_id']);
                if ($voucher) {
                    if ($voucher->isValid() && $voucher->canBeUsedBy(Auth::id())) {
                        $voucherId = $voucher->id;
                    }
                }
            }

            // Create order with 'pending_payment' status
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
                'payment_method' => 'momo',
                'voucher_id' => $voucherId,
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($orderData['sellers'] as $seller) {
                $sellerVoucherId = null;
                $sellerVoucher = null;
                $sellerDiscountAmount = $seller['discount_amount'] ?? 0;

                if (!empty($seller['voucher_id'])) {
                    $sellerVoucher = \App\Models\Voucher::find($seller['voucher_id']);
                    if ($sellerVoucher) {
                        if ($sellerVoucher->isValid() &&
                            $sellerVoucher->canBeUsedBy(Auth::id()) &&
                            ($sellerVoucher->applies_to === 'seller' && $sellerVoucher->seller_id == $seller['seller_id'])) {
                            $sellerVoucherId = $sellerVoucher->id;
                        }
                    }
                }

                $sellerSubtotal = 0;
                foreach ($seller['items'] as $item) {
                    $sellerSubtotal += $item['subtotal'];
                }

                foreach ($seller['items'] as $item) {
                    $post = Post::find($item['post_id']);
                    if (!$post) {
                        throw new \Exception("Sản phẩm không tồn tại!");
                    }

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

                // Track seller voucher usage
                if ($sellerVoucher && $sellerVoucherId) {
                    \App\Models\VoucherUsage::create([
                        'voucher_id' => $sellerVoucherId,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'discount_amount' => $seller['discount_amount'] ?? 0,
                    ]);
                    $sellerVoucher->increment('usage_count');
                }
            }

            // Track voucher usage
            if ($voucher && $voucherId) {
                \App\Models\VoucherUsage::create([
                    'voucher_id' => $voucherId,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $orderData['discount_total'] ?? 0,
                ]);
                $voucher->increment('usage_count');
            }

            DB::commit();

            // Generate MoMo QR code (simplified version - in production use actual MoMo API)
            $qrData = $this->generateMomoQR($order);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $order->grand_total,
                'qr_code' => $qrData['qr_code'],
                'payment_url' => $qrData['pay_url'] ?? route('momo.payment.show', $order->id),
                'redirect_now' => !empty($qrData['pay_url']) // Redirect if we have payUrl
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MoMo payment creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show payment QR code page
     */
    /**
     * MoMo return URL handler (user redirected here after payment)
     */
    public function returnUrl(Request $request)
    {
        $orderId = $request->orderId;
        $resultCode = $request->resultCode;

        if ($resultCode == 0) {
            // Payment successful - find order by momo_order_id
            $order = Order::where('momo_order_id', $orderId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'momo_trans_id' => $request->transId
                ]);

                // Clear cart
                \App\Models\Cart::where('user_id', $order->user_id)->delete();

                return redirect()->route('orders.index')->with('success', 'Thanh toán thành công! Đơn hàng của bạn đang được xử lý.');
            } else {
                return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng!');
            }
        } else {
            // Payment failed
            return redirect()->route('orders.index')->with('error', 'Thanh toán thất bại: ' . $request->message);
        }
    }

    /**
     * MoMo callback handler (webhook)
     */
    public function callback(Request $request)
    {
        try {
            // Verify signature
            $secretKey = env('MOMO_SECRET_KEY');

            $rawHash = "accessKey=" . $request->accessKey .
                       "&amount=" . $request->amount .
                       "&extraData=" . $request->extraData .
                       "&message=" . $request->message .
                       "&orderId=" . $request->orderId .
                       "&orderInfo=" . $request->orderInfo .
                       "&orderType=" . $request->orderType .
                       "&partnerCode=" . $request->partnerCode .
                       "&payType=" . $request->payType .
                       "&requestId=" . $request->requestId .
                       "&responseTime=" . $request->responseTime .
                       "&resultCode=" . $request->resultCode .
                       "&transId=" . $request->transId;

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($signature != $request->signature) {
                Log::error('MoMo callback signature mismatch');
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            // Extract order ID
            $extraData = json_decode(base64_decode($request->extraData), true);
            $orderId = $extraData['order_id'] ?? null;

            if (!$orderId) {
                Log::error('MoMo callback: Order ID not found');
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($request->resultCode == 0) {
                // Payment successful
                $order->update([
                    'payment_status' => 'paid',
                    'momo_trans_id' => $request->transId
                ]);

                // Clear cart
                \App\Models\Cart::where('user_id', $order->user_id)->delete();

                return response()->json(['message' => 'Success'], 200);
            } else {
                // Payment failed
                $order->update([
                    'payment_status' => 'failed'
                ]);

                return response()->json(['message' => 'Payment failed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('MoMo callback error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate MoMo QR code data
     * Call actual MoMo API
     */
    private function generateMomoQR($order)
    {
        $endpoint = env('MOMO_ENDPOINT') . '/v2/gateway/api/create';

        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');

        // Use existing momo_order_id if already set, otherwise create new one
        $orderId = $order->momo_order_id ?: (time() . "");
        $requestId = $orderId; // Same as orderId for simplicity
        $amount = (string)((int)$order->grand_total); // Convert to integer string (no decimal)
        $orderInfo = 'Thanh toan don hang ' . $order->order_number;
        $returnUrl = env('MOMO_RETURN_URL');
        $notifyUrl = env('MOMO_NOTIFY_URL');
        $extraData = "";
        $requestType = "payWithATM";

        // Create signature - EXACTLY like MoMo sample
        $rawHash = "accessKey=" . $accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $partnerCode .
                   "&redirectUrl=" . $returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Build data array - EXACTLY like MoMo sample
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        try {
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['resultCode']) && $jsonResult['resultCode'] == 0) {
                // Success - store momo order ID if not already set
                if (!$order->momo_order_id) {
                    $order->update(['momo_order_id' => $orderId]);
                    $order->refresh(); // Reload from database
                }

                return [
                    'qr_code' => $jsonResult['qrCodeUrl'] ?? null,
                    'deeplink' => $jsonResult['deeplink'] ?? null,
                    'pay_url' => $jsonResult['payUrl'] ?? null,
                    'amount' => $order->grand_total,
                    'order_number' => $order->order_number,
                    'request_id' => $requestId
                ];
            } else {
                // Error from MoMo
                $errorMsg = isset($jsonResult['message']) ? $jsonResult['message'] : 'Unknown error';

                // Return error to display
                return [
                    'qr_code' => null,
                    'pay_url' => null,
                    'amount' => $order->grand_total,
                    'order_number' => $order->order_number,
                    'error' => $errorMsg
                ];
            }
        } catch (\Exception $e) {
            Log::error('MoMo API Exception: ' . $e->getMessage());
            Log::error('Exception trace', ['trace' => $e->getTraceAsString()]);

            // Return error
            return [
                'qr_code' => null,
                'pay_url' => null,
                'amount' => $order->grand_total,
                'order_number' => $order->order_number,
                'error' => 'Lỗi kết nối: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Execute POST request to MoMo (same as sample code)
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Generate demo QR code (fallback)
     */
    private function generateDemoQR($order)
    {
        $qrContent = sprintf(
            "2|99|%s|%s|%s|0|0|%d",
            '0123456789',
            $order->full_name,
            $order->order_number,
            $order->grand_total
        );

        return [
            'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrContent),
            'amount' => $order->grand_total,
            'order_number' => $order->order_number,
            'phone' => '0123456789'
        ];
    }
}
