@extends('layouts.app')

@section('title', 'Chi tiết điểm tập kết - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Chi tiết điểm tập kết
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Thông tin chi tiết về điểm tập kết: {{ $collectionPoint->name }}
                </h6>
            </div>
            <div class="col-auto">
                <!-- Buttons -->
                <a href="{{ route('collection-points.edit', $collectionPoint) }}" class="btn btn-warning mr-2">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('collection-points.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
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
                <div class="card padding-card">
                    <div class="card-body">
                    <div class="row">
                        <!-- Thông tin điểm tập kết -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Thông tin điểm tập kết
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Tên:</strong></div>
                                        <div class="col-8">{{ $collectionPoint->name }}</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Địa chỉ:</strong></div>
                                        <div class="col-8">
                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                            {{ $collectionPoint->full_address }}
                                        </div>
                                    </div>

                                    @if($collectionPoint->postal_code)
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Mã bưu điện:</strong></div>
                                        <div class="col-8">{{ $collectionPoint->postal_code }}</div>
                                    </div>
                                    @endif

                                    @if($collectionPoint->address_note)
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Ghi chú:</strong></div>
                                        <div class="col-8">{{ $collectionPoint->address_note }}</div>
                                    </div>
                                    @endif

                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Ngày tạo:</strong></div>
                                        <div class="col-8">
                                            <i class="fas fa-calendar text-muted"></i>
                                            {{ $collectionPoint->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"><strong>Cập nhật:</strong></div>
                                        <div class="col-8">
                                            <i class="fas fa-clock text-muted"></i>
                                            {{ $collectionPoint->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-bar text-success"></i>
                                        Thống kê bài đăng
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h3 class="text-primary">{{ $collectionPoint->posts->count() }}</h3>
                                                <p class="text-muted mb-0">Tổng bài đăng</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h3 class="text-success">{{ $collectionPoint->posts->where('status', 'active')->count() }}</h3>
                                                <p class="text-muted mb-0">Đang hoạt động</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h3 class="text-warning">{{ $collectionPoint->posts->where('status', 'sold')->count() }}</h3>
                                                <p class="text-muted mb-0">Đã bán</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bolt text-warning"></i>
                                        Thao tác nhanh
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('posts.create') }}?collection_point_id={{ $collectionPoint->id }}"
                                       class="btn btn-success btn-block mb-2">
                                        <i class="fas fa-plus"></i> Tạo bài đăng tại điểm này
                                    </a>
                                    <a href="{{ route('posts.my-posts') }}?collection_point={{ $collectionPoint->id }}"
                                       class="btn btn-info btn-block">
                                        <i class="fas fa-list"></i> Xem tất cả bài đăng tại đây
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách bài đăng -->
                    @if($collectionPoint->posts->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list text-info"></i>
                                        Bài đăng tại điểm tập kết này
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tiêu đề</th>
                                                    <th>Loại phế liệu</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày đăng</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($collectionPoint->posts->take(10) as $post)
                                                <tr>
                                                    <td>
                                                        <strong>{{ Str::limit($post->title, 30) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $post->wasteType->name }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($post->quantity, 0) }} kg</td>
                                                    <td>{{ number_format($post->price, 0) }} VNĐ/kg</td>
                                                    <td>
                                                        @if($post->status == 'active')
                                                            <span class="badge badge-success">Đang hoạt động</span>
                                                        @elseif($post->status == 'sold')
                                                            <span class="badge badge-warning">Đã bán</span>
                                                        @else
                                                            <span class="badge badge-secondary">Không hoạt động</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $post->created_at->format('d/m/Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('posts.show', $post) }}"
                                                           class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('posts.edit', $post) }}"
                                                           class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($collectionPoint->posts->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('posts.my-posts') }}?collection_point={{ $collectionPoint->id }}"
                                           class="btn btn-outline-primary">
                                            Xem tất cả {{ $collectionPoint->posts->count() }} bài đăng
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-css')
<style>
.stat-item {
    padding: 1rem 0;
}

.stat-item h3 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
