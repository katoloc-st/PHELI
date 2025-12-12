@extends('layouts.app')

@section('title', 'Quản lý đơn hàng - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Quản lý đơn hàng
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Danh sách các đơn hàng phế liệu của bạn
                </h6>
            </div>
            <div class="col-auto">
                <!-- Filter Buttons -->
                <a href="{{ route('orders.index') }}" class="btn {{ !request('status') ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-list"></i> Tất cả
                </a>
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-clock"></i> Chờ xử lý
                </a>
                <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="btn {{ request('status') == 'processing' ? 'btn-info' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-sync"></i> Đang xử lý
                </a>
                <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-success' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-check"></i> Hoàn thành
                </a>
                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="btn {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-times"></i> Đã hủy
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

                @if($orderItems->count() > 0)
                            <div class="row">
                                @foreach($orderItems as $item)
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card card-list card-list-view clickable-card" data-url="{{ route('posts.show', $item->post_id) }}">
                                            <div class="row no-gutters">
                                                <div class="col-lg-5 col-md-5">
                                                    @php
                                                        $badgeClass = match($item->status) {
                                                            'completed' => 'badge-success',
                                                            'processing' => 'badge-info',
                                                            'cancelled' => 'badge-danger',
                                                            default => 'badge-warning'
                                                        };

                                                        $statusText = match($item->status) {
                                                            'completed' => 'Hoàn thành',
                                                            'processing' => 'Đang xử lý',
                                                            'cancelled' => 'Đã hủy',
                                                            default => 'Chờ xử lý'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                    @if($item->post && $item->post->images)
                                                        @php
                                                            $images = is_string($item->post->images) ? json_decode($item->post->images, true) : $item->post->images;
                                                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                                        @endphp
                                                        @if($firstImage)
                                                            <img class="card-img-top" src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->post->title }}">
                                                        @else
                                                            <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image">
                                                        @endif
                                                    @else
                                                        <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image">
                                                    @endif
                                                </div>
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="card-body">
                                                        @if($item->status === 'pending')
                                                            <form action="{{ route('orders.destroy', $item->order_id) }}" method="POST" class="float-right cancel-order-form" onsubmit="return confirm('Bạn có chắc muốn hủy sản phẩm này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                                                    <i class="fas fa-times"></i> Hủy
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <h5 class="card-title">{{ $item->post->title ?? 'Sản phẩm đã bị xóa' }}</h5>
                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                            <i class="mdi mdi-store"></i> {{ $item->seller->company_name ?? $item->seller->name }}
                                                        </h6>
                                                        @php
                                                            $hasDiscount = $item->discount_value && $item->discount_value > 0;
                                                            $originalTotal = $item->price * $item->quantity;
                                                        @endphp
                                                        @if($hasDiscount)
                                                            <div class="mt-3">
                                                                <h4 class="text-muted mb-1" style="text-decoration: line-through;">
                                                                    {{ number_format($originalTotal) }} <small>VNĐ</small>
                                                                </h4>
                                                                <h2 class="text-danger mb-0">
                                                                    {{ number_format($item->subtotal) }} <small>VNĐ</small>
                                                                </h2>
                                                            </div>
                                                        @else
                                                            <h2 class="text-success mb-0 mt-3">
                                                                {{ number_format($item->subtotal) }} <small>VNĐ</small>
                                                            </h2>
                                                        @endif
                                                        <p class="text-muted mt-2 mb-0">
                                                            <small>Đơn giá: {{ number_format($item->price) }} VNĐ/kg × {{ number_format($item->quantity, 2) }} kg</small>
                                                            @if($hasDiscount)
                                                                <br>
                                                                <small class="text-danger">
                                                                    <i class="fas fa-tag"></i>
                                                                    @if($item->discount_type === 'percentage')
                                                                        Giảm {{ $item->discount_value }}%
                                                                    @else
                                                                        Giảm {{ number_format($item->discount_value) }} VNĐ
                                                                    @endif
                                                                </small>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <span><i class="mdi mdi-credit-card"></i> Phương thức thanh toán : <strong>{{ $item->order->payment_method ?? 'Tiền mặt' }}</strong></span>
                                                        @php
                                                            $orderDate = $item->created_at;
                                                            $shippingMethod = $item->shipping_method ?? 'standard';

                                                            // Tính ngày giao dựa trên phương thức vận chuyển
                                                            switch($shippingMethod) {
                                                                case 'super-express': // Hỏa tốc: 2-3 ngày
                                                                    $minDays = 2;
                                                                    $maxDays = 3;
                                                                    break;
                                                                case 'express': // Nhanh: 5-7 ngày
                                                                    $minDays = 5;
                                                                    $maxDays = 7;
                                                                    break;
                                                                case 'standard': // Tiêu chuẩn: 10-15 ngày
                                                                default:
                                                                    $minDays = 10;
                                                                    $maxDays = 15;
                                                                    break;
                                                            }

                                                            $deliveryStartDate = $orderDate->copy()->addDays($minDays);
                                                            $deliveryEndDate = $orderDate->copy()->addDays($maxDays);
                                                            $deliveryRange = $deliveryStartDate->format('d/m') . ' - ' . $deliveryEndDate->format('d/m');
                                                        @endphp
                                                        <span><i class="mdi mdi-calendar"></i> Ngày giao hàng : <strong>{{ $deliveryRange }}</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        <!-- Pagination -->
                        @if($orderItems->hasPages())
                            {{ $orderItems->links('pagination.custom') }}
                        @endif
                    @else
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                                        <p class="text-muted mb-4">
                                            Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!
                                        </p>
                                        <a href="{{ route('posts.index') }}" class="btn btn-primary">
                                            <i class="fas fa-shopping-cart"></i> Mua sắm ngay
                                        </a>
                                    </div>
                                </div>
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
/* Filter buttons styling */
.btn-outline-light {
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-outline-light:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
}

.btn-light {
    background-color: #fff;
    border-color: #fff;
    color: #343a40;
}

.btn-warning, .btn-info, .btn-success, .btn-danger {
    color: #fff;
}

/* Clickable card styles */
.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Badge styles */
.badge {
    padding: 0.5em 0.75em;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-warning {
    background-color: #fbbf24;
    color: #78350f;
}

.badge-info {
    background-color: #60a5fa;
    color: #1e3a8a;
}

.badge-success {
    background-color: #34d399;
    color: #064e3b;
}

.badge-danger {
    background-color: #f87171;
    color: #7f1d1d;
}

/* Card image styling */
.card-img-top {
    height: 250px;
    object-fit: cover;
}

/* Cancel button styling */
.cancel-order-form {
    z-index: 10;
    position: relative;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
    background-color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Discount price styling */
.text-danger {
    color: #dc3545 !important;
}

h4.text-muted {
    font-size: 1.2rem;
    opacity: 0.6;
}
</style>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
    // Make cards clickable
    $('.clickable-card').on('click', function(e) {
        // Don't navigate if clicking on cancel button or form
        if ($(e.target).closest('.cancel-order-form').length === 0) {
            var url = $(this).data('url');
            window.location.href = url;
        }
    });

    // Prevent card click when clicking cancel button
    $('.cancel-order-form').on('click', function(e) {
        e.stopPropagation();
    });

    // Auto hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);
});
</script>
@endsection
