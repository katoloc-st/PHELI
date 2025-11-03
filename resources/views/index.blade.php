@extends('layouts.app')

@section('title', 'Trang chủ - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Main Slider With Form -->
      <section class="site-slider">
         <div id="siteslider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
               <li data-target="#siteslider" data-slide-to="0" class="active"></li>
               <li data-target="#siteslider" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
               <div class="carousel-item active" style="background-image: url('{{ asset('img/background/anh3.jpg') }}')">
                  <div class="overlay"></div>
               </div>
               <div class="carousel-item" style="background-image: url('{{ asset('img/background/anh4.jpg') }}')">
                  <div class="overlay"></div>
               </div>
            </div>
            <a class="carousel-control-prev" href="#siteslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#siteslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
         </div>
         <div class="slider-form">
            <div class="container">
               <div class="text-center mb-5">
                  <h6 class="subtitle mb-1 mt-0 text-shadow text-dark">Hệ thống quản lý phế liệu chuyên nghiệp
                  </h6>
                  <h1 class="display-4 mt-0 font-weight-bold text-shadow">Biến phế liệu thành giá trị
                  </h1>
               </div>
               <form method="GET" action="{{ route('index') }}">
                  <div class="row no-gutters">
                     <div class="col-md-4">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-map-marker-multiple"></i></div>
                           <input class="form-control" name="location" placeholder="Nhập địa điểm tìm kiếm"
                                  type="text" value="{{ request('location') }}">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-google-maps"></i></div>
                           <select class="form-control select2 no-radius" name="province">
                              <option value="">Chọn Tỉnh/Thành phố</option>
                              <option value="An Giang" {{ request('province') == 'An Giang' ? 'selected' : '' }}>An Giang</option>
                              <option value="Bà Rịa - Vũng Tàu" {{ request('province') == 'Bà Rịa - Vũng Tàu' ? 'selected' : '' }}>Bà Rịa - Vũng Tàu</option>
                              <option value="Bắc Giang" {{ request('province') == 'Bắc Giang' ? 'selected' : '' }}>Bắc Giang</option>
                              <option value="Bắc Kạn" {{ request('province') == 'Bắc Kạn' ? 'selected' : '' }}>Bắc Kạn</option>
                              <option value="Bạc Liêu" {{ request('province') == 'Bạc Liêu' ? 'selected' : '' }}>Bạc Liêu</option>
                              <option value="Bắc Ninh" {{ request('province') == 'Bắc Ninh' ? 'selected' : '' }}>Bắc Ninh</option>
                              <option value="Bến Tre" {{ request('province') == 'Bến Tre' ? 'selected' : '' }}>Bến Tre</option>
                              <option value="Bình Định" {{ request('province') == 'Bình Định' ? 'selected' : '' }}>Bình Định</option>
                              <option value="Bình Dương" {{ request('province') == 'Bình Dương' ? 'selected' : '' }}>Bình Dương</option>
                              <option value="Bình Phước" {{ request('province') == 'Bình Phước' ? 'selected' : '' }}>Bình Phước</option>
                              <option value="Bình Thuận" {{ request('province') == 'Bình Thuận' ? 'selected' : '' }}>Bình Thuận</option>
                              <option value="Cà Mau" {{ request('province') == 'Cà Mau' ? 'selected' : '' }}>Cà Mau</option>
                              <option value="Cần Thơ" {{ request('province') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                              <option value="Cao Bằng" {{ request('province') == 'Cao Bằng' ? 'selected' : '' }}>Cao Bằng</option>
                              <option value="Đà Nẵng" {{ request('province') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                              <option value="Đắk Lắk" {{ request('province') == 'Đắk Lắk' ? 'selected' : '' }}>Đắk Lắk</option>
                              <option value="Đắk Nông" {{ request('province') == 'Đắk Nông' ? 'selected' : '' }}>Đắk Nông</option>
                              <option value="Điện Biên" {{ request('province') == 'Điện Biên' ? 'selected' : '' }}>Điện Biên</option>
                              <option value="Đồng Nai" {{ request('province') == 'Đồng Nai' ? 'selected' : '' }}>Đồng Nai</option>
                              <option value="Đồng Tháp" {{ request('province') == 'Đồng Tháp' ? 'selected' : '' }}>Đồng Tháp</option>
                              <option value="Gia Lai" {{ request('province') == 'Gia Lai' ? 'selected' : '' }}>Gia Lai</option>
                              <option value="Hà Giang" {{ request('province') == 'Hà Giang' ? 'selected' : '' }}>Hà Giang</option>
                              <option value="Hà Nam" {{ request('province') == 'Hà Nam' ? 'selected' : '' }}>Hà Nam</option>
                              <option value="Hà Nội" {{ request('province') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                              <option value="Hà Tĩnh" {{ request('province') == 'Hà Tĩnh' ? 'selected' : '' }}>Hà Tĩnh</option>
                              <option value="Hải Dương" {{ request('province') == 'Hải Dương' ? 'selected' : '' }}>Hải Dương</option>
                              <option value="Hải Phòng" {{ request('province') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                              <option value="Hậu Giang" {{ request('province') == 'Hậu Giang' ? 'selected' : '' }}>Hậu Giang</option>
                              <option value="Hòa Bình" {{ request('province') == 'Hòa Bình' ? 'selected' : '' }}>Hòa Bình</option>
                              <option value="Hưng Yên" {{ request('province') == 'Hưng Yên' ? 'selected' : '' }}>Hưng Yên</option>
                              <option value="Khánh Hòa" {{ request('province') == 'Khánh Hòa' ? 'selected' : '' }}>Khánh Hòa</option>
                              <option value="Kiên Giang" {{ request('province') == 'Kiên Giang' ? 'selected' : '' }}>Kiên Giang</option>
                              <option value="Kon Tum" {{ request('province') == 'Kon Tum' ? 'selected' : '' }}>Kon Tum</option>
                              <option value="Lai Châu" {{ request('province') == 'Lai Châu' ? 'selected' : '' }}>Lai Châu</option>
                              <option value="Lâm Đồng" {{ request('province') == 'Lâm Đồng' ? 'selected' : '' }}>Lâm Đồng</option>
                              <option value="Lạng Sơn" {{ request('province') == 'Lạng Sơn' ? 'selected' : '' }}>Lạng Sơn</option>
                              <option value="Lào Cai" {{ request('province') == 'Lào Cai' ? 'selected' : '' }}>Lào Cai</option>
                              <option value="Long An" {{ request('province') == 'Long An' ? 'selected' : '' }}>Long An</option>
                              <option value="Nam Định" {{ request('province') == 'Nam Định' ? 'selected' : '' }}>Nam Định</option>
                              <option value="Nghệ An" {{ request('province') == 'Nghệ An' ? 'selected' : '' }}>Nghệ An</option>
                              <option value="Ninh Bình" {{ request('province') == 'Ninh Bình' ? 'selected' : '' }}>Ninh Bình</option>
                              <option value="Ninh Thuận" {{ request('province') == 'Ninh Thuận' ? 'selected' : '' }}>Ninh Thuận</option>
                              <option value="Phú Thọ" {{ request('province') == 'Phú Thọ' ? 'selected' : '' }}>Phú Thọ</option>
                              <option value="Phú Yên" {{ request('province') == 'Phú Yên' ? 'selected' : '' }}>Phú Yên</option>
                              <option value="Quảng Bình" {{ request('province') == 'Quảng Bình' ? 'selected' : '' }}>Quảng Bình</option>
                              <option value="Quảng Nam" {{ request('province') == 'Quảng Nam' ? 'selected' : '' }}>Quảng Nam</option>
                              <option value="Quảng Ngãi" {{ request('province') == 'Quảng Ngãi' ? 'selected' : '' }}>Quảng Ngãi</option>
                              <option value="Quảng Ninh" {{ request('province') == 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                              <option value="Quảng Trị" {{ request('province') == 'Quảng Trị' ? 'selected' : '' }}>Quảng Trị</option>
                              <option value="Sóc Trăng" {{ request('province') == 'Sóc Trăng' ? 'selected' : '' }}>Sóc Trăng</option>
                              <option value="Sơn La" {{ request('province') == 'Sơn La' ? 'selected' : '' }}>Sơn La</option>
                              <option value="Tây Ninh" {{ request('province') == 'Tây Ninh' ? 'selected' : '' }}>Tây Ninh</option>
                              <option value="Thái Bình" {{ request('province') == 'Thái Bình' ? 'selected' : '' }}>Thái Bình</option>
                              <option value="Thái Nguyên" {{ request('province') == 'Thái Nguyên' ? 'selected' : '' }}>Thái Nguyên</option>
                              <option value="Thanh Hóa" {{ request('province') == 'Thanh Hóa' ? 'selected' : '' }}>Thanh Hóa</option>
                              <option value="Thừa Thiên Huế" {{ request('province') == 'Thừa Thiên Huế' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                              <option value="Tiền Giang" {{ request('province') == 'Tiền Giang' ? 'selected' : '' }}>Tiền Giang</option>
                              <option value="TP Hồ Chí Minh" {{ request('province') == 'TP Hồ Chí Minh' ? 'selected' : '' }}>TP Hồ Chí Minh</option>
                              <option value="Trà Vinh" {{ request('province') == 'Trà Vinh' ? 'selected' : '' }}>Trà Vinh</option>
                              <option value="Tuyên Quang" {{ request('province') == 'Tuyên Quang' ? 'selected' : '' }}>Tuyên Quang</option>
                              <option value="Vĩnh Long" {{ request('province') == 'Vĩnh Long' ? 'selected' : '' }}>Vĩnh Long</option>
                              <option value="Vĩnh Phúc" {{ request('province') == 'Vĩnh Phúc' ? 'selected' : '' }}>Vĩnh Phúc</option>
                              <option value="Yên Bái" {{ request('province') == 'Yên Bái' ? 'selected' : '' }}>Yên Bái</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-security-home"></i></div>
                           <select class="form-control select2 no-radius" name="waste_category">
                              <option value="">DANH MỤC PHẾ LIỆU</option>
                              @foreach($wasteTypes as $wasteType)
                                 <option value="{{ $wasteType->name }}" {{ request('waste_category') == $wasteType->name ? 'selected' : '' }}>
                                    {{ $wasteType->name }}
                                 </option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-block no-radius font-weight-bold">TÌM KIẾM</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
        </section>
      <!-- End Main Slider With Form -->

      <!-- Search Results -->
      @if($posts->count() > 0)
      <section class="section-padding bg-light">
         <div class="container">
            <div class="section-title text-center mb-5">
               <h2>Kết quả tìm kiếm</h2>
               <p>Tìm thấy {{ $posts->count() }} bài đăng phù hợp</p>
            </div>
            <div class="row">
               @foreach($posts as $post)
               <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card h-100">
                     <div class="card-header">
                        <span class="badge {{ $post->type == 'doanh_nghiep_xanh' ? 'bg-success' : 'bg-primary' }}">
                           {{ $post->type == 'doanh_nghiep_xanh' ? 'DOANH NGHIỆP XANH' : 'CƠ SỞ PHẾ LIỆU' }}
                        </span>
                        <small class="float-right text-muted">{{ $post->created_at->diffForHumans() }}</small>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                        <div class="row mb-3">
                           <div class="col-6">
                              <strong>Loại:</strong><br>
                              <span class="text-primary">{{ $post->wasteType->name }}</span>
                           </div>
                           <div class="col-6">
                              <strong>Số lượng:</strong><br>
                              {{ number_format($post->quantity) }} {{ $post->wasteType->unit }}
                           </div>
                        </div>
                        <div class="text-center">
                           <span class="h5 text-success">
                              {{ number_format($post->price) }} VNĐ/{{ $post->wasteType->unit }}
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </section>
      @endif
      <!-- End Search Results -->
      <!-- Properties by City -->
      <section class="section-padding bg-white">
         <div class="section-title text-center mb-5">
            <h2>Thu gom phế liệu theo thành phố</h2>
            <p>Chọn thành phố gần bạn để tìm điểm thu gom phế liệu uy tín.</p>
         </div>
         <div class="container">
            <div class="row">
               <div class="col-lg-3">
                  <div class="card bg-dark text-white card-overlay">
                     <a href="{{ route('index', ['province' => 'Hà Nội']) }}">
                        <img class="card-img"  src="{{ asset('img/city/Hanoi.png') }}" alt="Card image">
                        <div class="card-img-overlay">
                           <h3 class="card-title text-white">Hà Nội</h3>
                           <p class="card-text text-white">{{ $stats['total_posts'] ?? 0 }} bài đăng</p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="col-lg-3">
                  <div class="card bg-dark text-white card-overlay">
                     <a href="{{ route('index', ['province' => 'Đà Nẵng']) }}">
                        <img class="card-img"  src="{{ asset('img/city/DaNang.jpg') }}" alt="Card image">
                        <div class="card-img-overlay">
                           <h3 class="card-title text-white">Đà Nẵng</h3>
                           <p class="card-text text-white">{{ intval($stats['total_posts'] * 0.3) ?? 0 }} bài đăng</p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="col-lg-3">
                  <div class="card bg-dark text-white card-overlay">
                     <a href="{{ route('index', ['province' => 'Bình Định']) }}">
                        <img class="card-img"  src="{{ asset('img/city/Binh_Dinh.jpg') }}" alt="Card image">
                        <div class="card-img-overlay">
                           <h3 class="card-title text-white">Bình Định</h3>
                           <p class="card-text text-white">{{ intval($stats['total_posts'] * 0.2) ?? 0 }} bài đăng</p>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="col-lg-3">
                  <div class="card bg-dark text-white card-overlay">
                     <a href="{{ route('index', ['province' => 'TP Hồ Chí Minh']) }}">
                        <img class="card-img"  src="{{ asset('img/city/TPHCM.jpg') }}" alt="Card image">
                        <div class="card-img-overlay">
                           <h3 class="card-title text-white">TP.HCM</h3>
                           <p class="card-text text-white">{{ intval($stats['total_posts'] * 0.5) ?? 0 }} bài đăng</p>
                        </div>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Properties by City -->
      <!-- Properties List -->
      <section class="section-padding">
         <div class="section-title text-center mb-5">
            <h2>Bảng Giá Phế Liệu Mới Nhất</h2>
            <p>Cập nhật giá thu mua phế liệu hôm nay, chính xác và nhanh chóng.</p>
         </div>
         <div class="container">
            <div class="row">
               @foreach($currentPrices->take(6) as $priceTable)
               <div class="col-lg-4 col-md-4">
                  <div class="card card-list">
                     <a href="{{ route('index', ['waste_category' => $priceTable->wasteType->name]) }}">
                        <div class="scrap-img-wrap">
                           <img class="card-img-top"  src="{{ asset('img/scrap/' . $priceTable->wasteType->name . '.jpg') }}"
                                alt="{{ $priceTable->wasteType->name }}"
                                onerror="this.src='{{ asset('img/scrap/default.jpg') }}'">
                        </div>
                        <div class="card-body text-center">
                           <h5 class="card-title mb-2">{{ $priceTable->wasteType->name }}</h5>
                           <div class="scrap-price-block mb-2">
                              <span class="scrap-price text-success font-weight-bold">
                                 {{ number_format($priceTable->price) }} VNĐ
                                 <span class="text-muted">/ {{ $priceTable->wasteType->unit }}</span>
                              </span>
                           </div>
                           <div class="scrap-change-block">
                              @php
                                 $changePercent = $priceChanges[$priceTable->waste_type_id] ?? 0;
                              @endphp
                              <span class="scrap-change {{ $changePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                 <i class="mdi {{ $changePercent >= 0 ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }}"></i>
                                 {{ $changePercent >= 0 ? '+' : '' }}{{ number_format($changePercent, 1) }}%
                              </span>
                           </div>
                        </div>
                     </a>
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </section>
      <!-- End Properties List -->
      <!-- Plans -->
      <section class="section-padding">
         <div class="section-title text-center mb-5">
            <h2>Danh mục phế liệu</h2>
            <p>Chọn loại phế liệu bạn quan tâm:</p>
         </div>
         <div class="container">
            <div class="row justify-content-center">
               <div class="col-12">
                  <div class="scrap-materials-grid">
                     @foreach($wasteTypes as $wasteType)
                        <a href="{{ route('index', ['waste_category' => $wasteType->name]) }}" class="scrap-btn">
                           {{ $wasteType->name }}
                        </a>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Plans -->

      <!-- Recent Posts -->
      <section class="section-padding bg-light">
         <div class="section-title text-center mb-5">
            <h2>Bài đăng mới nhất</h2>
            <p>Cập nhật thông tin mua bán phế liệu mới nhất từ cộng đồng</p>
         </div>
         <div class="container">
            <div class="row">
               @foreach($recentPosts as $post)
               <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card h-100">
                     <div class="card-header">
                        <span class="badge {{ $post->type == 'doanh_nghiep_xanh' ? 'bg-success' : 'bg-primary' }}">
                           {{ $post->type == 'doanh_nghiep_xanh' ? 'DOANH NGHIỆP XANH' : 'CƠ SỞ PHẾ LIỆU' }}
                        </span>
                        <small class="float-right text-muted">{{ $post->created_at->diffForHumans() }}</small>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->description, 80) }}</p>
                        <div class="row mb-2">
                           <div class="col-6">
                              <small><strong>Loại:</strong></small><br>
                              <span class="text-primary">{{ $post->wasteType->name }}</span>
                           </div>
                           <div class="col-6">
                              <small><strong>Số lượng:</strong></small><br>
                              {{ number_format($post->quantity) }} {{ $post->wasteType->unit }}
                           </div>
                        </div>
                        <div class="text-center mb-2">
                           <span class="h6 text-success">
                              {{ number_format($post->price) }} VNĐ/{{ $post->wasteType->unit }}
                           </span>
                        </div>
                        @if($post->location)
                           <small class="text-muted">
                              <i class="mdi mdi-map-marker"></i> {{ $post->location }}
                           </small>
                        @endif
                     </div>
                     <div class="card-footer">
                        <small class="text-muted">
                           Đăng bởi: {{ $post->user->name }}
                           <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $post->user->role)) }}</span>
                        </small>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            <div class="text-center mt-4">
               @auth
                  <a href="{{ route('posts.create') }}" class="btn btn-success me-3">
                     <i class="mdi mdi-plus"></i> Đăng bài mới
                  </a>
               @else
                  <a href="{{ route('logon') }}" class="btn btn-success me-3">
                     <i class="mdi mdi-account-plus"></i> Đăng nhập để đăng bài
                  </a>
               @endauth
               <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                  <i class="mdi mdi-view-list"></i> Xem tất cả bài đăng
               </a>
            </div>
         </div>
      </section>
      <!-- End Recent Posts -->
      <!-- Statistics -->
      {{-- <section class="py-5 bg-primary">
         <div class="container">
            <div class="row text-center text-white">
               <div class="col-md-3">
                  <div class="stat-item">
                     <h3 class="mb-2">{{ number_format($stats['total_posts']) }}</h3>
                     <p class="mb-0">Bài đăng đang hoạt động</p>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="stat-item">
                     <h3 class="mb-2">{{ number_format($stats['total_transactions']) }}</h3>
                     <p class="mb-0">Giao dịch thành công</p>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="stat-item">
                     <h3 class="mb-2">{{ number_format($stats['total_users']) }}</h3>
                     <p class="mb-0">Người dùng đăng ký</p>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="stat-item">
                     <h3 class="mb-2">{{ number_format($stats['total_waste_types']) }}</h3>
                     <p class="mb-0">Loại phế liệu</p>
                  </div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-12 text-center">
                  @guest
                     <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg me-3">
                        <i class="mdi mdi-account-plus"></i> Tham gia ngay
                     </a>
                     <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-login"></i> Đăng nhập
                     </a>
                  @else
                     <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-light btn-lg me-3">
                        <i class="mdi mdi-view-dashboard"></i> Bảng điều khiển
                     </a>
                     <a href="{{ route('posts.create') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-plus"></i> Đăng bài mới
                     </a>
                  @endguest
               </div>
            </div>
         </div>
      </section> --}}
      <!-- End Statistics -->
@endsection

@section('custom-js')
<script>
// Custom functionality for waste category links
document.querySelectorAll('.scrap-btn').forEach(function(btn) {
   btn.addEventListener('click', function(e) {
      if (!this.getAttribute('href')) {
         e.preventDefault();
         const wasteType = this.textContent.trim();
         window.location.href = `{{ route('index') }}?waste_category=${encodeURIComponent(wasteType)}`;
      }
   });
});

// Auto-submit search form when province or waste category changes
document.querySelectorAll('select[name="province"], select[name="waste_category"]').forEach(function(select) {
   select.addEventListener('change', function() {
      if (this.value) {
         // Submit form automatically when user selects a value
         this.closest('form').submit();
      }
   });
});

// Clear search functionality
function clearSearch() {
   window.location.href = '{{ route("index") }}';
}
</script>
@endsection
