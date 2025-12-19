@extends('layouts.app')

@section('title', 'Dashboard - Thương lái')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Xin chào, {{ Auth::user()->name }}!
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Thống kê hoạt động và quản lý phế liệu
                </h6>
            </div>
            <div class="col-auto">
                <span class="text-white-50">
                    <i class="far fa-clock"></i> {{ now()->format('d/m/Y H:i') }}
                </span>
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
                <!-- Sidebar -->
                @include('layouts.sidebar')
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="card padding-card">
                    <div class="card-body">
                        <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng bài đăng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPosts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Bài đăng đang hoạt động
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activePosts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Giao dịch
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tổng doanh thu
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalRevenue) }} VNĐ
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đánh giá và xu hướng -->
    <div class="row mb-4">
        <!-- Đánh giá trung bình -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá từ khách hàng</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h1 class="display-3 text-warning mb-0">
                            {{ number_format($averageRating ?? 0, 1) }}
                        </h1>
                        <div class="text-muted">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($averageRating ?? 0))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <p class="text-muted mb-0">Từ {{ $totalReviews }} đánh giá</p>
                </div>
            </div>
        </div>

        <!-- Biểu đồ xu hướng đăng bài -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Xu hướng đăng bài (6 tháng gần đây)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyPostsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo loại phế liệu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bài đăng theo loại phế liệu</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại phế liệu</th>
                                    <th>Số bài đăng</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($postsByWasteType as $item)
                                    <tr>
                                        <td>{{ $item->wasteType->name ?? 'N/A' }}</td>
                                        <td>{{ $item->total }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ ($item->total / $totalPosts) * 100 }}%"
                                                     aria-valuenow="{{ ($item->total / $totalPosts) * 100 }}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format(($item->total / $totalPosts) * 100, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bài đăng và giao dịch gần đây -->
    <div class="row">
        <!-- Bài đăng gần đây -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Bài đăng gần đây</h6>
                    <a href="{{ route('scrap-dealer.posts') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($myPosts->take(5) as $post)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $post->wasteType->name ?? 'N/A' }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span class="badge badge-{{ $post->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                            <p class="mb-1 mt-2 text-muted small">
                                {{ Str::limit($post->description, 100) }}
                            </p>
                            <div class="text-primary font-weight-bold">
                                {{ number_format($post->price) }} VNĐ/kg
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Chưa có bài đăng nào</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Giao dịch gần đây -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Giao dịch gần đây</h6>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($myTransactions->take(5) as $transaction)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Giao dịch #{{ $transaction->id }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <div class="text-muted small">
                                    Số lượng: {{ $transaction->quantity }} kg
                                </div>
                                <div class="text-primary font-weight-bold">
                                    {{ number_format($transaction->total_amount) }} VNĐ
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Chưa có giao dịch nào</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
                    <!-- End Card Body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Biểu đồ xu hướng đăng bài theo tháng
    var ctx = document.getElementById('monthlyPostsChart').getContext('2d');
    var monthlyData = @json($monthlyPosts);

    var labels = monthlyData.map(item => item.month);
    var data = monthlyData.map(item => item.total);

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số bài đăng',
                data: data,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
