<section class="section-padding footer bg-white">
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <img class="img-fluid footer-logo"  src="{{ asset('img/logo.svg') }}" alt="">
                  <h6 class="mb-4 mt-5">KẾT NỐI VỚI CHÚNG TÔI</h6>
                  <div class="footer-social">
                     <a href="#"><i class="mdi mdi-facebook"></i></a>
                     <a href="#"><i class="mdi mdi-twitter"></i></a>
                     <a href="#"><i class="mdi mdi-instagram"></i></a>
                     <a href="#"><i class="mdi mdi-google"></i></a>
                  </div>
               </div>
               <div class="col-md-2">
                  <h6 class="mb-4">LOẠI PHẾ LIỆU</h6>
                  <ul>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Sắt']) }}" class="text-reset">
                           Sắt thép
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Nhôm']) }}" class="text-reset">
                           Nhôm
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Đồng']) }}" class="text-reset">
                           Đồng
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Inox']) }}" class="text-reset">
                           Inox
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Nhựa']) }}" class="text-reset">
                           Nhựa
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.index', ['waste_type' => 'Giấy']) }}" class="text-reset">
                           Giấy
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="col-md-2">
                  <h6 class="mb-4">DỊCH VỤ</h6>
                  <ul>
                     <li>
                        <a href="{{ route('posts.index') }}" class="text-reset">
                           Mua bán phế liệu
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('index') }}#price-table" class="text-reset">
                           Bảng giá phế liệu
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('posts.create') }}" class="text-reset">
                           Đăng tin miễn phí
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Thu gom tận nơi
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Tư vấn định giá
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="col-md-2">
                  <h6 class="mb-4">HỖ TRỢ</h6>
                  <ul>
                     <li>
                        <a href="#" class="text-reset">
                           Hướng dẫn sử dụng
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Câu hỏi thường gặp
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Chính sách vận chuyển
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Quy trình giao dịch
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Liên hệ hỗ trợ
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="col-md-2">
                  <h6 class="mb-4">PHÁP LÝ</h6>
                  <ul>
                     <li>
                        <a href="#" class="text-reset">
                           Điều khoản sử dụng
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Chính sách bảo mật
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Quy định giao dịch
                        </a>
                     </li>
                     <li>
                        <a href="#" class="text-reset">
                           Giải quyết khiếu nại
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
            <hr class="mt-5 mb-4">
            <div class="row">
               <div class="col-md-6 text-left">
                  <p class="mb-0 text-muted">
                     © 2025 <strong>PHELI</strong>. Tất cả các quyền được bảo lưu.
                  </p>
               </div>
               <div class="col-md-6 text-right">
                  <p class="mb-0 text-muted">
                     <i class="mdi mdi-phone"></i> Hotline: <strong>1900-0091</strong> |
                     <i class="mdi mdi-email"></i> Email: <strong>contact@pheli.vn</strong>
                  </p>
               </div>
            </div>
         </div>
      </section>
