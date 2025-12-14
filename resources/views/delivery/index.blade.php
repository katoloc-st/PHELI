@extends('layouts.app')

@section('title', 'Quản lý giao hàng - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Quản lý giao hàng
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Danh sách đơn hàng cần giao
                </h6>
            </div>
            <div class="col-auto">
                <!-- Filter Buttons -->
                <a href="{{ route('delivery.index', ['status' => 'awaiting_pickup']) }}" class="btn {{ request('status') == 'awaiting_pickup' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-box"></i> Chờ lấy hàng
                </a>
                <a href="{{ route('delivery.index', ['status' => 'processing']) }}" class="btn {{ request('status') == 'processing' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-truck"></i> Chờ giao hàng
                </a>
                <a href="{{ route('delivery.index', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-check"></i> Hoàn thành
                </a>
                <a href="{{ route('delivery.index', ['status' => 'cancelled']) }}" class="btn {{ request('status') == 'cancelled' ? 'btn-light' : 'btn-outline-light' }}">
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
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($orderItems->count() > 0)
                    <div class="row">
                        @php
                            $currentGroupKey = null;
                            $isFirstItemInGroup = true;
                        @endphp
                        @foreach($orderItems as $item)
                            @php
                                // Tạo key để nhóm theo order_id + seller_id
                                $groupKey = $item->order_id . '_' . $item->seller_id;

                                // Kiểm tra nếu đây là nhóm mới
                                if ($currentGroupKey !== $groupKey) {
                                    $currentGroupKey = $groupKey;
                                    $isFirstItemInGroup = true;

                                    // Đếm số items trong nhóm này (cùng order_id và seller_id, loại trừ các item đã hủy)
                                    $itemsInGroup = $orderItems->where('order_id', $item->order_id)
                                                              ->where('seller_id', $item->seller_id)
                                                              ->whereNotIn('status', ['cancelled']);
                                    $totalItemsInGroup = $itemsInGroup->count();
                                } else {
                                    $isFirstItemInGroup = false;
                                }
                            @endphp

                            @if($isFirstItemInGroup && $totalItemsInGroup > 1)
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-layer-group"></i> <strong>Đơn ghép:</strong> Mã đơn <strong>{{ $item->order->order_number }}</strong> - Người bán: <strong>{{ $item->seller->name ?? 'N/A' }}</strong> ({{ $totalItemsInGroup }} sản phẩm)
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 mb-4">
                                <div class="card h-100" style="height: 240px;">
                                    <div class="row no-gutters h-100">
                                        <div class="col-lg-5 col-md-5">
                                            @php
                                                $badgeClass = match($item->status) {
                                                    'completed' => 'badge-success',
                                                    'processing' => 'badge-info',
                                                    'awaiting_pickup' => 'badge-primary',
                                                    'cancelled' => 'badge-danger',
                                                    default => 'badge-warning'
                                                };

                                                $statusText = match($item->status) {
                                                    'completed' => 'Hoàn thành',
                                                    'processing' => 'Chờ giao hàng',
                                                    'awaiting_pickup' => 'Chờ lấy hàng',
                                                    'cancelled' => 'Đã hủy',
                                                    default => 'Chờ xử lý'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}" style="position: absolute; top: 10px; left: 10px; z-index: 10;">{{ $statusText }}</span>
                                            @if($item->post && $item->post->images)
                                                @php
                                                    $images = is_string($item->post->images) ? json_decode($item->post->images, true) : $item->post->images;
                                                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                                @endphp
                                                @if($firstImage)
                                                    <img class="card-img-top" src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->post->title }}" style="height: 100%; object-fit: cover;">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image" style="height: 100%; object-fit: cover;">
                                                @endif
                                            @else
                                                <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image" style="height: 100%; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                                <!-- Action Buttons - Top Right -->
                                                <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                    @if($item->status === 'awaiting_pickup')
                                                        <form action="{{ route('delivery.updateStatus', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận đã lấy hàng và chuyển sang trạng thái Chờ giao hàng?')">
                                                            @csrf
                                                            <input type="hidden" name="status" value="processing">
                                                            <button type="submit" class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-truck"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('delivery.updateStatus', $item->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                                                            @csrf
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($item->status === 'processing')
                                                        <form action="{{ route('delivery.updateStatus', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận đã giao hàng thành công?')">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('delivery.updateStatus', $item->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                                                            @csrf
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div>
                                                    <h5 class="card-title mb-2">{{ $item->post->title ?? 'N/A' }}</h5>
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-shopping-cart"></i> Mã đơn: <strong>{{ $item->order->order_number }}</strong>
                                                    </p>

                                                    @if($item->status === 'awaiting_pickup')
                                                        {{-- Hiển thị thông tin người bán khi chờ lấy hàng --}}
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-store"></i> Người bán: <strong>{{ $item->seller->name ?? 'N/A' }}</strong>
                                                            <span class="ml-4"><i class="fas fa-phone"></i> SĐT: <strong>{{ $item->seller->phone ?? 'N/A' }}</strong></span>
                                                        </p>
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-map-marker-alt"></i> Địa chỉ lấy hàng: <strong>{{ $item->seller->address ?? 'N/A' }}</strong>
                                                        </p>
                                                    @else
                                                        {{-- Hiển thị thông tin người mua khi chờ giao hàng --}}
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-user"></i> Người mua: <strong>{{ $item->order->user->name ?? 'N/A' }}</strong>
                                                            <span class="ml-4"><i class="fas fa-phone"></i> SĐT: <strong>{{ $item->order->phone }}</strong></span>
                                                        </p>
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng: <strong>{{ $item->order->full_address }}</strong>
                                                        </p>
                                                    @endif

                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-weight"></i> Khối lượng: <strong>{{ $item->quantity }} kg</strong>
                                                    </p>
                                                    @php
                                                        // Tính ngày lấy hàng dựa vào shipping method
                                                        $pickupDaysMin = 1;
                                                        $pickupDaysMax = 2;
                                                        if ($item->order->shipping_method === 'standard') {
                                                            $pickupDaysMin = 7;
                                                            $pickupDaysMax = 12;
                                                        } elseif ($item->order->shipping_method === 'express') {
                                                            $pickupDaysMin = 3;
                                                            $pickupDaysMax = 5;
                                                        }
                                                        $pickupDateMin = now()->addDays($pickupDaysMin)->format('d/m/Y');
                                                        $pickupDateMax = now()->addDays($pickupDaysMax)->format('d/m/Y');
                                                    @endphp
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-calendar"></i> Ngày {{ $item->status === 'awaiting_pickup' ? 'lấy hàng' : 'giao hàng' }} dự kiến: <strong>{{ $pickupDateMin }} - {{ $pickupDateMax }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12">
                            {{ $orderItems->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có đơn hàng nào cần giao.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.btn-outline-light {
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-outline-light:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
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

.badge-primary {
    background-color: #3b82f6;
    color: #ffffff;
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

/* Card height */
.card.h-100 {
    height: 240px !important;
}

.card-img-top {
    height: 240px;
    object-fit: cover;
}
</style>
@endsection
