<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background: white;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #6366f1;
        }

        .company-info h1 {
            color: #6366f1;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .company-info p {
            color: #666;
            font-size: 14px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 16px;
            color: #666;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .detail-section {
            flex: 1;
            max-width: 48%;
        }

        .detail-section h3 {
            color: #6366f1;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .detail-section p {
            margin-bottom: 5px;
            font-size: 14px;
            word-wrap: break-word;
            line-height: 1.6;
        }

        .detail-section strong {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background: #6366f1;
            color: white;
        }

        thead th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        tbody td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-description {
            color: #666;
            font-size: 12px;
        }

        .summary {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .summary-table {
            width: 300px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #10b981;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: 500;
        }

        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .item-status {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 4px;
        }

        .item-cancelled {
            text-decoration: line-through;
            opacity: 0.6;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #6366f1;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .print-button:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>PHELIEU</h1>
                <p>Hệ thống quản lý phế liệu</p>
                <p>Email: support@phelieu.com</p>
                <p>Hotline: 1900-xxxx</p>
            </div>
            <div class="invoice-title">
                <h2>HÓA ĐƠN</h2>
                <p class="invoice-number">{{ $order->order_number }}</p>
                <p class="invoice-number">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Họ tên:</strong> {{ $order->full_name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->full_address }}</p>
            </div>
            <div class="detail-section">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</p>
                <p>
                    <strong>Trạng thái:</strong>
                    @php
                        $allCompleted = $order->items->every(fn($item) => $item->status === 'completed');
                        $allCancelled = $order->items->every(fn($item) => $item->status === 'cancelled');
                        $hasProcessing = $order->items->contains(fn($item) => $item->status === 'processing');
                        $hasAwaitingPickup = $order->items->contains(fn($item) => $item->status === 'awaiting_pickup');
                        $hasPending = $order->items->contains(fn($item) => $item->status === 'pending');

                        if ($allCompleted) {
                            $badgeClass = 'badge-success';
                            $statusText = 'Hoàn thành';
                        } elseif ($allCancelled) {
                            $badgeClass = 'badge-danger';
                            $statusText = 'Đã hủy';
                        } elseif ($hasProcessing) {
                            $badgeClass = 'badge-info';
                            $statusText = 'Chờ giao hàng';
                        } elseif ($hasAwaitingPickup) {
                            $badgeClass = 'badge-primary';
                            $statusText = 'Chờ lấy hàng';
                        } elseif ($hasPending) {
                            $badgeClass = 'badge-warning';
                            $statusText = 'Chờ xử lý';
                        } else {
                            $badgeClass = 'badge-secondary';
                            $statusText = 'Hỗn hợp';
                        }
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                </p>
            </div>
        </div>

        <!-- Order Items Table -->
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Sản phẩm</th>
                    <th width="15%" class="text-center">Trạng thái</th>
                    <th width="10%" class="text-center">Số lượng</th>
                    <th width="15%" class="text-right">Đơn giá</th>
                    <th width="20%" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                @php
                    $itemBadgeClass = match($item->status) {
                        'completed' => 'badge-success',
                        'awaiting_pickup' => 'badge-primary',
                        'processing' => 'badge-info',
                        'cancelled' => 'badge-danger',
                        default => 'badge-warning'
                    };

                    $itemStatusText = match($item->status) {
                        'completed' => 'Hoàn thành',
                        'awaiting_pickup' => 'Chờ lấy hàng',
                        'processing' => 'Chờ giao hàng',
                        'cancelled' => 'Đã hủy',
                        default => 'Chờ xử lý'
                    };

                    $isCancelled = $item->status === 'cancelled';
                @endphp
                <tr class="{{ $isCancelled ? 'item-cancelled' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $item->post->title }}</div>
                        <div class="item-description">
                            Loại: {{ $item->post->wasteType->name }} |
                            Người bán: {{ $item->post->user->company_name ?? $item->post->user->name }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="status-badge item-status {{ $itemBadgeClass }}">{{ $itemStatusText }}</span>
                    </td>
                    <td class="text-center">{{ number_format($item->quantity) }} kg</td>
                    <td class="text-right">{{ number_format($item->price) }} ₫/kg</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity) }} ₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        @php
            // Tính tạm tính: tổng tiền các item có trạng thái khác hủy
            $nonCancelledItems = $order->items->filter(fn($item) => $item->status !== 'cancelled');
            $recalculatedSubtotal = $nonCancelledItems->sum(fn($item) => $item->price * $item->quantity);

            // Lấy các items đang hoạt động (processing/completed) cho discount
            $activeItems = $order->items->filter(fn($item) => in_array($item->status, ['processing', 'completed']));

            // Tính phí ship: kiểm tra items bị hủy
            $cancelledItems = $order->items->filter(fn($item) => $item->status === 'cancelled');
            $recalculatedShipping = $order->shipping_total;

            foreach ($cancelledItems as $cancelledItem) {
                // Kiểm tra xem có item khác cùng order_id và seller_id không (ghép đơn)
                $hasSameSellerItem = $order->items->contains(function($item) use ($cancelledItem) {
                    return $item->id !== $cancelledItem->id
                        && $item->seller_id === $cancelledItem->seller_id
                        && in_array($item->status, ['pending', 'processing', 'completed']);
                });

                // Nếu không có item khác cùng seller (không phải ghép đơn), trừ phí ship
                if (!$hasSameSellerItem) {
                    $recalculatedShipping -= $cancelledItem->shipping_fee;
                }
            }

            // Tính discount: dùng trực tiếp từ order (đã được cập nhật khi có hủy)
            $recalculatedDiscount = $order->discount_total;

            // Tính deposit: dùng trực tiếp từ order (đã được cập nhật khi có hủy)
            $recalculatedDeposit = $order->deposit_amount;

            $recalculatedGrandTotal = $recalculatedSubtotal + $recalculatedShipping - $recalculatedDiscount + $recalculatedDeposit;
        @endphp
        <div class="summary">
            <div class="summary-table">
                <div class="summary-row" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; margin-bottom: 8px;">
                    <span class="summary-label">Tổng sản phẩm:</span>
                    <span class="summary-value">
                        {{ $order->items->count() }} sản phẩm
                        @if($cancelledItems->count() > 0)
                            <span style="color: #ef4444; font-size: 12px; font-weight: normal;">({{ $cancelledItems->count() }} đã hủy)</span>
                        @endif
                    </span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Tạm tính:</span>
                    <span class="summary-value">{{ number_format($recalculatedSubtotal) }} ₫</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển:</span>
                    <span class="summary-value">{{ number_format($recalculatedShipping) }} ₫</span>
                </div>
                @if($recalculatedDiscount > 0)
                <div class="summary-row">
                    <span class="summary-label">Giảm giá:</span>
                    <span class="summary-value" style="color: #ef4444;">-{{ number_format($recalculatedDiscount) }} ₫</span>
                </div>
                @endif
                @if($recalculatedDeposit > 0)
                <div class="summary-row">
                    <span class="summary-label">Đặt cọc:</span>
                    <span class="summary-value" style="color: #f59e0b;">{{ number_format($recalculatedDeposit) }} ₫</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span class="summary-label">Tổng cộng:</span>
                    <span class="summary-value">{{ number_format($recalculatedGrandTotal) }} ₫</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn bạn đã sử dụng dịch vụ của PHELIEU!</p>
            <p>Đây là hóa đơn được tạo tự động, không cần chữ ký và con dấu.</p>
        </div>
    </div>

    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> In hóa đơn
    </button>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
