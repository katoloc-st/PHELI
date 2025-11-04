@extends('layouts.app')

@section('title', 'Thêm điểm tập kết - Hệ thống quản lý phế liệu')

@push('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<style>
#map {
    height: 400px;
    width: 100%;
    border-radius: 8px;
    margin-bottom: 20px;
}

.mapboxgl-ctrl-geocoder {
    min-width: 100%;
}
</style>
@endpush

@section('content')
<section class="bg-dark py-5 user-header">
    <div class="container">
        <div class="row align-items-center mt-2 mb-5 pb-4">
            <div class="col">
                <!-- Heading -->
                <h1 class="text-white mb-2">
                    Thêm điểm tập kết mới
                </h1>
                <!-- Text -->
                <h6 class="font-weight-normal text-white-50 mb-0">
                    Tạo điểm tập kết mới
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

                <form method="POST" action="{{ route('collection-points.store') }}">
                @csrf
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
                                               value="{{ old('name') }}"
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
                                               value="{{ old('postal_code') }}"
                                               placeholder="VD: 70000">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                           </div>

                           <!-- Dòng 2: Tỉnh, Quận và Phường -->
                           <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="province">Tỉnh/Thành phố <span class="required-field">*</span></label>
                                        <select class="form-control @error('province') is-invalid @enderror"
                                                id="province"
                                                name="province"
                                                required>
                                            <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                            <option value="TP. Hồ Chí Minh" {{ old('province') == 'TP. Hồ Chí Minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                            <option value="Hà Nội" {{ old('province') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                            <option value="Đà Nẵng" {{ old('province') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                            <option value="Cần Thơ" {{ old('province') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                            <option value="Hải Phòng" {{ old('province') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                            <option value="An Giang" {{ old('province') == 'An Giang' ? 'selected' : '' }}>An Giang</option>
                                            <option value="Bà Rịa - Vũng Tàu" {{ old('province') == 'Bà Rịa - Vũng Tàu' ? 'selected' : '' }}>Bà Rịa - Vũng Tàu</option>
                                            <option value="Bạc Liêu" {{ old('province') == 'Bạc Liêu' ? 'selected' : '' }}>Bạc Liêu</option>
                                            <option value="Bắc Giang" {{ old('province') == 'Bắc Giang' ? 'selected' : '' }}>Bắc Giang</option>
                                            <option value="Bắc Kạn" {{ old('province') == 'Bắc Kạn' ? 'selected' : '' }}>Bắc Kạn</option>
                                            <option value="Bắc Ninh" {{ old('province') == 'Bắc Ninh' ? 'selected' : '' }}>Bắc Ninh</option>
                                            <option value="Bến Tre" {{ old('province') == 'Bến Tre' ? 'selected' : '' }}>Bến Tre</option>
                                            <option value="Bình Dương" {{ old('province') == 'Bình Dương' ? 'selected' : '' }}>Bình Dương</option>
                                            <option value="Bình Định" {{ old('province') == 'Bình Định' ? 'selected' : '' }}>Bình Định</option>
                                            <option value="Bình Phước" {{ old('province') == 'Bình Phước' ? 'selected' : '' }}>Bình Phước</option>
                                            <option value="Bình Thuận" {{ old('province') == 'Bình Thuận' ? 'selected' : '' }}>Bình Thuận</option>
                                            <option value="Cà Mau" {{ old('province') == 'Cà Mau' ? 'selected' : '' }}>Cà Mau</option>
                                            <option value="Cao Bằng" {{ old('province') == 'Cao Bằng' ? 'selected' : '' }}>Cao Bằng</option>
                                            <option value="Đắk Lắk" {{ old('province') == 'Đắk Lắk' ? 'selected' : '' }}>Đắk Lắk</option>
                                            <option value="Đắk Nông" {{ old('province') == 'Đắk Nông' ? 'selected' : '' }}>Đắk Nông</option>
                                            <option value="Điện Biên" {{ old('province') == 'Điện Biên' ? 'selected' : '' }}>Điện Biên</option>
                                            <option value="Đồng Nai" {{ old('province') == 'Đồng Nai' ? 'selected' : '' }}>Đồng Nai</option>
                                            <option value="Đồng Tháp" {{ old('province') == 'Đồng Tháp' ? 'selected' : '' }}>Đồng Tháp</option>
                                            <option value="Gia Lai" {{ old('province') == 'Gia Lai' ? 'selected' : '' }}>Gia Lai</option>
                                            <option value="Hà Giang" {{ old('province') == 'Hà Giang' ? 'selected' : '' }}>Hà Giang</option>
                                            <option value="Hà Nam" {{ old('province') == 'Hà Nam' ? 'selected' : '' }}>Hà Nam</option>
                                            <option value="Hà Tĩnh" {{ old('province') == 'Hà Tĩnh' ? 'selected' : '' }}>Hà Tĩnh</option>
                                            <option value="Hải Dương" {{ old('province') == 'Hải Dương' ? 'selected' : '' }}>Hải Dương</option>
                                            <option value="Hậu Giang" {{ old('province') == 'Hậu Giang' ? 'selected' : '' }}>Hậu Giang</option>
                                            <option value="Hòa Bình" {{ old('province') == 'Hòa Bình' ? 'selected' : '' }}>Hòa Bình</option>
                                            <option value="Hưng Yên" {{ old('province') == 'Hưng Yên' ? 'selected' : '' }}>Hưng Yên</option>
                                            <option value="Khánh Hòa" {{ old('province') == 'Khánh Hòa' ? 'selected' : '' }}>Khánh Hòa</option>
                                            <option value="Kiên Giang" {{ old('province') == 'Kiên Giang' ? 'selected' : '' }}>Kiên Giang</option>
                                            <option value="Kon Tum" {{ old('province') == 'Kon Tum' ? 'selected' : '' }}>Kon Tum</option>
                                            <option value="Lai Châu" {{ old('province') == 'Lai Châu' ? 'selected' : '' }}>Lai Châu</option>
                                            <option value="Lâm Đồng" {{ old('province') == 'Lâm Đồng' ? 'selected' : '' }}>Lâm Đồng</option>
                                            <option value="Lạng Sơn" {{ old('province') == 'Lạng Sơn' ? 'selected' : '' }}>Lạng Sơn</option>
                                            <option value="Lào Cai" {{ old('province') == 'Lào Cai' ? 'selected' : '' }}>Lào Cai</option>
                                            <option value="Long An" {{ old('province') == 'Long An' ? 'selected' : '' }}>Long An</option>
                                            <option value="Nam Định" {{ old('province') == 'Nam Định' ? 'selected' : '' }}>Nam Định</option>
                                            <option value="Nghệ An" {{ old('province') == 'Nghệ An' ? 'selected' : '' }}>Nghệ An</option>
                                            <option value="Ninh Bình" {{ old('province') == 'Ninh Bình' ? 'selected' : '' }}>Ninh Bình</option>
                                            <option value="Ninh Thuận" {{ old('province') == 'Ninh Thuận' ? 'selected' : '' }}>Ninh Thuận</option>
                                            <option value="Phú Thọ" {{ old('province') == 'Phú Thọ' ? 'selected' : '' }}>Phú Thọ</option>
                                            <option value="Phú Yên" {{ old('province') == 'Phú Yên' ? 'selected' : '' }}>Phú Yên</option>
                                            <option value="Quảng Bình" {{ old('province') == 'Quảng Bình' ? 'selected' : '' }}>Quảng Bình</option>
                                            <option value="Quảng Nam" {{ old('province') == 'Quảng Nam' ? 'selected' : '' }}>Quảng Nam</option>
                                            <option value="Quảng Ngãi" {{ old('province') == 'Quảng Ngãi' ? 'selected' : '' }}>Quảng Ngãi</option>
                                            <option value="Quảng Ninh" {{ old('province') == 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                                            <option value="Quảng Trị" {{ old('province') == 'Quảng Trị' ? 'selected' : '' }}>Quảng Trị</option>
                                            <option value="Sóc Trăng" {{ old('province') == 'Sóc Trăng' ? 'selected' : '' }}>Sóc Trăng</option>
                                            <option value="Sơn La" {{ old('province') == 'Sơn La' ? 'selected' : '' }}>Sơn La</option>
                                            <option value="Tây Ninh" {{ old('province') == 'Tây Ninh' ? 'selected' : '' }}>Tây Ninh</option>
                                            <option value="Thái Bình" {{ old('province') == 'Thái Bình' ? 'selected' : '' }}>Thái Bình</option>
                                            <option value="Thái Nguyên" {{ old('province') == 'Thái Nguyên' ? 'selected' : '' }}>Thái Nguyên</option>
                                            <option value="Thanh Hóa" {{ old('province') == 'Thanh Hóa' ? 'selected' : '' }}>Thanh Hóa</option>
                                            <option value="Thừa Thiên Huế" {{ old('province') == 'Thừa Thiên Huế' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                                            <option value="Tiền Giang" {{ old('province') == 'Tiền Giang' ? 'selected' : '' }}>Tiền Giang</option>
                                            <option value="Trà Vinh" {{ old('province') == 'Trà Vinh' ? 'selected' : '' }}>Trà Vinh</option>
                                            <option value="Tuyên Quang" {{ old('province') == 'Tuyên Quang' ? 'selected' : '' }}>Tuyên Quang</option>
                                            <option value="Vĩnh Long" {{ old('province') == 'Vĩnh Long' ? 'selected' : '' }}>Vĩnh Long</option>
                                            <option value="Vĩnh Phúc" {{ old('province') == 'Vĩnh Phúc' ? 'selected' : '' }}>Vĩnh Phúc</option>
                                            <option value="Yên Bái" {{ old('province') == 'Yên Bái' ? 'selected' : '' }}>Yên Bái</option>
                                        </select>
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district">Quận/Huyện <span class="required-field">*</span></label>
                                        <select class="form-control @error('district') is-invalid @enderror"
                                                id="district"
                                                name="district"
                                                required>
                                            <option value="">-- Chọn Tỉnh trước --</option>
                                        </select>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ward">Phường/Xã <span class="required-field">*</span></label>
                                        <input type="text"
                                               class="form-control @error('ward') is-invalid @enderror"
                                               id="ward"
                                               name="ward"
                                               value="{{ old('ward') }}"
                                               placeholder="VD: Phường Bến Nghé"
                                               required>
                                        @error('ward')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                           </div>

                           <!-- Dòng 3: Địa chỉ chi tiết -->
                           <div class="form-group">
                                <label for="detailed_address">Địa chỉ chi tiết <span class="required-field">*</span></label>
                                <input type="text"
                                       class="form-control @error('detailed_address') is-invalid @enderror"
                                       id="detailed_address"
                                       name="detailed_address"
                                       value="{{ old('detailed_address') }}"
                                       placeholder="VD: 123 Nguyễn Huệ"
                                       required>
                                @error('detailed_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                           </div>

                           <!-- Dòng cuối: Ghi chú -->
                           <div class="form-group">
                                <label for="address_note">Ghi chú địa chỉ</label>
                                <input type="text"
                                       class="form-control @error('address_note') is-invalid @enderror"
                                       id="address_note"
                                       name="address_note"
                                       value="{{ old('address_note') }}"
                                       placeholder="VD: Cổng chính, bên trái, gần ngã tư...">
                                @error('address_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                           </div>

                           <!-- Bản đồ -->
                           <div class="form-group">
                                <label>Vị trí trên bản đồ</label>
                                <div id="map" style="height: 400px; width: 100%; background-color: #e0e0e0; border: 2px solid #ccc; border-radius: 8px;"></div>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information"></i> Chọn tỉnh/quận để zoom vào khu vực. Kéo marker hoặc click trên bản đồ để chọn vị trí chính xác.
                                </small>
                                <!-- Hidden inputs for coordinates -->
                                <input type="hidden" id="latitude" name="latitude" value="">
                                <input type="hidden" id="longitude" name="longitude" value="">
                           </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Tạo điểm tập kết
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

<!-- Mapbox Scripts - Load inline to avoid script loading issues -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>

@endsection

@section('custom-js')
<script>
(function() {
    console.log('=== INLINE MAP SCRIPT STARTING ===');

    // Mapbox Access Token
    var mapboxAccessToken = 'pk.eyJ1IjoiaG9hbmdoYW5kbiIsImEiOiJjbTdsbTkydm8wZGpiMmxxcTdvdzVqbHd3In0.HUUli-jvI1ALTBuzSeKTpw';

    var map;
    var marker;
    var isReverseGeocoding = false;

// District data
const districtData = {
   "TP. Hồ Chí Minh": ["Quận 1", "Quận 2", "Quận 3", "Quận 4", "Quận 5", "Quận 6", "Quận 7", "Quận 8", "Quận 9", "Quận 10", "Quận 11", "Quận 12", "Quận Bình Tân", "Quận Bình Thạnh", "Quận Gò Vấp", "Quận Phú Nhuận", "Quận Tân Bình", "Quận Tân Phú", "Quận Thủ Đức", "Huyện Bình Chánh", "Huyện Cần Giờ", "Huyện Củ Chi", "Huyện Hóc Môn", "Huyện Nhà Bè"],
   "Hà Nội": ["Quận Ba Đình", "Quận Hoàn Kiếm", "Quận Tây Hồ", "Quận Long Biên", "Quận Cầu Giấy", "Quận Đống Đa", "Quận Hai Bà Trưng", "Quận Hoàng Mai", "Quận Thanh Xuân", "Quận Hà Đông", "Quận Nam Từ Liêm", "Quận Bắc Từ Liêm", "Huyện Ba Vì", "Huyện Chương Mỹ", "Huyện Đan Phượng", "Huyện Đông Anh", "Huyện Gia Lâm", "Huyện Hoài Đức", "Huyện Mê Linh", "Huyện Mỹ Đức", "Huyện Phú Xuyên", "Huyện Phúc Thọ", "Huyện Quốc Oai", "Huyện Sóc Sơn", "Huyện Thạch Thất", "Huyện Thanh Oai", "Huyện Thanh Trì", "Huyện Thường Tín", "Huyện Ứng Hòa", "Thị xã Sơn Tây"],
   "Đà Nẵng": ["Quận Hải Châu", "Quận Thanh Khê", "Quận Sơn Trà", "Quận Ngũ Hành Sơn", "Quận Liên Chiểu", "Quận Cẩm Lệ", "Huyện Hòa Vang", "Huyện Hoàng Sa"],
   "Cần Thơ": ["Quận Ninh Kiều", "Quận Ô Môn", "Quận Bình Thủy", "Quận Cái Răng", "Quận Thốt Nốt", "Huyện Vĩnh Thạnh", "Huyện Cờ Đỏ", "Huyện Phong Điền", "Huyện Thới Lai"],
   "Hải Phòng": ["Quận Hồng Bàng", "Quận Ngô Quyền", "Quận Lê Chân", "Quận Hải An", "Quận Kiến An", "Quận Đồ Sơn", "Quận Dương Kinh", "Huyện Thuỷ Nguyên", "Huyện An Dương", "Huyện An Lão", "Huyện Kiến Thuỵ", "Huyện Tiên Lãng", "Huyện Vĩnh Bảo", "Huyện Cát Hải", "Huyện Bạch Long Vĩ"],
   "An Giang": ["TP. Long Xuyên", "TP. Châu Đốc", "Huyện An Phú", "Huyện Tân Châu", "Huyện Phú Tân", "Huyện Châu Phú", "Huyện Tịnh Biên", "Huyện Tri Tôn", "Huyện Châu Thành", "Huyện Chợ Mới", "Huyện Thoại Sơn"],
   "Bà Rịa - Vũng Tàu": ["TP. Vũng Tàu", "TP. Bà Rịa", "Huyện Châu Đức", "Huyện Xuyên Mộc", "Huyện Long Điền", "Huyện Đất Đỏ", "Huyện Tân Thành", "Huyện Côn Đảo"],
   "Bạc Liêu": ["TP. Bạc Liêu", "Huyện Hồng Dân", "Huyện Phước Long", "Huyện Vĩnh Lợi", "Thị xã Giá Rai", "Huyện Đông Hải", "Huyện Hoà Bình"],
   "Bắc Giang": ["TP. Bắc Giang", "Huyện Yên Thế", "Huyện Tân Yên", "Huyện Lạng Giang", "Huyện Lục Nam", "Huyện Lục Ngạn", "Huyện Sơn Động", "Huyện Yên Dũng", "Huyện Việt Yên", "Huyện Hiệp Hòa"],
   "Bắc Kạn": ["TP. Bắc Kạn", "Huyện Pác Nặm", "Huyện Ba Bể", "Huyện Ngân Sơn", "Huyện Bạch Thông", "Huyện Chợ Đồn", "Huyện Chợ Mới", "Huyện Na Rì"],
   "Bắc Ninh": ["TP. Bắc Ninh", "Huyện Yên Phong", "Huyện Quế Võ", "Huyện Tiên Du", "Thị xã Từ Sơn", "Huyện Thuận Thành", "Huyện Gia Bình", "Huyện Lương Tài"],
   "Bến Tre": ["TP. Bến Tre", "Huyện Châu Thành", "Huyện Chợ Lách", "Huyện Mỏ Cày Nam", "Huyện Giồng Trôm", "Huyện Bình Đại", "Huyện Ba Tri", "Huyện Thạnh Phú", "Huyện Mỏ Cày Bắc"],
   "Bình Dương": ["TP. Thủ Dầu Một", "TP. Thuận An", "TP. Dĩ An", "Huyện Bàu Bàng", "Huyện Dầu Tiếng", "Huyện Bến Cát", "Huyện Phú Giáo", "Huyện Tân Uyên", "Huyện Bắc Tân Uyên"],
   "Bình Định": ["TP. Quy Nhơn", "Huyện An Lão", "Huyện Hoài Nhơn", "Huyện Hoài Ân", "Huyện Phù Mỹ", "Huyện Vĩnh Thạnh", "Huyện Tây Sơn", "Huyện Phù Cát", "Huyện An Nhơn", "Thị xã Hoài Nhơn", "Huyện Tuy Phước", "Huyện Vân Canh"],
   "Bình Phước": ["TP. Đồng Xoài", "Thị xã Bình Long", "Thị xã Phước Long", "Huyện Bù Gia Mập", "Huyện Lộc Ninh", "Huyện Bù Đốp", "Huyện Hớn Quản", "Huyện Đồng Phú", "Huyện Bù Đăng", "Huyện Chơn Thành", "Huyện Phú Riềng"],
   "Bình Thuận": ["TP. Phan Thiết", "Thị xã La Gi", "Huyện Tuy Phong", "Huyện Bắc Bình", "Huyện Hàm Thuận Bắc", "Huyện Hàm Thuận Nam", "Huyện Tánh Linh", "Huyện Đức Linh", "Huyện Hàm Tân", "Huyện Phú Quý"],
   "Cà Mau": ["TP. Cà Mau", "Huyện U Minh", "Huyện Thới Bình", "Huyện Trần Văn Thời", "Huyện Cái Nước", "Huyện Đầm Dơi", "Huyện Năm Căn", "Huyện Phú Tân", "Huyện Ngọc Hiển"],
   "Cao Bằng": ["TP. Cao Bằng", "Huyện Bảo Lâm", "Huyện Bảo Lạc", "Huyện Hà Quảng", "Huyện Trùng Khánh", "Huyện Hạ Lang", "Huyện Quảng Uyên", "Huyện Phục Hoà", "Huyện Hoà An", "Huyện Nguyên Bình", "Huyện Thạch An", "Huyện Thông Nông", "Huyện Trà Lĩnh"],
   "Đắk Lắk": ["TP. Buôn Ma Thuột", "Thị xã Buôn Hồ", "Huyện Ea H'leo", "Huyện Ea Súp", "Huyện Buôn Đôn", "Huyện Cư M'gar", "Huyện Krông Búk", "Huyện Krông Năng", "Huyện Ea Kar", "Huyện M'Đrắk", "Huyện Krông Bông", "Huyện Krông Pắc", "Huyện Krông A Na", "Huyện Lắk", "Huyện Cư Kuin"],
   "Đắk Nông": ["TP. Gia Nghĩa", "Huyện Đăk Glong", "Huyện Cư Jút", "Huyện Đắk Mil", "Huyện Krông Nô", "Huyện Đắk Song", "Huyện Đắk R'Lấp", "Huyện Tuy Đức"],
   "Điện Biên": ["TP. Điện Biên Phủ", "Thị xã Mường Lay", "Huyện Mường Chà", "Huyện Tủa Chùa", "Huyện Tuần Giáo", "Huyện Điện Biên", "Huyện Điện Biên Đông", "Huyện Mường Ảng", "Huyện Nậm Pồ", "Huyện Mường Nhé"],
   "Đồng Nai": ["TP. Biên Hòa", "TP. Long Khánh", "Huyện Tân Phú", "Huyện Vĩnh Cửu", "Huyện Định Quán", "Huyện Trảng Bom", "Huyện Thống Nhất", "Huyện Cẩm Mỹ", "Huyện Long Thành", "Huyện Xuân Lộc", "Huyện Nhơn Trạch"],
   "Đồng Tháp": ["TP. Cao Lãnh", "TP. Sa Đéc", "Thị xã Hồng Ngự", "Huyện Tân Hồng", "Huyện Hồng Ngự", "Huyện Tam Nông", "Huyện Tháp Mười", "Huyện Cao Lãnh", "Huyện Thanh Bình", "Huyện Lấp Vò", "Huyện Lai Vung", "Huyện Châu Thành"],
   "Gia Lai": ["TP. Pleiku", "Thị xã An Khê", "Thị xã Ayun Pa", "Huyện KBang", "Huyện Đăk Đoa", "Huyện Chư Păh", "Huyện Ia Grai", "Huyện Mang Yang", "Huyện Kông Chro", "Huyện Đức Cơ", "Huyện Chư Prông", "Huyện Chư Sê", "Huyện Đăk Pơ", "Huyện Ia Pa", "Huyện Krông Pa", "Huyện Phú Thiện", "Huyện Chư Pưh"],
   "Hà Giang": ["TP. Hà Giang", "Huyện Đồng Văn", "Huyện Mèo Vạc", "Huyện Yên Minh", "Huyện Quản Bạ", "Huyện Vị Xuyên", "Huyện Bắc Mê", "Huyện Hoàng Su Phì", "Huyện Xín Mần", "Huyện Bắc Quang", "Huyện Quang Bình"],
   "Hà Nam": ["TP. Phủ Lý", "Thị xã Duy Tiên", "Huyện Kim Bảng", "Huyện Thanh Liêm", "Huyện Bình Lục", "Huyện Lý Nhân"],
   "Hà Tĩnh": ["TP. Hà Tĩnh", "Thị xã Hồng Lĩnh", "Huyện Hương Sơn", "Huyện Đức Thọ", "Huyện Vũ Quang", "Huyện Nghi Xuân", "Huyện Can Lộc", "Huyện Hương Khê", "Huyện Thạch Hà", "Huyện Cẩm Xuyên", "Huyện Kỳ Anh", "Huyện Lộc Hà", "Thị xã Kỳ Anh"],
   "Hải Dương": ["TP. Hải Dương", "Thị xã Chí Linh", "Huyện Nam Sách", "Huyện Kinh Môn", "Huyện Kim Thành", "Huyện Thanh Hà", "Huyện Cẩm Giàng", "Huyện Bình Giang", "Huyện Gia Lộc", "Huyện Tứ Kỳ", "Huyện Ninh Giang", "Huyện Thanh Miện"],
   "Hậu Giang": ["TP. Vị Thanh", "Thị xã Ngã Bảy", "Huyện Châu Thành A", "Huyện Châu Thành", "Huyện Phụng Hiệp", "Huyện Vị Thuỷ", "Huyện Long Mỹ", "Thị xã Long Mỹ"],
   "Hòa Bình": ["TP. Hòa Bình", "Huyện Đà Bắc", "Huyện Lương Sơn", "Huyện Kim Bôi", "Huyện Cao Phong", "Huyện Tân Lạc", "Huyện Mai Châu", "Huyện Lạc Sơn", "Huyện Yên Thủy", "Huyện Lạc Thủy"],
   "Hưng Yên": ["TP. Hưng Yên", "Huyện Văn Lâm", "Huyện Văn Giang", "Huyện Yên Mỹ", "Thị xã Mỹ Hào", "Huyện Ân Thi", "Huyện Khoái Châu", "Huyện Kim Động", "Huyện Tiên Lữ", "Huyện Phù Cừ"],
   "Khánh Hòa": ["TP. Nha Trang", "TP. Cam Ranh", "Huyện Cam Lâm", "Huyện Vạn Ninh", "Thị xã Ninh Hòa", "Huyện Khánh Vĩnh", "Huyện Diên Khánh", "Huyện Khánh Sơn", "Huyện Trường Sa"],
   "Kiên Giang": ["TP. Rạch Giá", "TP. Hà Tiên", "Huyện Kiên Lương", "Huyện Hòn Đất", "Huyện Tân Hiệp", "Huyện Châu Thành", "Huyện Giồng Riềng", "Huyện Gò Quao", "Huyện An Biên", "Huyện An Minh", "Huyện Vĩnh Thuận", "Huyện Phú Quốc", "Huyện Kiên Hải", "Huyện U Minh Thượng", "Huyện Giang Thành"],
   "Kon Tum": ["TP. Kon Tum", "Huyện Đắk Glei", "Huyện Ngọc Hồi", "Huyện Đắk Tô", "Huyện Kon Plông", "Huyện Kon Rẫy", "Huyện Đắk Hà", "Huyện Sa Thầy", "Huyện Tu Mơ Rông", "Huyện Ia H' Drai"],
   "Lai Châu": ["TP. Lai Châu", "Huyện Tam Đường", "Huyện Mường Tè", "Huyện Sìn Hồ", "Huyện Phong Thổ", "Huyện Than Uyên", "Huyện Tân Uyên", "Huyện Nậm Nhùn"],
   "Lâm Đồng": ["TP. Đà Lạt", "TP. Bảo Lộc", "Huyện Đam Rông", "Huyện Lạc Dương", "Huyện Lâm Hà", "Huyện Đơn Dương", "Huyện Đức Trọng", "Huyện Di Linh", "Huyện Bảo Lâm", "Huyện Đạ Huoai", "Huyện Đạ Tẻh", "Huyện Cát Tiên"],
   "Lạng Sơn": ["TP. Lạng Sơn", "Huyện Tràng Định", "Huyện Bình Gia", "Huyện Văn Lãng", "Huyện Cao Lộc", "Huyện Văn Quan", "Huyện Bắc Sơn", "Huyện Hữu Lũng", "Huyện Chi Lăng", "Huyện Lộc Bình", "Huyện Đình Lập"],
   "Lào Cai": ["TP. Lào Cai", "Huyện Bát Xát", "Huyện Mường Khương", "Huyện Si Ma Cai", "Huyện Bắc Hà", "Huyện Bảo Thắng", "Huyện Bảo Yên", "Huyện Sa Pa", "Huyện Văn Bàn"],
   "Long An": ["TP. Tân An", "Thị xã Kiến Tường", "Huyện Tân Hưng", "Huyện Vĩnh Hưng", "Huyện Mộc Hóa", "Huyện Tân Thạnh", "Huyện Thạnh Hóa", "Huyện Đức Huệ", "Huyện Đức Hòa", "Huyện Bến Lức", "Huyện Thủ Thừa", "Huyện Tân Trụ", "Huyện Cần Đước", "Huyện Cần Giuộc", "Huyện Châu Thành"],
   "Nam Định": ["TP. Nam Định", "Huyện Mỹ Lộc", "Huyện Vụ Bản", "Huyện Ý Yên", "Huyện Nghĩa Hưng", "Huyện Nam Trực", "Huyện Trực Ninh", "Huyện Xuân Trường", "Huyện Giao Thủy", "Huyện Hải Hậu"],
   "Nghệ An": ["TP. Vinh", "Thị xã Cửa Lò", "Thị xã Thái Hoà", "Huyện Quế Phong", "Huyện Quỳ Châu", "Huyện Kỳ Sơn", "Huyện Tương Dương", "Huyện Nghĩa Đàn", "Huyện Quỳ Hợp", "Huyện Quỳnh Lưu", "Huyện Con Cuông", "Huyện Tân Kỳ", "Huyện Anh Sơn", "Huyện Diễn Châu", "Huyện Yên Thành", "Huyện Đô Lương", "Huyện Thanh Chương", "Huyện Nghi Lộc", "Huyện Nam Đàn", "Huyện Hưng Nguyên", "Thị xã Hoàng Mai"],
   "Ninh Bình": ["TP. Ninh Bình", "TP. Tam Điệp", "Huyện Nho Quan", "Huyện Gia Viễn", "Huyện Hoa Lư", "Huyện Yên Khánh", "Huyện Kim Sơn", "Huyện Yên Mô"],
   "Ninh Thuận": ["TP. Phan Rang-Tháp Chàm", "Huyện Bác Ái", "Huyện Ninh Sơn", "Huyện Ninh Hải", "Huyện Ninh Phước", "Huyện Thuận Bắc", "Huyện Thuận Nam"],
   "Phú Thọ": ["TP. Việt Trì", "Thị xã Phú Thọ", "Huyện Đoan Hùng", "Huyện Hạ Hoà", "Huyện Thanh Ba", "Huyện Phù Ninh", "Huyện Yên Lập", "Huyện Cẩm Khê", "Huyện Tam Nông", "Huyện Lâm Thao", "Huyện Thanh Sơn", "Huyện Thanh Thuỷ", "Huyện Tân Sơn"],
   "Phú Yên": ["TP. Tuy Hòa", "Thị xã Sông Cầu", "Huyện Đồng Xuân", "Huyện Tuy An", "Huyện Sơn Hòa", "Huyện Sông Hinh", "Huyện Tây Hoà", "Huyện Phú Hoà", "Thị xã Đông Hòa"],
   "Quảng Bình": ["TP. Đồng Hới", "Huyện Minh Hóa", "Huyện Tuyên Hóa", "Huyện Quảng Trạch", "Huyện Bố Trạch", "Huyện Quảng Ninh", "Huyện Lệ Thủy", "Thị xã Ba Đồn"],
   "Quảng Nam": ["TP. Tam Kỳ", "TP. Hội An", "Huyện Tây Giang", "Huyện Đông Giang", "Huyện Đại Lộc", "Huyện Điện Bàn", "Huyện Duy Xuyên", "Huyện Quế Sơn", "Huyện Nam Giang", "Huyện Phước Sơn", "Huyện Hiệp Đức", "Huyện Thăng Bình", "Huyện Tiên Phước", "Huyện Bắc Trà My", "Huyện Nam Trà My", "Huyện Núi Thành", "Huyện Phú Ninh", "Huyện Nông Sơn"],
   "Quảng Ngãi": ["TP. Quảng Ngãi", "Huyện Bình Sơn", "Huyện Trà Bồng", "Huyện Sơn Tịnh", "Huyện Tư Nghĩa", "Huyện Sơn Hà", "Huyện Sơn Tây", "Huyện Minh Long", "Huyện Nghĩa Hành", "Huyện Mộ Đức", "Huyện Đức Phổ", "Huyện Ba Tơ", "Huyện Lý Sơn"],
   "Quảng Ninh": ["TP. Hạ Long", "TP. Móng Cái", "TP. Cẩm Phả", "TP. Uông Bí", "Huyện Bình Liêu", "Huyện Tiên Yên", "Huyện Đầm Hà", "Huyện Hải Hà", "Huyện Ba Chẽ", "Huyện Vân Đồn", "Thị xã Đông Triều", "Thị xã Quảng Yên", "Huyện Cô Tô"],
   "Quảng Trị": ["TP. Đông Hà", "Thị xã Quảng Trị", "Huyện Vĩnh Linh", "Huyện Hướng Hóa", "Huyện Gio Linh", "Huyện Đa Krông", "Huyện Cam Lộ", "Huyện Triệu Phong", "Huyện Hải Lăng", "Huyện Cồn Cỏ"],
   "Sóc Trăng": ["TP. Sóc Trăng", "Huyện Châu Thành", "Huyện Kế Sách", "Huyện Mỹ Tú", "Huyện Cù Lao Dung", "Huyện Long Phú", "Huyện Mỹ Xuyên", "Thị xã Ngã Năm", "Huyện Thạnh Trị", "Thị xã Vĩnh Châu", "Huyện Trần Đề"],
   "Sơn La": ["TP. Sơn La", "Huyện Quỳnh Nhai", "Huyện Thuận Châu", "Huyện Mường La", "Huyện Bắc Yên", "Huyện Phù Yên", "Huyện Mộc Châu", "Huyện Yên Châu", "Huyện Mai Sơn", "Huyện Sông Mã", "Huyện Sốp Cộp", "Huyện Vân Hồ"],
   "Tây Ninh": ["TP. Tây Ninh", "Huyện Tân Biên", "Huyện Tân Châu", "Huyện Dương Minh Châu", "Huyện Châu Thành", "Huyện Hòa Thành", "Huyện Gò Dầu", "Huyện Bến Cầu", "Thị xã Trảng Bàng"],
   "Thái Bình": ["TP. Thái Bình", "Huyện Quỳnh Phụ", "Huyện Hưng Hà", "Huyện Đông Hưng", "Huyện Thái Thụy", "Huyện Tiền Hải", "Huyện Kiến Xương", "Huyện Vũ Thư"],
   "Thái Nguyên": ["TP. Thái Nguyên", "TP. Sông Công", "Huyện Định Hóa", "Huyện Phú Lương", "Huyện Đồng Hỷ", "Huyện Võ Nhai", "Huyện Đại Từ", "Thị xã Phổ Yên", "Huyện Phú Bình"],
   "Thanh Hóa": ["TP. Thanh Hóa", "Thị xã Bỉm Sơn", "TP. Sầm Sơn", "Huyện Mường Lát", "Huyện Quan Hóa", "Huyện Bá Thước", "Huyện Quan Sơn", "Huyện Lang Chánh", "Huyện Ngọc Lặc", "Huyện Cẩm Thủy", "Huyện Thạch Thành", "Huyện Hà Trung", "Huyện Vĩnh Lộc", "Huyện Yên Định", "Huyện Thọ Xuân", "Huyện Thường Xuân", "Huyện Triệu Sơn", "Huyện Thiệu Hóa", "Huyện Hoằng Hóa", "Huyện Hậu Lộc", "Huyện Nga Sơn", "Huyện Như Xuân", "Huyện Như Thanh", "Huyện Nông Cống", "Huyện Đông Sơn", "Huyện Quảng Xương", "Thị xã Nghi Sơn"],
   "Thừa Thiên Huế": ["TP. Huế", "Huyện Phong Điền", "Huyện Quảng Điền", "Huyện Phú Vang", "Thị xã Hương Thủy", "Thị xã Hương Trà", "Huyện A Lưới", "Huyện Phú Lộc", "Huyện Nam Đông"],
   "Tiền Giang": ["TP. Mỹ Tho", "Thị xã Gò Công", "Thị xã Cai Lậy", "Huyện Tân Phước", "Huyện Cái Bè", "Huyện Cai Lậy", "Huyện Châu Thành", "Huyện Chợ Gạo", "Huyện Gò Công Tây", "Huyện Gò Công Đông", "Huyện Tân Phú Đông"],
   "Trà Vinh": ["TP. Trà Vinh", "Huyện Càng Long", "Huyện Cầu Kè", "Huyện Tiểu Cần", "Huyện Châu Thành", "Huyện Cầu Ngang", "Huyện Trà Cú", "Huyện Duyên Hải", "Thị xã Duyên Hải"],
   "Tuyên Quang": ["TP. Tuyên Quang", "Huyện Lâm Bình", "Huyện Na Hang", "Huyện Chiêm Hóa", "Huyện Hàm Yên", "Huyện Yên Sơn", "Huyện Sơn Dương"],
   "Vĩnh Long": ["TP. Vĩnh Long", "Huyện Long Hồ", "Huyện Mang Thít", "Huyện Vũng Liêm", "Huyện Tam Bình", "Thị xã Bình Minh", "Huyện Trà Ôn", "Huyện Bình Tân"],
   "Vĩnh Phúc": ["TP. Vĩnh Yên", "TP. Phúc Yên", "Huyện Lập Thạch", "Huyện Tam Dương", "Huyện Tam Đảo", "Huyện Bình Xuyên", "Huyện Yên Lạc", "Huyện Vĩnh Tường", "Huyện Sông Lô"],
   "Yên Bái": ["TP. Yên Bái", "Thị xã Nghĩa Lộ", "Huyện Lục Yên", "Huyện Văn Yên", "Huyện Mù Căng Chải", "Huyện Trấn Yên", "Huyện Trạm Tấu", "Huyện Văn Chấn", "Huyện Yên Bình"]
};

// Initialize map after a delay
setTimeout(function() {
    console.log('[MAP] Starting initialization...');
    console.log('[MAP] typeof mapboxgl:', typeof mapboxgl);

    if (typeof mapboxgl === 'undefined') {
        console.error('[MAP] Mapbox not loaded yet, retrying...');
        setTimeout(arguments.callee, 1000);
        return;
    }

    mapboxgl.accessToken = mapboxAccessToken;
    console.log('[MAP] Token set, calling initializeMap...');

    try {
        initializeMap();
        console.log('[MAP] Map initialized successfully!');
    } catch (error) {
        console.error('[MAP] Error initializing:', error);
    }
}, 1000);

// Set up event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const provinceEl = document.getElementById('province');
    const districtEl = document.getElementById('district');
    const detailedAddressEl = document.getElementById('detailed_address');
    const wardEl = document.getElementById('ward');

    // Prevent Enter key from submitting form on address fields
    const addressFields = [provinceEl, districtEl, detailedAddressEl, wardEl];
    addressFields.forEach(field => {
        if (field) {
            field.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    geocodeFullAddress();
                }
            });
        }
    });

    if (provinceEl) {
        provinceEl.addEventListener('change', function() {
            updateDistrictDropdown();
            if (!isReverseGeocoding) {
                zoomToProvince(this.value);
            }
        });
    }

    if (districtEl) {
        districtEl.addEventListener('change', function() {
            const province = provinceEl ? provinceEl.value : '';
            const district = this.value;
            if (!isReverseGeocoding && province && district) {
                zoomToDistrict(province, district);
            }
        });
    }

    if (detailedAddressEl) {
        detailedAddressEl.addEventListener('change', geocodeFullAddress);
    }

    if (wardEl) {
        wardEl.addEventListener('change', geocodeFullAddress);
    }
});

function updateDistrictDropdown() {
    const provinceEl = document.getElementById('province');
    const districtEl = document.getElementById('district');

    if (!provinceEl || !districtEl) return;

    const province = provinceEl.value;

    // Clear existing options
    districtEl.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';

    // Add new options
    if (province && districtData[province]) {
        districtData[province].forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtEl.appendChild(option);
        });
    }
}

function initializeMap() {
    console.log('Initializing map...');
    const mapContainer = document.getElementById('map');

    if (!mapContainer) {
        console.error('Map container not found!');
        return;
    }

    console.log('Map container found:', mapContainer);

    try {
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [106.5, 15.5],
            zoom: 4.5
        });

        console.log('Map created successfully');

        map.addControl(new mapboxgl.NavigationControl());

        // Add marker
        marker = new mapboxgl.Marker({
            draggable: true
        })
        .setLngLat([107.5, 16.5])
        .addTo(map);

        console.log('Marker added successfully');
    } catch (error) {
        console.error('Error initializing map:', error);
    }

    // Reverse geocode on marker drag
    marker.on('dragend', function() {
        const lngLat = marker.getLngLat();
        updateCoordinateInputs(lngLat.lng, lngLat.lat);
        reverseGeocode(lngLat.lng, lngLat.lat);
    });

    // Move marker on map click
    map.on('click', function(e) {
        marker.setLngLat(e.lngLat);
        updateCoordinateInputs(e.lngLat.lng, e.lngLat.lat);
        reverseGeocode(e.lngLat.lng, e.lngLat.lat);
    });
}

function updateCoordinateInputs(lng, lat) {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    if (latInput) latInput.value = lat;
    if (lngInput) lngInput.value = lng;
    console.log('[MAP] Coordinates updated:', lat, lng);
}

function zoomToProvince(province) {
    if (!province) return;

    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(province)}.json?country=VN&access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const coords = data.features[0].center;
                map.flyTo({ center: coords, zoom: 10 });
                marker.setLngLat(coords);
                updateCoordinateInputs(coords[0], coords[1]);
            }
        });
}

function zoomToDistrict(province, district) {
    const query = `${district}, ${province}, Vietnam`;

    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?country=VN&access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const coords = data.features[0].center;
                map.flyTo({ center: coords, zoom: 13 });
                marker.setLngLat(coords);
                updateCoordinateInputs(coords[0], coords[1]);
            }
        });
}

function geocodeFullAddress() {
    const detailed = document.getElementById('detailed_address')?.value;
    const ward = document.getElementById('ward')?.value;
    const district = document.getElementById('district')?.value;
    const province = document.getElementById('province')?.value;

    if (!detailed || !ward || !district || !province) return;

    const fullAddress = `${detailed}, ${ward}, ${district}, ${province}, Vietnam`;

    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(fullAddress)}.json?country=VN&access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const coords = data.features[0].center;
                map.flyTo({ center: coords, zoom: 15 });
                marker.setLngLat(coords);
                updateCoordinateInputs(coords[0], coords[1]);
            }
        });
}

function reverseGeocode(lng, lat) {
    isReverseGeocoding = true;

    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?country=VN&access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const place = data.features[0];

                place.context?.forEach(ctx => {
                    if (ctx.id.includes('place')) {
                        const cityName = ctx.text;
                        const provinceSelect = document.getElementById('province');

                        // Try to match with province options
                        if (provinceSelect) {
                            const options = provinceSelect.querySelectorAll('option');
                            options.forEach(option => {
                                if (option.text.includes(cityName)) {
                                    provinceSelect.value = option.value;
                                    // Trigger change event
                                    const event = new Event('change', { bubbles: true });
                                    provinceSelect.dispatchEvent(event);
                                }
                            });
                        }
                    }

                    if (ctx.id.includes('locality') || ctx.id.includes('district')) {
                        const districtName = ctx.text;
                        setTimeout(() => {
                            const districtSelect = document.getElementById('district');
                            if (districtSelect) {
                                const options = districtSelect.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.text.includes(districtName)) {
                                        districtSelect.value = option.value;
                                    }
                                });
                            }
                        }, 500);
                    }

                    if (ctx.id.includes('neighborhood')) {
                        const wardInput = document.getElementById('ward');
                        if (wardInput) wardInput.value = ctx.text;
                    }

                    // Extract postal code
                    if (ctx.id.includes('postcode')) {
                        const postalCodeInput = document.getElementById('postal_code');
                        if (postalCodeInput) {
                            postalCodeInput.value = ctx.text;
                        }
                    }
                });

                // Extract street name
                if (place.properties?.address || place.text) {
                    const streetName = (place.properties?.address || place.text).replace(/^Đường\s+/i, '');
                    const detailedAddressInput = document.getElementById('detailed_address');
                    if (detailedAddressInput) detailedAddressInput.value = streetName;
                }
            }

            setTimeout(() => { isReverseGeocoding = false; }, 1000);
        })
        .catch(() => {
            isReverseGeocoding = false;
        });
}

console.log('=== INLINE MAP SCRIPT ENDED ===');
})();
</script>
@endsection


