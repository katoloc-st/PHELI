<div class="card padding-card tab-view user-pages-sidebar">
                     <div class="card-body">
                        <!-- Heading -->
                        <h6 class="text-uppercase mt-0 mb-3">
                           Tài khoản
                        </h6>
                        <ul class="nav">
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('profile.show') ? 'active text-success' : '' }}" href="{{ route('profile.show') }}">
                                 <i class="fas fa-user mr-2"></i>Thông tin người đại diện
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('company-profile.show') ? 'active text-success' : '' }}" href="{{ route('company-profile.show') }}">
                                 <i class="fas fa-building mr-2"></i>Thông tin công ty
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('collection-points.*') ? 'active text-success' : '' }}" href="{{ route('collection-points.index') }}">
                                 <i class="fas fa-map-marker-alt mr-2"></i>Điểm tập kết
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('posts.my-posts') ? 'active text-success' : '' }}" href="{{ route('posts.my-posts') }}">
                                 <i class="fas fa-list mr-2"></i>Bài đăng của tôi
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('posts.create') ? 'active text-success' : '' }}" href="{{ route('posts.create') }}">
                                 <i class="fas fa-plus mr-2"></i>Thêm bài đăng
                              </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ request()->routeIs('cart.index') ? 'active text-success' : '' }}" href="{{ route('cart.index') }}">
                                 <i class="fas fa-shopping-cart mr-2"></i>Giỏ hàng
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
