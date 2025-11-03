@extends('layouts.app')

@section('title', 'Bài đăng của tôi - Hệ thống quản lý phế liệu')



@section('content')
      <!-- Inner Header -->
      <section class="bg-dark py-5 user-header">
         <div class="container">
            <div class="row align-items-center mt-2 mb-5 pb-4">
               <div class="col">
                  <!-- Heading -->
                  <h1 class="text-white mb-2" style="font-family: 'Barlow', sans-serif;">
                     Bài đăng của tôi
                  </h1>
                  <!-- Text -->
                  <h6 class="font-weight-normal text-white-50 mb-0">
                     Quản lý các bài đăng phế liệu của bạn
                  </h6>
               </div>
               <div class="col-auto">
                  <!-- Button -->
                  <a href="{{ route('posts.create') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-plus"></i> Thêm bài đăng mới
                </a>
               </div>
            </div>
            <!-- / .row -->
         </div>
         <!-- / .container -->
      </section>
      <!-- End Inner Header -->
      <!-- My Properties -->
      <section class="section-padding pt-0 user-pages-main">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-3">
                  <!-- Collapse -->
                  @include('layouts.sidebar')
               </div>
               <div class="col-lg-9 col-md-9">
                  <!-- Success Message -->
                  @if(session('success'))
                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  @endif

                  @if($posts->count() > 0)
                     <div class="row">
                        @foreach($posts as $post)
                           <div class="col-lg-12 col-md-12">
                              <div class="card card-list card-list-view clickable-card" data-url="{{ route('posts.show', $post) }}">
                                 <div class="row no-gutters">
                                    <div class="col-lg-5 col-md-5">
                                       @php
                                          $badgeClass = match($post->status) {
                                             'active' => 'badge-success',
                                             'sold' => 'badge-secondary',
                                             'completed' => 'badge-dark',
                                             default => 'badge-warning'
                                          };

                                          $statusText = match($post->status) {
                                             'active' => 'Đang bán',
                                             'sold' => 'Đã bán',
                                             'completed' => 'Hoàn thành',
                                             default => 'Chờ duyệt'
                                          };
                                       @endphp
                                       <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                       <img class="card-img-top" src="{{ asset('img/list/' . ($loop->index % 6 + 1) . '.png') }}" alt="{{ $post->title }}">
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                       <div class="card-body">
                                          <div class="dropdown float-right">
                                             <button class="btn btn-sm btn-link text-muted" type="button" id="dropdownMenuButton{{ $post->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $post->id }}">
                                                <a class="dropdown-item" href="{{ route('posts.show', $post) }}">
                                                   <i class="mdi mdi-eye"></i> Xem chi tiết
                                                </a>
                                                <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                   <i class="mdi mdi-pencil"></i> Chỉnh sửa
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài đăng này?')" class="d-inline">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button type="submit" class="dropdown-item text-danger">
                                                      <i class="mdi mdi-delete"></i> Xóa
                                                   </button>
                                                </form>
                                             </div>
                                          </div>
                                          <h5 class="card-title">{{ $post->title }}</h5>
                                          <h6 class="card-subtitle mb-2 text-muted">
                                             <i class="mdi mdi-map-marker"></i> {{ $post->collectionPoint->district }}, {{ $post->collectionPoint->province }}
                                          </h6>
                                          <h2 class="text-success mb-0 mt-3">
                                             {{ number_format($post->price, 0, ',', '.') }} <small>VNĐ/kg</small>
                                          </h2>
                                          <p class="mt-2 mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($post->description, 80) }}</p>
                                       </div>
                                       <div class="card-footer">
                                          <span><i class="mdi mdi-cube"></i> Loại phế liệu : <strong>{{ $post->wasteType->name }}</strong></span>
                                          <span><i class="mdi mdi-scale"></i> Số lượng : <strong>{{ number_format($post->quantity, 0, ',', '.') }} kg</strong></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     </div>
                  @else
                     <div class="row">
                        <div class="col-12">
                           <div class="card">
                              <div class="card-body text-center py-5">
                                 <i class="mdi mdi-post-outline display-3 text-muted mb-3"></i>
                                 <h4>Chưa có bài đăng nào</h4>
                                 <p class="text-muted">Bạn chưa có bài đăng nào. Hãy tạo bài đăng đầu tiên của bạn!</p>
                                 <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Đăng bài mới
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endif

                  <!-- Pagination -->
                  @if($posts->hasPages())
                     {{ $posts->links('pagination.custom') }}
                  @endif
               </div>
            </div>
         </div>
      </section>
      <!-- End My Properties -->
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

@section('custom-css')
<style>

/* Fix for dropdown positioning */
.dropdown {
    position: relative;
}

.dropdown-menu-right {
    right: 0 !important;
    left: auto !important;
}

/* Add margin to last card to prevent dropdown cutoff */
.col-lg-12:last-child .card {
    margin-bottom: 60px;
}

/* Responsive adjustments for dropdown */
@media (max-width: 768px) {
    .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        top: 100% !important;
        transform: none !important;
        min-width: 140px;
    }

    .col-lg-12:last-child .card {
        margin-bottom: 80px;
    }
}

/* Clickable card styles */
.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.clickable-card .dropdown {
    position: relative;
    z-index: 10;
}
</style>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
    // Make cards clickable
    $('.clickable-card').on('click', function(e) {
        // Don't navigate if clicking on dropdown or its children
        if ($(e.target).closest('.dropdown').length === 0) {
            var url = $(this).data('url');
            window.location.href = url;
        }
    });

    // Prevent card click when clicking dropdown
    $('.dropdown-toggle, .dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });

    // Fix dropdown positioning
    $('.dropdown-toggle').on('click', function() {
        var $dropdown = $(this).next('.dropdown-menu');
        var $container = $(this).closest('.card');

        // Ensure dropdown stays within viewport
        setTimeout(function() {
            var dropdownRect = $dropdown[0].getBoundingClientRect();
            var viewportHeight = window.innerHeight;

            if (dropdownRect.bottom > viewportHeight) {
                $dropdown.addClass('dropup');
                $dropdown.css({
                    'top': 'auto',
                    'bottom': '100%'
                });
            }
        }, 10);
    });

    // Auto hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);

    // Confirm delete action
    $('form[onsubmit*="confirm"]').on('submit', function(e) {
        if (!confirm('Bạn có chắc chắn muốn xóa bài đăng này?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
