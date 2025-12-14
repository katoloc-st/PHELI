@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Order Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>Đơn hàng #{{ $order->order_number }}</h3>
                            <p class="text-muted mb-0">Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @php
                                // Tính trạng thái tổng hợp từ các items
                                $allCompleted = $order->items->every(fn($item) => $item->status === 'completed');
                                $allCancelled = $order->items->every(fn($item) => $item->status === 'cancelled');
                                $hasProcessing = $order->items->contains(fn($item) => $item->status === 'processing');
                                $hasPending = $order->items->contains(fn($item) => $item->status === 'pending');
                            @endphp

                            @if($allCompleted)
                                <span class="badge bg-success fs-6">Hoàn thành</span>
                            @elseif($allCancelled)
                                <span class="badge bg-danger fs-6">Đã hủy</span>
                            @elseif($hasProcessing)
                                <span class="badge bg-info fs-6">Đang xử lý</span>
                            @elseif($hasPending)
                                <span class="badge bg-warning fs-6">Chờ xử lý</span>
                            @else
                                <span class="badge bg-secondary fs-6">Hỗn hợp</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $itemsBySeller = $order->items->groupBy('seller_id');
                            @endphp

                            @foreach($itemsBySeller as $sellerId => $items)
                                @php
                                    $seller = $items->first()->seller;
                                    $sellerTotal = 0;
                                @endphp

                                <div class="seller-group mb-4 pb-4 border-bottom">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="mdi mdi-store text-primary me-2"></i>
                                        <h6 class="mb-0">{{ $seller->company_name ?? $seller->name }}</h6>
                                    </div>

                                    @foreach($items as $item)
                                        @php
                                            $sellerTotal += $item->subtotal;
                                        @endphp
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                @if($item->post->image)
                                                    <img src="{{ asset('storage/' . $item->post->image) }}"
                                                         alt="{{ $item->post->title }}"
                                                         class="img-fluid rounded">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                        <i class="mdi mdi-image mdi-48px text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <h6>{{ $item->post->title }}</h6>
                                                <p class="text-muted mb-0">Số lượng: {{ $item->quantity }}</p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <p class="mb-0">{{ number_format($item->price) }} VNĐ</p>
                                                <strong>{{ number_format($item->subtotal) }} VNĐ</strong>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row mt-3">
                                        <div class="col-md-8 offset-md-2">
                                            @if($items->first()->note)
                                                <p class="text-muted mb-2">
                                                    <i class="mdi mdi-note-text"></i> Ghi chú: {{ $items->first()->note }}
                                                </p>
                                            @endif
                                            @if($items->first()->shipping_method)
                                                <p class="mb-1">Phương thức vận chuyển: {{ $items->first()->shipping_method }}</p>
                                            @endif
                                            <p class="mb-1">Phí vận chuyển: {{ number_format($items->first()->shipping_fee) }} VNĐ</p>
                                            @if($items->first()->discount_type && $items->first()->discount_type !== 'none')
                                                <p class="text-success mb-1">
                                                    Giảm giá:
                                                    @if($items->first()->discount_type === 'percent')
                                                        {{ $items->first()->discount_value }}%
                                                    @elseif($items->first()->discount_type === 'fixed')
                                                        {{ number_format($items->first()->discount_value) }} VNĐ
                                                    @else
                                                        {{ $items->first()->discount_type }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Customer Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Họ tên:</strong> {{ $order->full_name }}</p>
                            <p class="mb-2"><strong>Email:</strong> {{ $order->email }}</p>
                            <p class="mb-2"><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Địa chỉ giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $order->full_address }}</p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Tổng đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal) }} VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_total) }} VNĐ</span>
                            </div>
                            @if(floatval($order->discount_total) > 0)
                                <div class="d-flex justify-content-between mb-2 text-danger">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format($order->discount_total) }} VNĐ</span>
                                </div>
                            @endif
                            @if(floatval($order->deposit_amount) > 0)
                                <div class="d-flex justify-content-between mb-2 text-warning">
                                    <span>Đặt cọc:</span>
                                    <span>{{ number_format($order->deposit_amount) }} VNĐ</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary">{{ number_format($order->grand_total) }} VNĐ</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-3">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="mdi mdi-arrow-left"></i> Quay lại danh sách
                        </a>
                        @php
                            $hasPendingItems = $order->items->contains(fn($item) => $item->status === 'pending');
                        @endphp
                        @if($hasPendingItems)
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy các sản phẩm đang chờ xử lý trong đơn hàng này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="mdi mdi-close"></i> Hủy các sản phẩm chờ xử lý
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
