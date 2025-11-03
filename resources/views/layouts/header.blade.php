<header>
         <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 0;">
            <div class="container-fluid" style="padding-left: 180px; padding-right: 180px;">
               <!-- Logo - Bên trái -->
               <a class="navbar-brand text-success logo" href="{{ route('index') }}" style="margin-right: 0; padding: 20px 0;">
                  <img class="img-fluid" src="{{ asset('img/logo.svg') }}" alt="" style="max-height: 55px;">
               </a>

               <button class="navbar-toggler" type="button" data-toggle="collapse"
                  data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                  aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
               </button>

               <div class="collapse navbar-collapse justify-content-center" id="navbarResponsive">
                  <!-- Menu chính - Giữa tuyệt đối -->
                  <ul class="navbar-nav" style="position: absolute; left: 50%; transform: translateX(-50%); z-index: 1000;">
                     <li class="nav-item">
                        <a class="nav-link px-3 font-weight-medium" href="{{ route('index') }}" style="padding-top: 28px; padding-bottom: 28px; font-size: 15px;">
                           Trang chủ
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link px-3 font-weight-medium" href="{{ route('posts.index') }}" style="padding-top: 28px; padding-bottom: 28px; font-size: 15px;">
                           Bài đăng
                        </a>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 font-weight-medium" href="#" id="navbarDropdownSellers" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" style="padding-top: 28px; padding-bottom: 28px; font-size: 15px;">
                           Người bán
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownSellers" style="z-index: 1001;">
                           <a class="dropdown-item" href="agency-list.html">Cơ sở phế liệu</a>
                           <a class="dropdown-item" href="business-list.html">Doanh nghiệp xanh</a>
                        </div>
                     </li>
                  </ul>
               </div>

               @auth
                  <!-- User logged in - Icons + Avatar bên phải -->
                  <div class="d-flex align-items-center" style="gap: 20px; margin-left: auto; padding: 20px 0;">
                     <!-- Icon tìm kiếm -->
                     <a class="nav-link p-0" href="#" title="Tìm kiếm">
                        <i class="fas fa-search text-dark" style="font-size: 22px;"></i>
                     </a>

                     <!-- Icon yêu thích -->
                     <a class="nav-link p-0 position-relative d-inline-block" href="#" title="Danh sách yêu thích">
                        <i class="far fa-heart text-dark" style="font-size: 22px;"></i>
                        <span id="wishlistBadge" class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -10px; font-size: 10px; padding: 3px 6px; min-width: 20px; display: none;">0</span>
                     </a>

                     <!-- Icon giỏ hàng -->
                     <a class="nav-link p-0 position-relative d-inline-block" href="#" title="Giỏ hàng" onclick="openCart(); return false;">
                        <i class="fas fa-shopping-bag text-dark" style="font-size: 22px;"></i>
                        <span id="cartBadge" class="badge badge-success badge-pill position-absolute" style="top: -8px; right: -10px; font-size: 10px; padding: 3px 6px; min-width: 20px; display: none;">0</span>
                     </a>

                     <!-- Avatar dropdown -->
                     <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" id="userDropdown"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="white-space: nowrap;">
                           @if(Auth::user()->avatar)
                              <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                   class="rounded-circle"
                                   alt="{{ Auth::user()->name }}"
                                   style="width: 45px; height: 45px; object-fit: cover;">
                           @else
                              <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white"
                                   style="width: 45px; height: 45px; font-weight: bold; font-size: 18px;">
                                 {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                              </div>
                           @endif
                           <span class="text-dark font-weight-medium ml-2" style="font-size: 15px;">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                           <a class="dropdown-item" href="{{ route('profile.show') }}">
                              <i class="fas fa-user mr-2"></i>Hồ sơ cá nhân
                           </a>
                           <a class="dropdown-item" href="{{ route('company-profile.show') }}">
                              <i class="fas fa-building mr-2"></i>Thông tin công ty
                           </a>
                           <a class="dropdown-item" href="{{ route('collection-points.index') }}">
                              <i class="fas fa-map-marker-alt mr-2"></i>Điểm tập kết
                           </a>
                           <a class="dropdown-item" href="{{ route('posts.my-posts') }}">
                              <i class="fas fa-list mr-2"></i>Bài đăng của tôi
                           </a>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item" href="#">
                              <i class="fas fa-cog mr-2"></i>Cài đặt
                           </a>
                           <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                              @csrf
                              <button type="submit" class="dropdown-item text-danger">
                                 <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
               @else
                  <!-- User not logged in - Buttons bên phải -->
                  <div class="d-flex align-items-center" style="gap: 10px; margin-left: auto; padding: 20px 0;">
                     <a class="btn btn-outline-primary px-4" href="{{ route('logon') }}">
                        <i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập
                     </a>
                     <a class="btn btn-primary px-4" href="{{ route('logon') }}">
                        <i class="fas fa-plus mr-1"></i> Đăng bài bán
                     </a>
                  </div>
               @endauth
            </div>
         </nav>
      </header>
