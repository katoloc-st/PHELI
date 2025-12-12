@extends('layouts.app')

@section('title', 'Xuất hóa đơn - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Xuất hóa đơn
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Quản lý và xuất hóa đơn cho các đơn hàng
                </h6>
            </div>
            <div class="col-auto">
                <!-- Button -->
                <a href="{{ route('orders.index') }}" class="btn btn-outline-light mr-2">
                    <i class="fas fa-arrow-left"></i> Quay lại đơn hàng
                </a>
            </div>
        </div>
        <!-- / .row -->
    </div>
    <!-- / .container -->
</section>

<section class="section-padding pt-0 user-pages-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <!-- Collapse -->
                @include('layouts.sidebar')
            </div>
            <div class="col-lg-9 col-md-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(isset($orders) && $orders->count() > 0)
                    <div class="row">
                        @foreach($orders as $order)
                            @php
                                // Tính trạng thái tổng hợp từ items
                                $allCompleted = $order->items->every(fn($item) => $item->status === 'completed');
                                $allCancelled = $order->items->every(fn($item) => $item->status === 'cancelled');
                                $hasProcessing = $order->items->contains(fn($item) => $item->status === 'processing');
                                $hasPending = $order->items->contains(fn($item) => $item->status === 'pending');

                                $badgeClass = 'badge-secondary';
                                $statusText = 'Hỗn hợp';

                                if ($allCompleted) {
                                    $badgeClass = 'badge-success';
                                    $statusText = 'Hoàn thành';
                                } elseif ($allCancelled) {
                                    $badgeClass = 'badge-danger';
                                    $statusText = 'Đã hủy';
                                } elseif ($hasProcessing) {
                                    $badgeClass = 'badge-info';
                                    $statusText = 'Đang xử lý';
                                } elseif ($hasPending) {
                                    $badgeClass = 'badge-warning';
                                    $statusText = 'Chờ xử lý';
                                }
                            @endphp
                            <div class="col-lg-12 col-md-12 mb-4">
                                <div class="card invoice-card">
                                    <div class="card-header bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-receipt text-primary"></i>
                                                    <strong>{{ $order->order_number }}</strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <small class="text-muted">
                                                    <i class="far fa-calendar"></i> {{ $order->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-2">Thông tin khách hàng</h6>
                                                <p class="mb-1"><strong>Họ tên:</strong> {{ $order->full_name }}</p>
                                                <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                                                <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                                                <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->full_address }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-2">Chi tiết đơn hàng</h6>
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Tổng sản phẩm:</span>
                                                        <strong>{{ $order->items->count() }} sản phẩm</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Tạm tính:</span>
                                                        <span>{{ number_format($order->subtotal) }} ₫</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Phí vận chuyển:</span>
                                                        <span>{{ number_format($order->shipping_total) }} ₫</span>
                                                    </div>
                                                    @if($order->discount_total > 0)
                                                    <div class="d-flex justify-content-between text-danger">
                                                        <span>Giảm giá:</span>
                                                        <span>-{{ number_format($order->discount_total) }} ₫</span>
                                                    </div>
                                                    @endif
                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Tổng cộng:</strong>
                                                        <strong class="text-success">{{ number_format($order->grand_total) }} ₫</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($allCompleted)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i> Đơn hàng đã hoàn thành
                                                    </span>
                                                @elseif($allCancelled)
                                                    <span class="text-danger">
                                                        <i class="fas fa-times-circle"></i> Đơn hàng đã hủy
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-info-circle"></i> Đơn hàng đang được xử lý
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="#" class="btn btn-sm btn-outline-primary" onclick="printInvoice({{ $order->id }})">
                                                    <i class="fas fa-print"></i> Xuất hóa đơn
                                                </a>
                                                <a href="#" class="btn btn-sm btn-primary" onclick="downloadInvoice({{ $order->id }})">
                                                    <i class="fas fa-download"></i> Tải PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                            <p class="text-muted mb-4">
                                Bạn chưa có đơn hàng nào để xuất hóa đơn.
                            </p>
                            <a href="{{ route('posts.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Mua sắm ngay
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-css')
<style>
.invoice-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.invoice-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.invoice-card .card-header {
    background-color: #f9fafb !important;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.25rem;
}

.invoice-card .card-body {
    padding: 1.5rem 1.25rem;
}

.invoice-card .card-footer {
    border-top: 1px solid #e5e7eb;
    padding: 1rem 1.25rem;
}

.badge {
    padding: 0.5em 0.75em;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-success {
    background-color: #10b981;
    color: white;
}

.badge-warning {
    background-color: #f59e0b;
    color: white;
}

.badge-info {
    background-color: #3b82f6;
    color: white;
}

.badge-danger {
    background-color: #ef4444;
    color: white;
}

.badge-secondary {
    background-color: #6b7280;
    color: white;
}

.text-primary {
    color: #6366f1 !important;
}

.text-success {
    color: #10b981 !important;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

.btn-outline-primary {
    color: #6366f1;
    border-color: #6366f1;
}

.btn-outline-primary:hover {
    color: white;
    background-color: #6366f1;
    border-color: #6366f1;
}

.btn-primary {
    background-color: #6366f1;
    border-color: #6366f1;
}

.btn-primary:hover {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

.btn-success {
    background-color: #10b981;
    border-color: #10b981;
}

.btn-success:hover {
    background-color: #059669;
    border-color: #059669;
}
</style>
@endsection

@section('custom-js')
<script>
// Auto hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);

// Print invoice function
function printInvoice(orderId) {
    // TODO: Implement print invoice functionality
    // You can open a print-friendly page or use browser print
    window.open('/invoice/' + orderId + '/print', '_blank');
    return false;
}

// Download invoice as PDF function
function downloadInvoice(orderId) {
    // TODO: Implement PDF download functionality
    // You can redirect to a PDF generation route
    window.location.href = '/invoice/' + orderId + '/pdf';
    return false;
}
</script>
@endsection
