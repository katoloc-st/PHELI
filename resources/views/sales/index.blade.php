@extends('layouts.app')

@section('title', 'Quản lý yêu cầu mua hàng - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Quản lý yêu cầu mua hàng
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Danh sách yêu cầu mua hàng từ khách hàng
                </h6>
            </div>
            <div class="col-auto">
                <!-- Filter Buttons -->
                <a href="{{ route('sales.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-clock"></i> Chờ xử lý
                </a>
                <a href="{{ route('sales.index', ['status' => 'awaiting_pickup']) }}" class="btn {{ request('status') == 'awaiting_pickup' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-box"></i> Chờ lấy
                </a>
                <a href="{{ route('sales.index', ['status' => 'processing']) }}" class="btn {{ request('status') == 'processing' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-truck"></i> Chờ giao
                </a>
                <a href="{{ route('sales.index', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-light' : 'btn-outline-light' }} mr-2">
                    <i class="fas fa-check"></i> Hoàn thành
                </a>
                <a href="{{ route('sales.index', ['status' => 'cancelled']) }}" class="btn {{ request('status') == 'cancelled' ? 'btn-light' : 'btn-outline-light' }}">
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
                                <div class="card card-list card-list-view mb-4 position-relative" style="height: 240px;">
                                    @if($item->status === 'pending')
                                        <div class="position-absolute" style="top: 15px; right: 15px; z-index: 10;">
                                            <form action="{{ route('sales.updateStatus', $item->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="status" value="awaiting_pickup">
                                                <button type="submit" class="btn btn-outline-info btn-sm" style="padding: 5px 12px; font-size: 13px;" onclick="return confirm('Xác nhận duyệt đơn?')">
                                                    <i class="fas fa-check"></i> Duyệt
                                                </button>
                                            </form>

                                            <form action="{{ route('sales.updateStatus', $item->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" style="padding: 5px 12px; font-size: 13px;" onclick="return confirm('Xác nhận hủy đơn?')">
                                                    <i class="fas fa-times"></i> Hủy
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    <div class="row no-gutters" style="height: 100%;">
                                        <div class="col-lg-5 col-md-5 position-relative" style="height: 100%;">
                                            @php
                                                $badgeClass = match($item->status) {
                                                    'completed' => 'badge-success',
                                                    'awaiting_pickup' => 'badge-primary',
                                                    'processing' => 'badge-info',
                                                    'cancelled' => 'badge-danger',
                                                    default => 'badge-warning'
                                                };

                                                $statusText = match($item->status) {
                                                    'completed' => 'Hoàn thành',
                                                    'awaiting_pickup' => 'Chờ lấy hàng',
                                                    'processing' => 'Chờ giao hàng',
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
                                                    <img class="card-img-top" src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->post->title }}" style="height: 100%; object-fit: cover;">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image" style="height: 100%; object-fit: cover;">
                                                @endif
                                            @else
                                                <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="No image" style="height: 100%; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <a href="{{ route('posts.show', $item->post_id) }}" class="text-dark">
                                                        {{ $item->post->title }}
                                                    </a>
                                                </h5>
                                                <p class="card-text">
                                                    <strong>Khách hàng:</strong> {{ $item->order->full_name }}<br>
                                                    <strong>Điện thoại:</strong> {{ $item->order->phone }}<br>
                                                    <strong>Email:</strong> {{ $item->order->email }}<br>
                                                    <strong>Địa chỉ:</strong> {{ $item->order->full_address }}
                                                    @if($item->order->notes)
                                                        <br><strong>Ghi chú:</strong> {{ $item->order->notes }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <span><i class="mdi mdi-credit-card"></i> Phương thức thanh toán : <strong>{{ $item->order->payment_method ?? 'Tiền mặt' }}</strong></span>
                                                @php
                                                    $orderDate = $item->created_at;
                                                    $shippingMethod = $item->shipping_method ?? 'standard';

                                                    // Tính ngày lấy hàng dựa trên phương thức vận chuyển
                                                    switch($shippingMethod) {
                                                        case 'super-express': // Hỏa tốc: 1-2 ngày
                                                            $minDays = 1;
                                                            $maxDays = 2;
                                                            break;
                                                        case 'express': // Nhanh: 3-5 ngày
                                                            $minDays = 3;
                                                            $maxDays = 5;
                                                            break;
                                                        case 'standard': // Tiêu chuẩn: 7-12 ngày
                                                        default:
                                                            $minDays = 7;
                                                            $maxDays = 12;
                                                            break;
                                                    }

                                                    $deliveryStartDate = $orderDate->copy()->addDays($minDays);
                                                    $deliveryEndDate = $orderDate->copy()->addDays($maxDays);
                                                    $deliveryRange = $deliveryStartDate->format('d/m') . ' - ' . $deliveryEndDate->format('d/m');
                                                @endphp
                                                <span><i class="mdi mdi-calendar"></i> Ngày lấy hàng : <strong>{{ $deliveryRange }}</strong></span>
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
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-3x mb-3"></i>
                                <h5>Chưa có yêu cầu mua hàng nào</h5>
                                <p>Khi có khách hàng đặt mua sản phẩm của bạn, chúng sẽ hiển thị tại đây.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
