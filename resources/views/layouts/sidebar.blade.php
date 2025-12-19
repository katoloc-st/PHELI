<div class="card padding-card tab-view user-pages-sidebar">
                     <div class="card-body">
                        <!-- Heading -->
                        <h6 class="text-uppercase mt-0 mb-3" style="font-size: 15px; font-weight: 600;">
                           Tài khoản
                        </h6>
                        <ul class="nav">
                           @if(Auth::check())
                              @if(Auth::user()->role === 'waste_company')
                              <li class="nav-item">
                                 <a class="nav-link {{ request()->routeIs('waste-company.dashboard') ? 'active' : '' }}" href="{{ route('waste-company.dashboard') }}" style="{{ request()->routeIs('waste-company.dashboard') ? 'color: #516AF0 !important;' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2" style="{{ request()->routeIs('waste-company.dashboard') ? 'color: #516AF0 !important;' : '' }}"></i>Dashboard
                                 </a>
                              </li>
                              @elseif(Auth::user()->role === 'scrap_dealer')
                              <li class="nav-item">
                                 <a class="nav-link {{ request()->routeIs('scrap-dealer.dashboard') ? 'active' : '' }}" href="{{ route('scrap-dealer.dashboard') }}" style="{{ request()->routeIs('scrap-dealer.dashboard') ? 'color: #516AF0 !important;' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2" style="{{ request()->routeIs('scrap-dealer.dashboard') ? 'color: #516AF0 !important;' : '' }}"></i>Dashboard
                                 </a>
                              </li>
                              @elseif(Auth::user()->role === 'recycling_plant')
                              <li class="nav-item">
                                 <a class="nav-link {{ request()->routeIs('recycling-plant.dashboard') ? 'active' : '' }}" href="{{ route('recycling-plant.dashboard') }}" style="{{ request()->routeIs('recycling-plant.dashboard') ? 'color: #516AF0 !important;' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2" style="{{ request()->routeIs('recycling-plant.dashboard') ? 'color: #516AF0 !important;' : '' }}"></i>Dashboard
                                 </a>
                              </li>
                              @endif
                           @endif
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}" style="{{ request()->routeIs('profile.show') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-user mr-2" style="{{ request()->routeIs('profile.show') ? 'color: #516AF0 !important;' : '' }}"></i>Hồ sơ cá nhân
                              </a>
                           </li>
                           @if(Auth::check() && Auth::user()->role !== 'delivery_staff')
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('company-profile.show') ? 'active' : '' }}" href="{{ route('company-profile.show') }}" style="{{ request()->routeIs('company-profile.show') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-building mr-2" style="{{ request()->routeIs('company-profile.show') ? 'color: #516AF0 !important;' : '' }}"></i>Thông tin công ty
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('collection-points.*') ? 'active' : '' }}" href="{{ route('collection-points.index') }}" style="{{ request()->routeIs('collection-points.*') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-map-marker-alt mr-2" style="{{ request()->routeIs('collection-points.*') ? 'color: #516AF0 !important;' : '' }}"></i>Điểm tập kết
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('posts.my-posts') ? 'active' : '' }}" href="{{ route('posts.my-posts') }}" style="{{ request()->routeIs('posts.my-posts') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-list mr-2" style="{{ request()->routeIs('posts.my-posts') ? 'color: #516AF0 !important;' : '' }}"></i>Bài đăng của tôi
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}" href="{{ route('posts.create') }}" style="{{ request()->routeIs('posts.create') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-plus mr-2" style="{{ request()->routeIs('posts.create') ? 'color: #516AF0 !important;' : '' }}"></i>Thêm bài đăng
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}" style="{{ request()->routeIs('sales.*') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-store mr-2" style="{{ request()->routeIs('sales.*') ? 'color: #516AF0 !important;' : '' }}"></i>Quản lý yêu cầu mua
                              </a>
                           </li>
                           @endif
                           @if(Auth::check() && Auth::user()->role === 'delivery_staff')
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}" href="{{ route('delivery.index') }}" style="{{ request()->routeIs('delivery.*') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-truck mr-2" style="{{ request()->routeIs('delivery.*') ? 'color: #516AF0 !important;' : '' }}"></i>Quản lý giao hàng
                              </a>
                           </li>
                           @endif
                           @if(Auth::check() && Auth::user()->role !== 'delivery_staff')
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}" style="{{ request()->routeIs('cart.index') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-shopping-cart mr-2" style="{{ request()->routeIs('cart.index') ? 'color: #516AF0 !important;' : '' }}"></i>Giỏ hàng
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('orders.*') && !request()->routeIs('invoice.*') ? 'active' : '' }}" href="{{ route('orders.index') }}" style="{{ request()->routeIs('orders.*') && !request()->routeIs('invoice.*') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-shopping-bag mr-2" style="{{ request()->routeIs('orders.*') && !request()->routeIs('invoice.*') ? 'color: #516AF0 !important;' : '' }}"></i>Đơn hàng của tôi
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}" href="{{ route('invoice.index') }}" style="{{ request()->routeIs('invoice.*') ? 'color: #516AF0 !important;' : '' }}">
                                 <i class="fas fa-file-invoice mr-2" style="{{ request()->routeIs('invoice.*') ? 'color: #516AF0 !important;' : '' }}"></i>Xuất hóa đơn
                              </a>
                           </li>
                           @endif
                        </ul>
                     </div>
                  </div>
<style>
/* Tăng cỡ chữ sidebar */
.user-pages-sidebar .nav-link {
    font-size: 16px !important;
    padding: 10px 0 !important;
}

.user-pages-sidebar .nav-link i {
    font-size: 18px !important;
    width: 24px !important;
    text-align: center !important;
    margin-right: 12px !important;
}

/* Màu hover cho sidebar items */
.user-pages-sidebar .nav-link:hover {
    color: #516AF0 !important;
}

.user-pages-sidebar .nav-link:hover i {
    color: #516AF0 !important;
}

/* Thanh màu bên trái khi active - sử dụng ::after */
.user-pages-sidebar .nav-link.active::after {
    background: #516AF0 !important;
}

/* Thanh màu bên trái khi hover */
.user-pages-sidebar .nav-link:hover::after {
    background: #516AF0 !important;
}
</style>
