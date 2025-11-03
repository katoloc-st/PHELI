@extends('layouts.app')

@section('title', 'Chỉnh sửa điểm tập kết - Hệ thống quản lý phế liệu')

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Chỉnh sửa điểm tập kết
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Cập nhật thông tin điểm tập kết: {{ $collectionPoint->name }}
                </h6>
            </div>
            <div class="col-auto">
                <!-- Button -->
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
                  @if(session('success'))
                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  @endif

                  @if($errors->any())
                     <div class="alert alert-danger">
                        <h6><i class="mdi mdi-alert-circle"></i> Vui lòng kiểm tra lại thông tin:</h6>
                        <ul class="mb-0">
                           @foreach($errors->all() as $error)
                              <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
                  @endif

                <form method="POST" action="{{ route('collection-points.update', $collectionPoint) }}">
                @csrf
                @method('PUT')
                    <div class="card padding-card">
                        <div class="card-body">
                           <h5 class="card-title mb-4">
                              <i class="mdi mdi-map-marker"></i> Thông tin về điểm tập kết phế liệu
                           </h5>

                                <!-- Dòng 1: Tên điểm tập kết và Mã bưu điện -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">Tên điểm tập kết <span class="required-field">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   value="{{ old('name', $collectionPoint->name) }}"
                                                   placeholder="VD: Điểm tập kết Quận 1"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="postal_code">Mã bưu điện</label>
                                            <input type="text"
                                                   class="form-control @error('postal_code') is-invalid @enderror"
                                                   id="postal_code"
                                                   name="postal_code"
                                                   value="{{ old('postal_code', $collectionPoint->postal_code) }}"
                                                   placeholder="VD: 70000">
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Dòng 2: Địa chỉ chi tiết -->
                                <div class="form-group">
                                    <label for="detailed_address">Địa chỉ chi tiết <span class="required-field">*</span></label>
                                    <input type="text"
                                           class="form-control @error('detailed_address') is-invalid @enderror"
                                           id="detailed_address"
                                           name="detailed_address"
                                           value="{{ old('detailed_address', $collectionPoint->detailed_address) }}"
                                           placeholder="VD: 123 Nguyễn Huệ"
                                           required>
                                    @error('detailed_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dòng 3: Tỉnh, Quận và Phường -->

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province">Tỉnh/Thành phố <span class="required-field">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('province') is-invalid @enderror"
                                                   id="province"
                                                   name="province"
                                                   value="{{ old('province', $collectionPoint->province) }}"
                                                   placeholder="VD: TP.HCM"
                                                   required>
                                            @error('province')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ward">Phường/Xã <span class="required-field">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('ward') is-invalid @enderror"
                                                   id="ward"
                                                   name="ward"
                                                   value="{{ old('ward', $collectionPoint->ward) }}"
                                                   placeholder="VD: Phường Bến Nghé"
                                                   required>
                                            @error('ward')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Dòng cuối: Ghi chú -->
                                <div class="form-group">
                                    <label for="address_note">Ghi chú địa chỉ</label>
                                    <textarea class="form-control @error('address_note') is-invalid @enderror"
                                              id="address_note"
                                              name="address_note"
                                              rows="3"
                                              placeholder="VD: Cổng chính, bên trái, gần ngã tư...">{{ old('address_note', $collectionPoint->address_note) }}</textarea>
                                    @error('address_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Thông tin:</strong> Điểm tập kết này đang có {{ $collectionPoint->posts->count() }} bài đăng liên kết.
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Cập nhật
                                    </button>
                                    <a href="{{ route('collection-points.index') }}" class="btn btn-secondary btn-lg ml-2">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
