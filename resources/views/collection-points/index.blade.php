@extends('layouts.app')

@section('title', 'Quản lý điểm tập kết - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Quản lý điểm tập kết
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Danh sách các điểm tập kết phế liệu
                </h6>
            </div>
            <div class="col-auto">
                <!-- Button -->
                <a href="{{ route('collection-points.create') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-plus"></i> Thêm điểm tập kết mới
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
                        <h5 class="card-title mb-4">
                            <i class="mdi mdi-map-marker"></i> Quản lý điểm tập kết
                        </h5>
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

                    @if($collectionPoints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="35%">Tên điểm tập kết</th>
                                        <th width="40%">Địa chỉ</th>
                                        <th width="15%">Số bài đăng</th>
                                        <th width="12%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($collectionPoints as $point)
                                        <tr>
                                            <td>
                                                <strong>{{ $point->name }}</strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                                {{ $point->full_address }}
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $point->posts->count() }} bài đăng
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('collection-points.show', $point) }}" class="btn btn-square me-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('collection-points.edit', $point) }}" class="btn btn-square me-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('collection-points.destroy', $point) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-square" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $collectionPoints->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có điểm tập kết nào</h5>
                            <p class="text-muted mb-4">
                                Tạo điểm tập kết đầu tiên để bắt đầu đăng bài bán phế liệu
                            </p>
                            <a href="{{ route('collection-points.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm điểm tập kết mới
                            </a>
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
.btn-square {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}

.btn-square i {
    color: #6366f1;
    transition: color 0.2s ease;
}

.btn-square:hover {
    background-color: #6366f1;
    border-color: #6366f1;
    transform: translateY(-1px);
}

.btn-square:hover i {
    color: white;
}
</style>
@endsection

@section('custom-js')
<script>
// Auto hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@endsection

