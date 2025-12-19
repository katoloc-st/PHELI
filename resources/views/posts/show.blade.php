@extends('layouts.app')

@section('title', $post->title . ' - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Property Single Slider header -->
      <section class="site-slider">
         <div id="siteslider" class="carousel slide" data-ride="carousel">
            @php
               $images = is_string($post->images) ? json_decode($post->images, true) : $post->images;
            @endphp
            @if($images && is_array($images) && count($images) > 0)
               <ol class="carousel-indicators">
                  @foreach($images as $index => $image)
                     <li data-target="#siteslider" data-slide-to="{{ $index }}" @if($index == 0) class="active" @endif></li>
                  @endforeach
               </ol>
               <div class="carousel-inner" role="listbox">
                  @foreach($images as $index => $image)
                     <div class="carousel-item @if($index == 0) active @endif" style="background-image: url('{{ asset('storage/' . $image) }}')"></div>
                  @endforeach
               </div>
            @else
               <div class="carousel-inner" role="listbox">
                  <div class="carousel-item active" style="background-image: url('{{ asset('img/slider/1.jpg') }}')"></div>
               </div>
            @endif
            <a class="carousel-control-prev" href="#siteslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#siteslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
         </div>
         <div class="property-single-title property-single-title-gallery">
            <div class="container">
               <div class="row">
                  <div class="col-lg-8 col-md-8">
                     <h1>{{ $post->title }}</h1>
                     <h6><i class="mdi mdi-home-map-marker"></i> {{ $post->collectionPoint->full_address }}</h6>
                  </div>
                  <div class="col-lg-4 col-md-4 text-right">
                     <h6 class="mt-2">Loại phế liệu: {{ $post->wasteType->name }}</h6>
                     <h2 class="text-success">{{ number_format($post->price, 0, ',', '.') }} <small>VNĐ/kg</small></h2>
                  </div>
               </div>
               <hr>
               <div class="row align-items-center">
                  <div class="col-lg-8 col-md-8">
                     <div class="d-flex gap-3 flex-wrap align-items-center">
                        <button type="button" class="btn btn-primary btn-lg btn-buy-now" onclick="buyNow({{ $post->id }})">
                           Mua ngay
                        </button>
                        <button type="button" class="btn btn-icon" onclick="addToCart({{ $post->id }})" title="Thêm vào giỏ hàng">
                           <i class="fas fa-shopping-bag"></i>
                        </button>
                        <button type="button" class="btn btn-icon" onclick="addToWishlist({{ $post->id }})" title="Yêu thích">
                           <i class="far fa-heart"></i>
                        </button>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-4 text-right">
                     <div class="footer-social">
                        <span>Chia sẻ :</span> &nbsp;
                        <a href="#"><i class="mdi mdi-facebook"></i></a>
                        <a href="#"><i class="mdi mdi-twitter"></i></a>
                        <a href="#"><i class="mdi mdi-instagram"></i></a>
                        <a href="#"><i class="mdi mdi-google"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Property Single Slider header -->
      <!-- Property Single Slider -->
      <section class="section-padding">
         <div class="container">
            <div class="row">
               <div class="col-lg-8 col-md-8">
                  <div class="card">
                     <div class="card-body site-slider pl-0 pr-0 pt-0 pb-0">
                        <div id="sitesliderz" class="carousel slide" data-ride="carousel">
                           @php
                              $images = is_string($post->images) ? json_decode($post->images, true) : $post->images;
                           @endphp
                           @if($images && is_array($images) && count($images) > 0)
                              <ol class="carousel-indicators">
                                 @foreach($images as $index => $image)
                                    <li data-target="#sitesliderz" data-slide-to="{{ $index }}" @if($index == 0) class="active" @endif></li>
                                 @endforeach
                              </ol>
                              <div class="carousel-inner" role="listbox">
                                 @foreach($images as $index => $image)
                                    <div class="carousel-item @if($index == 0) active @endif rounded" style="background-image: url('{{ asset('storage/' . $image) }}')"></div>
                                 @endforeach
                              </div>
                           @else
                              <div class="carousel-inner" role="listbox">
                                 <div class="carousel-item active rounded" style="background-image: url('{{ asset('img/slider/3.jpg') }}')"></div>
                              </div>
                           @endif
                           <a class="carousel-control-prev" href="#sitesliderz" role="button" data-slide="prev">
                           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                           <span class="sr-only">Previous</span>
                           </a>
                           <a class="carousel-control-next" href="#sitesliderz" role="button" data-slide="next">
                           <span class="carousel-control-next-icon" aria-hidden="true"></span>
                           <span class="sr-only">Next</span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-6 col-md-6">
                        <div class="list-icon">
                           <i class="mdi mdi-cube"></i>
                           <strong>Loại phế liệu:</strong>
                           <p class="mb-0">{{ $post->wasteType->name }}</p>
                        </div>
                     </div>
                     <div class="col-lg-6 col-md-6">
                        <div class="list-icon">
                           <i class="mdi mdi-scale"></i>
                           <strong>Số lượng:</strong>
                           <p class="mb-0">{{ $post->quantity }} kg</p>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-6 col-md-6">
                        <div class="list-icon">
                           <i class="mdi mdi-account"></i>
                           <strong>Người đăng:</strong>
                           <p class="mb-0">{{ $post->user->role }}</p>
                        </div>
                     </div>
                     <div class="col-lg-6 col-md-6">
                        <div class="list-icon">
                           <i class="mdi mdi-calendar"></i>
                           <strong>Ngày đăng:</strong>
                           <p class="mb-0">{{ $post->created_at->format('d/m/Y') }}</p>
                        </div>
                     </div>
                  </div>

                  <div class="card padding-card">
                     <div class="card-body">
                        <h5 class="card-title mb-3">Mô tả chi tiết</h5>
                        <p>{{ $post->description }}</p>

                        @if($post->address_note)
                           <h6 class="mt-3">Ghi chú địa chỉ:</h6>
                           <p class="mb-0">{{ $post->address_note }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="card padding-card">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Video</h5>
                        <a href="#"><img class="rounded img-fluid" src="img/video.jpg" alt="Card image cap"></a>
                     </div>
                  </div>
                  <div class="card padding-card reviews-card">
                     <div class="card-body">
                        <h5 class="card-title mb-4">
                           Đánh giá ({{ $post->reviews_count }})
                           @if($post->reviews_count > 0)
                              <span class="star-rating ml-2">
                                 @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($post->average_rating))
                                       <i class="mdi mdi-star text-warning"></i>
                                    @elseif($i <= ceil($post->average_rating))
                                       <i class="mdi mdi-star-half text-warning"></i>
                                    @else
                                       <i class="mdi mdi-star-outline text-muted"></i>
                                    @endif
                                 @endfor
                                 <small class="text-muted">({{ number_format($post->average_rating, 1) }}/5)</small>
                              </span>
                           @endif
                        </h5>

                        @if($post->approvedReviews->count() > 0)
                           @foreach($post->approvedReviews as $review)
                              <div class="media @if(!$loop->first) mt-4 @endif">
                                 @if($review->user->avatar)
                                    <img class="d-flex mr-3 rounded-circle"
                                         src="{{ asset('storage/' . $review->user->avatar) }}"
                                         alt="{{ $review->user->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                 @else
                                    <img class="d-flex mr-3 rounded-circle"
                                         src="{{ asset('img/user/1.jpg') }}"
                                         alt="{{ $review->user->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                 @endif

                                 <div class="media-body">
                                    <h5 class="mt-0">
                                       {{ $review->user->name }}
                                       <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                       <span class="star-rating float-right">
                                          @for($i = 1; $i <= 5; $i++)
                                             @if($i <= $review->rating)
                                                <i class="mdi mdi-star text-warning"></i>
                                             @else
                                                <i class="mdi mdi-star-outline text-muted"></i>
                                             @endif
                                          @endfor
                                          <small class="text-success">{{ $review->rating }}/5</small>
                                       </span>
                                    </h5>
                                    <p class="mb-0">{{ $review->content }}</p>

                                    @auth
                                       @if($review->user_id === Auth::id())
                                          <div class="mt-2">
                                             <small class="text-muted">Đánh giá của bạn</small>
                                             <button type="button" class="btn btn-sm btn-outline-danger ml-2"
                                                     onclick="deleteReview({{ $review->id }})">
                                                <i class="mdi mdi-delete"></i> Xóa
                                             </button>
                                          </div>
                                       @endif
                                    @endauth
                                 </div>
                              </div>
                           @endforeach
                        @else
                           <div class="alert alert-info">
                              <i class="mdi mdi-information"></i> Chưa có đánh giá nào cho bài đăng này.
                           </div>
                        @endif
                     </div>
                  </div>
                  @auth
                     @if(!$userReview && Auth::id() !== $post->user_id)
                        <div class="card padding-card">
                           <div class="card-body">
                              <h5 class="card-title mb-4">Đưa ra đánh giá của bạn</h5>

                              @if($errors->any())
                                 <div class="alert alert-danger">
                                    <ul class="mb-0">
                                       @foreach($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                       @endforeach
                                    </ul>
                                 </div>
                              @endif

                              <form action="{{ route('reviews.store', $post) }}" method="POST">
                                 @csrf
                                 <div class="form-group">
                                    <label for="rating">Đánh giá <span class="text-danger">*</span></label>
                                    <select class="form-control @error('rating') is-invalid @enderror"
                                            id="rating" name="rating" required>
                                       <option value="">Chọn số sao</option>
                                       <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 sao - Xuất sắc</option>
                                       <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 sao - Tốt</option>
                                       <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 sao - Bình thường</option>
                                       <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ 2 sao - Kém</option>
                                       <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ 1 sao - Rất kém</option>
                                    </select>
                                    @error('rating')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                 </div>

                                 <div class="form-group">
                                    <label for="content">Nội dung đánh giá <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="4"
                                              placeholder="Chia sẻ trải nghiệm của bạn về bài đăng này..."
                                              required>{{ old('content') }}</textarea>
                                    @error('content')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tối thiểu 10 ký tự, tối đa 1000 ký tự.</small>
                                 </div>

                                 <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-send"></i> Gửi đánh giá
                                 </button>
                              </form>
                           </div>
                        </div>
                     @elseif($userReview)
                        <div class="card padding-card">
                           <div class="card-body">
                              <h5 class="card-title mb-4">Đánh giá của bạn</h5>
                              <div class="alert alert-success">
                                 <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                       <strong>Đánh giá: </strong>
                                       @for($i = 1; $i <= 5; $i++)
                                          @if($i <= $userReview->rating)
                                             <i class="mdi mdi-star text-warning"></i>
                                          @else
                                             <i class="mdi mdi-star-outline text-muted"></i>
                                          @endif
                                       @endfor
                                       ({{ $userReview->rating }}/5)
                                       <br>
                                       <strong>Nội dung: </strong>{{ $userReview->content }}
                                       <br>
                                       <small class="text-muted">Đánh giá vào {{ $userReview->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteReview({{ $userReview->id }})">
                                       <i class="mdi mdi-delete"></i>
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     @elseif(Auth::id() === $post->user_id)
                        <div class="card padding-card">
                           <div class="card-body">
                            <h5 class="card-title mb-4">Đưa ra đánh giá của bạn</h5>
                              <div class="alert alert-info">
                                 <i class="mdi mdi-information"></i> Bạn không thể đánh giá bài đăng của chính mình.
                              </div>
                           </div>
                        </div>
                     @endif
                  @else
                     <div class="card padding-card">
                        <div class="card-body">
                           <div class="alert alert-warning">
                              <i class="mdi mdi-account-alert"></i> Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để có thể đánh giá bài đăng này.
                           </div>
                        </div>
                     </div>
                  @endauth
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="card sidebar-card">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin người bán</h5>
                        <div id="featured-properties">
                           <div class="carousel-inner">
                              <div class="carousel-item active">
                                 <div class="card card-list">
                                    <a href="#">
                                        @if($post->user->company_logo)
                                            <img class="card-img-top"
                                                src="{{ asset('storage/' . $post->user->company_logo) }}"
                                                alt="Ảnh người bán" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <img class="card-img-top"
                                                src="{{ asset('img/agent.jpg') }}"
                                                alt="Ảnh người bán" style="width: 100px; height: 100px; object-fit: cover;">
                                        @endif
                                       <div class="card-body pb-0">
                                          <h5 class="card-title mb-4">{{ $post->user->company_name }}</h5>
                                          @php
                                                $roleDisplay = '';
                                                switch($post->user->role) {
                                                case 'scrap_dealer':
                                                    $roleDisplay = 'Cơ sở phế liệu';
                                                    break;
                                                case 'waste_company':
                                                    $roleDisplay = 'Doanh nghiệp xanh';
                                                    break;
                                                case 'admin':
                                                    $roleDisplay = 'Quản trị viên';
                                                    break;
                                                default:
                                                    $roleDisplay = $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : 'Chưa xác định';
                                                }
                                            @endphp
                                          <h6 class="card-subtitle mb-3 text-muted"><i class="mdi mdi-domain"></i> {{ $roleDisplay }}</h6>
                                          <h6 class="card-subtitle mb-3 text-muted"><i class="mdi mdi-phone"></i> {{ $post->user->phone }}</h6>
                                          <h6 class="card-subtitle mb-3 text-muted"><i class="mdi mdi-email"></i> {{ $post->user->email }}</h6>
                                          <h6 class="card-subtitle text-muted"><i class="mdi mdi-link"></i> {{ $post->user->website }}</h6>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card sidebar-card">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Địa chỉ đăng bán</h5>
                        <div class="address-info">
                           <p class="mb-2"><i class="mdi mdi-map-marker text-success"></i> <strong>Địa chỉ cụ thể:</strong></p>
                           <p class="mb-3">{{ $post->collectionPoint->detailed_address }}</p>

                           <p class="mb-2"><i class="mdi mdi-city text-success"></i> <strong>Khu vực:</strong></p>
                           <p class="mb-2">{{ $post->collectionPoint->ward }}</p>
                           <p class="mb-3">{{ $post->collectionPoint->province }}</p>

                           @if($post->collectionPoint->postal_code)
                              <p class="mb-1"><i class="mdi mdi-mailbox text-success"></i> <strong>Mã bưu điện:</strong> {{ $post->collectionPoint->postal_code }}</p>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="card sidebar-card">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Bài đăng nổi bật</h5>
                        <div id="featured-properties" class="carousel slide" data-ride="carousel">
                           <ol class="carousel-indicators">
                              <li data-target="#featured-properties" data-slide-to="0" class="active"></li>
                              <li data-target="#featured-properties" data-slide-to="1"></li>
                           </ol>
                           <div class="carousel-inner">
                              <div class="carousel-item active">
                                 <div class="card card-list">
                                    <a href="#">
                                       <span class="badge badge-success">Bán</span>
                                       <img class="card-img-top" src="img/list/1.png" alt="Sắt vụn công nghiệp">
                                       <div class="card-body">
                                          <h5 class="card-title">Sắt vụn công nghiệp</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"><i class="mdi mdi-home-map-marker"></i> TP.HCM, Quận 1</h6>
                                          <h2 class="text-success mb-0 mt-3">
                                             10.000.000 <small>VNĐ</small>
                                          </h2>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                              <div class="carousel-item">
                                 <div class="card card-list">
                                    <a href="#">
                                       <span class="badge badge-secondary">Bán</span>
                                       <img class="card-img-top" src="img/list/2.png" alt="Nhựa phế liệu PE, PP">
                                       <div class="card-body">
                                          <h5 class="card-title">Nhựa phế liệu PE, PP</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"><i class="mdi mdi-home-map-marker"></i> Bình Dương</h6>
                                          <h2 class="text-success mb-0 mt-3">
                                             5.000.000 <small>VNĐ</small>
                                          </h2>
                                       </div>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Property Single Slider -->
      <!-- Similar Properties -->
      <section class="section-padding  border-top">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 col-md-12 section-title text-left mb-4">
                  <h2>Các bài đăng liên kết</h2>
               </div>

               @if($linkedPosts->count() > 0)
                  @foreach($linkedPosts->take(3) as $linkedPost)
                     <div class="col-lg-4 col-md-4">
                        <div class="card card-list">
                           <a href="{{ route('posts.show', $linkedPost) }}">
                              <span class="badge badge-success">Đang bán</span>

                              @php
                                 $linkedImages = is_string($linkedPost->images) ? json_decode($linkedPost->images, true) : $linkedPost->images;
                              @endphp
                              @if($linkedImages && is_array($linkedImages) && count($linkedImages) > 0)
                                 <img class="card-img-top" src="{{ asset('storage/' . $linkedImages[0]) }}" alt="{{ $linkedPost->title }}" style="height: 250px; object-fit: cover;">
                              @else
                                 <img class="card-img-top" src="{{ asset('img/list/1.png') }}" alt="{{ $linkedPost->title }}" style="height: 250px; object-fit: cover;">
                              @endif

                              <div class="card-body">
                                 <h5 class="card-title" style="height: 3em; line-height: 1.5em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $linkedPost->title }}</h5>
                                 <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="mdi mdi-home-map-marker"></i> {{ $linkedPost->collectionPoint->district }}, {{ $linkedPost->collectionPoint->province }}
                                 </h6>
                                 <h2 class="text-success mb-0 mt-3">
                                    {{ number_format($linkedPost->price, 0, ',', '.') }} <small>VNĐ/kg</small>
                                 </h2>
                                 <div class="mt-2">
                                    <p class="mt-2 mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($linkedPost->description, 80) }}</p>
                                    <span class="d-block"><i class="mdi mdi-cube"></i> Loại phế liệu: <strong>{{ $linkedPost->wasteType->name }}</strong></span>
                                    <span class="d-block"><i class="mdi mdi-scale"></i> Số lượng: <strong>{{ $linkedPost->quantity }} kg</strong></span>
                                 </div>
                              </div>
                              <div class="card-footer">
                                 <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center" style="flex: 1; min-width: 0; margin-right: 10px;">
                                       @if($linkedPost->user->company_logo)
                                          <img src="{{ asset('storage/' . $linkedPost->user->company_logo) }}"
                                               alt="{{ $linkedPost->user->company_name }}"
                                               class="rounded-circle mr-2"
                                               style="width: 40px; height: 40px; object-fit: cover; flex-shrink: 0;">
                                       @else
                                          <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                               style="width: 40px; height: 40px; background-color: #28a745; color: white; font-weight: bold; font-size: 16px; flex-shrink: 0;">
                                             {{ strtoupper(substr($linkedPost->user->company_name, 0, 1)) }}
                                          </div>
                                       @endif
                                       <span style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong>{{ $linkedPost->user->company_name }}</strong></span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 12px; flex-shrink: 0;">
                                       <a href="#" class="text-dark" onclick="event.preventDefault(); event.stopPropagation(); addToWishlist({{ $linkedPost->id }});" title="Thêm vào yêu thích">
                                          <i class="far fa-heart" style="font-size: 24px;"></i>
                                       </a>
                                       <a href="#" class="text-dark" onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $linkedPost->id }});" title="Thêm vào giỏ hàng">
                                          <i class="fas fa-shopping-bag" style="font-size: 24px;"></i>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </a>
                        </div>
                     </div>
                  @endforeach
               @else
                  <div class="col-lg-12">
                     <div class="alert alert-info text-center">
                        <i class="mdi mdi-information"></i> Chưa có bài đăng nào liên kết với bài đăng này.
                     </div>
                  </div>
               @endif
            </div>
         </div>
      </section>
      <!-- End Similar Properties -->
      <!-- Join Team -->
      <section class="py-5 bg-primary">
         <div class="container">
            <div class="row align-items-center text-center text-md-left">
               <div class="col-md-8">
                  <h2 class="text-white my-2">Join Our Professional Team & Agents</h2>
               </div>
               <div class="col-md-4 text-center text-md-right">
                  <button type="button" class="btn btn-outline-light my-2">Read More</button> <button type="button"
                     class="btn btn-light my-2">Contact Us</button>
               </div>
            </div>
         </div>
      </section>
      <!-- End Join Team -->
@endsection

@section('custom-js')
<script>
function deleteReview(reviewId) {
    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')) {
        // Tạo form để submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/reviews/${reviewId}`;

        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        document.body.appendChild(form);
        form.submit();
    }
}

// Character counter for review content
document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    if (contentTextarea) {
        const maxLength = 1000;

        // Create counter element
        const counter = document.createElement('small');
        counter.className = 'form-text text-muted float-right';
        counter.id = 'content-counter';
        contentTextarea.parentNode.appendChild(counter);

        function updateCounter() {
            const currentLength = contentTextarea.value.length;
            counter.textContent = `${currentLength}/${maxLength} ký tự`;

            if (currentLength > maxLength * 0.9) {
                counter.className = 'form-text text-warning float-right';
            } else {
                counter.className = 'form-text text-muted float-right';
            }
        }

        contentTextarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial call
    }
});

// Mua ngay
function buyNow(postId) {
    // Thêm vào giỏ hàng và chuyển đến trang thanh toán
    if (typeof window.addToCart === 'function') {
        window.addToCart(postId);
        // Chờ một chút để đảm bảo đã thêm vào giỏ hàng, sau đó chuyển trang
        setTimeout(function() {
            window.location.href = '{{ route('cart.index') }}';
        }, 500);
    } else {
        alert('Vui lòng đăng nhập để mua hàng!');
        window.location.href = '{{ route('login') }}';
    }
}

// Thêm vào danh sách yêu thích
function addToWishlist(postId) {
    console.log('Thêm bài đăng ' + postId + ' vào danh sách yêu thích');
    // TODO: Implement wishlist functionality
    alert('Đã thêm vào danh sách yêu thích! Chức năng này đang được phát triển.');
}
</script>
@endsection

@push('styles')
<style>
/* Action Buttons */
.property-single-title .d-flex {
    gap: 15px;
}

.property-single-title .btn-buy-now {
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.property-single-title .btn-buy-now:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    transform: translateY(-2px);
}

/* Icon Buttons - No Border */
.property-single-title .btn-icon {
    width: 50px;
    height: 50px;
    padding: 0;
    border: none;
    background: transparent;
    color: #333;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.property-single-title .btn-icon:hover {
    background: #f8f9fa;
    transform: scale(1.1);
    color: #667eea;
}

.property-single-title .btn-icon:focus {
    outline: none;
    box-shadow: none;
}

.property-single-title .btn-icon i {
    font-size: 24px;
}

/* Responsive buttons */
@media (max-width: 768px) {
    .property-single-title .d-flex {
        flex-direction: row;
        justify-content: flex-start;
        width: 100%;
    }

    .property-single-title .btn-buy-now {
        flex: 1;
    }
}/* Review Styles */
.reviews-card .media {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 15px;
}

.reviews-card .media:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.star-rating i {
    font-size: 16px;
}

.star-rating small {
    margin-left: 5px;
}

.review-form .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.review-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 10px;
    margin-top: 10px;
}

/* Linked Posts Styles */
.section-title h2 small {
    font-size: 0.6em;
    font-weight: normal;
}

.card-list .card-body .d-block {
    font-size: 0.9em;
    margin-bottom: 3px;
}

.card-list .card-body .d-block i {
    width: 16px;
    text-align: center;
    margin-right: 5px;
}

.card-list .badge {
    font-size: 0.8em;
    padding: 4px 8px;
}

.card-list:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.carousel-inner .carousel-item {
    height: 400px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.site-slider .carousel-inner .carousel-item {
    height: 300px;
}
</style>
@endpush
