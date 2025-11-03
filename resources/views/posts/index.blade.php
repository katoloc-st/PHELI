@extends('layouts.app')

@section('title', 'Danh sách bài đăng - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Main Slider With Form -->
      <section class="site-slider">
         <div id="siteslider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
               <li data-target="#siteslider" data-slide-to="0" class="active"></li>
               <li data-target="#siteslider" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
               <div class="carousel-item active" style="background-image: url('{{ asset('img/slider/1.jpg') }}')">
                  <div class="overlay"></div>
               </div>
               <div class="carousel-item" style="background-image: url('{{ asset('img/slider/2.jpg') }}')">
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
         <div class="slider-form inner-page-form">
            <div class="container">
               <h1 class="text-center mb-5">Tìm kiếm bài đăng phế liệu</h1>
               <form method="GET" action="{{ route('posts.index') }}">
                  <div class="row no-gutters">
                     <div class="col-lg-2 col-md-6 col-sm-12 mb-2">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-magnify"></i></div>
                           <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        </div>
                     </div>
                     <div class="col-lg-2 col-md-6 col-sm-12 mb-2">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-city"></i></div>
                           <select class="form-control select2" name="type">
                              <option value="">Tất cả đối tượng</option>
                              <option value="co_so_phe_lieu" {{ request('type') == 'co_so_phe_lieu' ? 'selected' : '' }}>Cơ sở phế liệu</option>
                              <option value="doanh_nghiep_xanh" {{ request('type') == 'doanh_nghiep_xanh' ? 'selected' : '' }}>Doanh nghiệp xanh</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-home-modern"></i></div>
                           <select class="form-control select2" name="waste_type_id">
                              <option value="">Tất cả loại phế liệu</option>
                              @foreach($wasteTypes as $wasteType)
                                 <option value="{{ $wasteType->id }}" {{ request('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                    {{ $wasteType->name }}
                                 </option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                        <div class="input-group">
                           <div class="input-group-addon"><i class="mdi mdi-map-marker"></i></div>
                           <select class="form-control select2" name="province">
                              <option value="">Tất cả khu vực</option>
                              @foreach($provinces as $province)
                                 <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                 </option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-2 col-md-6 col-sm-12 mb-2">
                        <div class="input-group">
                           <button type="submit" class="btn btn-success btn-block no-radius font-weight-bold">
                              <i class="mdi mdi-magnify mr-2"></i>TÌM KIẾM
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </section>
      <!-- End Main Slider With Form -->

      <!-- Properties List -->
      <section class="section-padding" style="padding: 0 !important; margin-bottom: 50px;">
         <div class="container-fluid" style="padding: 0 !important;">
            <div class="row" style="height: 600px; margin: 0;">
               <!-- Posts List - Left Half -->
               <div class="col-lg-6 col-md-12" style="height: 100%; overflow-y: auto; padding: 20px;">
                  <!-- Filter Tags -->
                  @if(request()->hasAny(['search', 'type', 'waste_type_id', 'province']))
                     <div class="site_top_filter row mb-3">
                        <div class="col-lg-12 tags-action">
                           @if(request('search'))
                              <span>Tìm kiếm: "{{ request('search') }}"
                                 <a href="{{ route('posts.index', request()->except('search')) }}">
                                    <i class="mdi mdi-window-close"></i>
                                 </a>
                              </span>
                           @endif
                           @if(request('type'))
                              <span>
                                 {{ request('type') == 'co_so_phe_lieu' ? 'Cơ sở phế liệu' : 'Doanh nghiệp xanh' }}
                                 <a href="{{ route('posts.index', request()->except('type')) }}">
                                    <i class="mdi mdi-window-close"></i>
                                 </a>
                              </span>
                           @endif
                           @if(request('waste_type_id'))
                              @php
                                 $selectedWaste = $wasteTypes->find(request('waste_type_id'));
                              @endphp
                              @if($selectedWaste)
                                 <span>{{ $selectedWaste->name }}
                                    <a href="{{ route('posts.index', request()->except('waste_type_id')) }}">
                                       <i class="mdi mdi-window-close"></i>
                                    </a>
                                 </span>
                              @endif
                           @endif
                           @if(request('province'))
                              <span>Khu vực: {{ request('province') }}
                                 <a href="{{ route('posts.index', request()->except('province')) }}">
                                    <i class="mdi mdi-window-close"></i>
                                 </a>
                              </span>
                           @endif
                        </div>
                     </div>
                  @endif

                  <!-- Results Count -->
                  <div class="mb-3">
                     <p class="text-muted">Tìm thấy <strong>{{ $posts->total() }}</strong> bài đăng</p>
                  </div>

                  <!-- Posts Grid -->
                  @if($posts->count() > 0)
                     <div class="row">
                        @foreach($posts as $post)
                           <div class="col-lg-6 col-md-12 mb-4">
                              <div class="card card-list">
                                 <a href="{{ route('posts.show', $post) }}">
                                    <span class="badge badge-success">
                                       {{ $post->type == 'co_so_phe_lieu' ? 'Cơ sở phế liệu' : 'Doanh nghiệp xanh' }}
                                    </span>

                                    @if($post->images && count($post->images) > 0)
                                       <img class="card-img-top"
                                            src="{{ asset('storage/' . $post->images[0]) }}"
                                            alt="{{ $post->title }}"
                                            style="height: 250px; object-fit: cover;">
                                    @else
                                       <img class="card-img-top"
                                            src="{{ asset('img/list/1.png') }}"
                                            alt="{{ $post->title }}"
                                            style="height: 250px; object-fit: cover;">
                                    @endif

                                    <div class="card-body">
                                       <h5 class="card-title">{{ $post->title }}</h5>
                                       <h6 class="card-subtitle mb-2 text-muted">
                                          <i class="mdi mdi-home-map-marker"></i>
                                          {{ $post->collectionPoint->district }}, {{ $post->collectionPoint->province }}
                                       </h6>
                                       <h2 class="text-success mb-0 mt-3">
                                          {{ number_format($post->price, 0, ',', '.') }} <small>VNĐ/kg</small>
                                       </h2>
                                       <div class="mt-2">
                                          <p class="mt-2 mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                             {{ Str::limit($post->description, 80) }}
                                          </p>
                                          <span class="d-block">
                                             <i class="mdi mdi-cube"></i> Loại phế liệu: <strong>{{ $post->wasteType->name }}</strong>
                                          </span>
                                          <span class="d-block">
                                             <i class="mdi mdi-scale"></i> Số lượng: <strong>{{ $post->quantity }} kg</strong>
                                          </span>
                                       </div>
                                    </div>
                                    <div class="card-footer">
                                       <div class="d-flex align-items-center justify-content-between">
                                          <div class="d-flex align-items-center" style="flex: 1; min-width: 0; margin-right: 10px;">
                                             @if($post->user->company_logo)
                                                <img src="{{ asset('storage/' . $post->user->company_logo) }}"
                                                     alt="{{ $post->user->company_name }}"
                                                     class="rounded-circle mr-2"
                                                     style="width: 40px; height: 40px; object-fit: cover; flex-shrink: 0;">
                                             @else
                                                <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px; background-color: #28a745; color: white; font-weight: bold; font-size: 16px; flex-shrink: 0;">
                                                   {{ strtoupper(substr($post->user->company_name, 0, 1)) }}
                                                </div>
                                             @endif
                                             <span style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <strong>{{ $post->user->company_name }}</strong>
                                             </span>
                                          </div>
                                          <div class="d-flex align-items-center" style="gap: 12px; flex-shrink: 0;">
                                             <a href="#" class="text-dark" onclick="event.preventDefault(); event.stopPropagation(); addToWishlist({{ $post->id }});" title="Thêm vào yêu thích">
                                                <i class="far fa-heart" style="font-size: 24px;"></i>
                                             </a>
                                             <a href="#" class="text-dark" onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $post->id }});" title="Thêm vào giỏ hàng">
                                                <i class="fas fa-shopping-bag" style="font-size: 24px;"></i>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </a>
                              </div>
                           </div>
                        @endforeach
                     </div>

                     <!-- Pagination -->
                     <nav class="mt-5">
                        {{ $posts->links() }}
                     </nav>
                  @else
                     <div class="alert alert-info text-center">
                        <i class="mdi mdi-information"></i> Không tìm thấy bài đăng nào phù hợp với tiêu chí tìm kiếm.
                     </div>
                  @endif
               </div>

               <!-- Map - Right Half -->
               <div class="col-lg-6 col-md-12 p-0 map-wrapper" style="height: 100%; position: relative; overflow: hidden;">
                  <div id="map" style="width: 100%; height: 100%; position: relative;">
                     <!-- Mapbox will inject controls here -->
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Properties List -->

      <!-- Join Team -->
      <section class="py-5 bg-primary">
         <div class="container">
            <div class="row align-items-center text-center text-md-left">
               <div class="col-md-8">
                  <h2 class="text-white my-2">Join Our Professional Team & Agents</h2>
               </div>
               <div class="col-md-4 text-center text-md-right">
                  <button type="button" class="btn btn-outline-light my-2">Read More</button>
                  <button type="button" class="btn btn-light my-2">Contact Us</button>
               </div>
            </div>
         </div>
      </section>
      <!-- End Join Team -->
@endsection

@section('custom-js')
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script>
// Set properties data BEFORE loading map.js
window.mapProperties = [
    @foreach($posts as $post)
    {
        id: {{ $post->id }},
        title: "{{ $post->title }}",
        address: "{{ $post->collectionPoint->district }}, {{ $post->collectionPoint->province }}",
        price: {{ $post->price }},
        quantity: {{ $post->quantity }},
        wasteType: "{{ $post->wasteType->name }}",
        coordinates: [
            {{ $post->collectionPoint->longitude ?? 105.8342 }},
            {{ $post->collectionPoint->latitude ?? 21.0278 }}
        ],
        @if($post->images && count($post->images) > 0)
        image: "{{ asset('storage/' . $post->images[0]) }}"
        @else
        image: "{{ asset('img/list/1.png') }}"
        @endif
    }{{ $loop->last ? '' : ',' }}
    @endforeach
];

// Thêm vào danh sách yêu thích
function addToWishlist(postId) {
    console.log('Thêm bài đăng ' + postId + ' vào danh sách yêu thích');
    alert('Chức năng thêm vào yêu thích đang được phát triển');
}

// Lưu ý: addToCart đã được định nghĩa global trong header.blade.php
// Không cần định nghĩa lại ở đây

// Add data-property-id to cards after DOM loaded
$(document).ready(function() {
    $('.card-list').each(function(index) {
        if (window.mapProperties[index]) {
            $(this).attr('data-property-id', window.mapProperties[index].id);
        }
    });
});
</script>
<script src="{{ asset('js/map.js') }}"></script>
@endsection

@push('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<style>
.card-list:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.tags-action span {
    display: inline-block;
    background: #f8f9fa;
    padding: 5px 15px;
    margin-right: 10px;
    border-radius: 20px;
    font-size: 14px;
}

.tags-action span a {
    color: #dc3545;
    margin-left: 5px;
}

.tags-action span a:hover {
    color: #c82333;
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

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
}

/* Fix margin cho search form - đều trên dưới */
.slider-form .row.no-gutters {
    align-items: center;
}

.slider-form .col-lg-2,
.slider-form .col-lg-3 {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.slider-form .input-group {
    margin: 0;
}

/* Custom scrollbar styling - xanh tím giống banner */
.col-lg-6.col-md-12::-webkit-scrollbar {
    width: 12px;
}

.col-lg-6.col-md-12::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.col-lg-6.col-md-12::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    border: 2px solid #f1f1f1;
}

.col-lg-6.col-md-12::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #764ba2 0%, #667eea 100%);
}

/* For Firefox */
.col-lg-6.col-md-12 {
    scrollbar-width: thin;
    scrollbar-color: #667eea #f1f1f1;
}

/* Mapbox Map Container */
.map-wrapper {
    position: relative !important;
    overflow: hidden !important;
}

#map {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
}

#map .mapboxgl-canvas-container {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
}

/* Ensure Mapbox controls are visible - minimal overrides */
.mapboxgl-ctrl-group,
.mapboxgl-ctrl {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* DON'T override button styles - let Mapbox CSS handle icons */
.mapboxgl-ctrl-group button {
    display: block !important;
}

.mapboxgl-ctrl-logo {
    display: block !important;
}

.mapboxgl-ctrl-attrib {
    display: block !important;
}

/* Make sure map container doesn't hide controls */
#map {
    position: relative !important;
    z-index: 1 !important;
}

.mapboxgl-canvas-container {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 1 !important;
}

#map .mapboxgl-control-container {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    pointer-events: none !important;
    z-index: 10 !important;
}

/* Fix control corners - they must be children of control-container */
#map .mapboxgl-control-container > div {
    position: absolute !important;
    pointer-events: auto !important;
}

/* Position each corner within the map container */
#map .mapboxgl-ctrl-top-right {
    position: absolute !important;
    top: 10px !important;
    right: 10px !important;
}

#map .mapboxgl-ctrl-top-left {
    position: absolute !important;
    top: 10px !important;
    left: 10px !important;
}

#map .mapboxgl-ctrl-bottom-right {
    position: absolute !important;
    bottom: 10px !important;
    right: 10px !important;
}

#map .mapboxgl-ctrl-bottom-left {
    position: absolute !important;
    bottom: 10px !important;
    left: 10px !important;
}

/* Ensure map wrapper has proper stacking context */
.col-lg-6.col-md-12.p-0 {
    position: relative !important;
    z-index: 1 !important;
}

/* Mapbox Popup Styling */
.mapboxgl-popup {
    z-index: 999 !important;
}

.mapboxgl-popup-content {
    padding: 15px !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.popup-property {
    display: flex;
    gap: 15px;
    min-width: 300px;
}

.popup-property .img-style {
    flex-shrink: 0;
}

.popup-property .content {
    flex: 1;
}

.popup-property .text-caption-1 {
    color: #666;
    font-size: 13px;
    margin-bottom: 4px;
}

.popup-property h6 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.popup-property .price {
    color: #28a745 !important;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 8px;
}

.popup-property .info {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 15px;
}

.popup-property .info li {
    font-size: 13px;
    color: #666;
}

.popup-property .info li i {
    color: #28a745;
}

/* Controls positioning - FORCE WITHIN MAP */
#map .mapboxgl-control-container .mapboxgl-ctrl-top-right {
    position: absolute !important;
    top: 10px !important;
    right: 10px !important;
    left: auto !important;
    bottom: auto !important;
    min-width: 29px !important;
    float: none !important;
    margin: 0 !important;
}

#map .mapboxgl-control-container .mapboxgl-ctrl-bottom-left {
    position: absolute !important;
    bottom: 10px !important;
    left: 10px !important;
    right: auto !important;
    top: auto !important;
    min-width: 88px !important;
    float: none !important;
    margin: 0 !important;
}

#map .mapboxgl-control-container .mapboxgl-ctrl-bottom-right {
    position: absolute !important;
    bottom: 10px !important;
    right: 10px !important;
    left: auto !important;
    top: auto !important;
    float: none !important;
    margin: 0 !important;
}



/* NUCLEAR OPTION - Override everything for Mapbox controls */
button.mapboxgl-ctrl-zoom-in,
button.mapboxgl-ctrl-zoom-out,
button.mapboxgl-ctrl-compass,
button.mapboxgl-ctrl-geolocate,
button.mapboxgl-ctrl-fullscreen {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 29px !important;
    height: 29px !important;
    border: 0 !important;
    cursor: pointer !important;
    background-color: #fff !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
}

button.mapboxgl-ctrl-zoom-in:hover,
button.mapboxgl-ctrl-zoom-out:hover,
button.mapboxgl-ctrl-compass:hover,
button.mapboxgl-ctrl-geolocate:hover,
button.mapboxgl-ctrl-fullscreen:hover {
    background-color: #f2f2f2 !important;
}

/* Prevent any transform/clip from hiding controls */
.mapboxgl-ctrl-top-right *,
.mapboxgl-ctrl-top-left *,
.mapboxgl-ctrl-bottom-right *,
.mapboxgl-ctrl-bottom-left * {
    transform: none !important;
    clip-path: none !important;
    clip: auto !important;
    overflow: visible !important;
}

/* Custom Marker Styling - Màu đỏ */
.office-marker {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.office-marker i {
    color: #dc3545; /* Màu đỏ */
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

/* Active marker - vòng tròn trắng viền đen với marker đỏ bên trong */
.office-marker.active {
    background-color: white;
    border: 3px solid #333;
    border-radius: 50%;
    width: 46px;
    height: 46px;
}

.office-marker.active i {
    color: #dc3545;
    font-size: 24px;
}

/* Popup Property Styling - Dựa trên LANDMAP */
.popup-property {
    display: flex;
    padding: 15px;
    max-width: 350px;
}

.popup-property .img-style {
    margin-right: 15px;
    flex-shrink: 0;
}

.popup-property .content {
    flex: 1;
}

.popup-property .text-caption-1 {
    font-size: 12px;
    color: #666;
}

.popup-property h6 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
}

.popup-property .price {
    color: #28a745;
    font-size: 18px;
    font-weight: 700;
}

.popup-property .info {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 15px;
}

.popup-property .info li {
    font-size: 13px;
}

.popup-property .info i {
    margin-right: 4px;
}

/* Mapbox Popup Styling */
.mapboxgl-popup-content {
    padding: 0 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.mapboxgl-popup-close-button {
    font-size: 20px;
    padding: 5px 10px;
    color: #333;
}

.mapboxgl-popup-close-button:hover {
    background-color: rgba(0,0,0,0.05);
}

/* Scrollbar styling for posts list */
.col-lg-6::-webkit-scrollbar {
    width: 8px;
}

.col-lg-6::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.col-lg-6::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 4px;
}

.col-lg-6::-webkit-scrollbar-thumb:hover {
    background: #218838;
}
</style>
@endpush
