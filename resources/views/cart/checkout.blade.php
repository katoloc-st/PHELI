@extends('layouts.app')

@section('title', 'Thanh toán - Hệ thống quản lý phế liệu')

@push('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<style>
/* Mapbox Map Styling */
#map {
   position: relative !important;
   z-index: 1 !important;
}

#map .mapboxgl-canvas-container {
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

#map .mapboxgl-control-container > div {
   position: absolute !important;
   pointer-events: auto !important;
}

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

.mapboxgl-ctrl-group,
.mapboxgl-ctrl {
   display: block !important;
   visibility: visible !important;
   opacity: 1 !important;
}

.mapboxgl-ctrl-group button {
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

.mapboxgl-ctrl-group button:hover {
   background-color: #f2f2f2 !important;
}

.mapboxgl-ctrl-logo {
   display: block !important;
}

.mapboxgl-ctrl-attrib {
   display: block !important;
}
</style>
@endpush

@section('content')
<section class="checkout-section py-5">
   <div class="container">
      <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
         @csrf
         <div class="row">
            <!-- Left Side - Form -->
            <div class="col-lg-7 col-md-12">
               <div class="checkout-form">
                  <!-- Contact Section -->
                  <div class="form-section mb-4">
                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="section-title mb-0">Thông tin liên hệ</h4>
                        @guest
                           <a href="{{ route('login') }}" class="sign-in-link">Đăng nhập</a>
                        @endguest
                     </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <input type="email" class="form-control form-control-lg" id="email"
                                  placeholder="Email"
                                  value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <input type="tel" class="form-control form-control-lg" id="phone"
                                  placeholder="Số điện thoại"
                                  value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                     </div>
                  </div>
                  <div class="custom-control custom-checkbox">
                     <input type="checkbox" class="custom-control-input" id="emailNews">
                     <label class="custom-control-label" for="emailNews">
                        Gửi cho tôi tin tức và ưu đãi
                     </label>
                  </div>
               </div>

               <!-- Delivery Section -->
               <div class="form-section mb-4">
                  <h4 class="section-title mb-3">Thông tin giao hàng</h4>

                  <!-- Hàng 1: Tên -->
                  <div class="form-group">
                     <input type="text" class="form-control form-control-lg" id="fullName"
                            placeholder="Tên"
                            value="{{ auth()->user()->name ?? '' }}" required>
                  </div>

                  <!-- Hàng 2: Tỉnh/Thành phố, Quận/Huyện, Phường/Xã -->
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           <select class="form-control form-control-lg" id="state" required>
                              <option value="">Tỉnh/Thành phố</option>
                              <option value="An Giang">An Giang</option>
                              <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                              <option value="Bạc Liêu">Bạc Liêu</option>
                              <option value="Bắc Kạn">Bắc Kạn</option>
                              <option value="Bắc Giang">Bắc Giang</option>
                              <option value="Bắc Ninh">Bắc Ninh</option>
                              <option value="Bến Tre">Bến Tre</option>
                              <option value="Bình Dương">Bình Dương</option>
                              <option value="Bình Định">Bình Định</option>
                              <option value="Bình Phước">Bình Phước</option>
                              <option value="Bình Thuận">Bình Thuận</option>
                              <option value="Cà Mau">Cà Mau</option>
                              <option value="Cao Bằng">Cao Bằng</option>
                              <option value="Cần Thơ">Cần Thơ</option>
                              <option value="Đà Nẵng">Đà Nẵng</option>
                              <option value="Đắk Lắk">Đắk Lắk</option>
                              <option value="Đắk Nông">Đắk Nông</option>
                              <option value="Điện Biên">Điện Biên</option>
                              <option value="Đồng Nai">Đồng Nai</option>
                              <option value="Đồng Tháp">Đồng Tháp</option>
                              <option value="Gia Lai">Gia Lai</option>
                              <option value="Hà Giang">Hà Giang</option>
                              <option value="Hà Nam">Hà Nam</option>
                              <option value="Hà Nội">Hà Nội</option>
                              <option value="Hà Tĩnh">Hà Tĩnh</option>
                              <option value="Hải Dương">Hải Dương</option>
                              <option value="Hải Phòng">Hải Phòng</option>
                              <option value="Hậu Giang">Hậu Giang</option>
                              <option value="Hòa Bình">Hòa Bình</option>
                              <option value="Hưng Yên">Hưng Yên</option>
                              <option value="Khánh Hòa">Khánh Hòa</option>
                              <option value="Kiên Giang">Kiên Giang</option>
                              <option value="Kon Tum">Kon Tum</option>
                              <option value="Lai Châu">Lai Châu</option>
                              <option value="Lạng Sơn">Lạng Sơn</option>
                              <option value="Lào Cai">Lào Cai</option>
                              <option value="Lâm Đồng">Lâm Đồng</option>
                              <option value="Long An">Long An</option>
                              <option value="Nam Định">Nam Định</option>
                              <option value="Nghệ An">Nghệ An</option>
                              <option value="Ninh Bình">Ninh Bình</option>
                              <option value="Ninh Thuận">Ninh Thuận</option>
                              <option value="Phú Thọ">Phú Thọ</option>
                              <option value="Phú Yên">Phú Yên</option>
                              <option value="Quảng Bình">Quảng Bình</option>
                              <option value="Quảng Nam">Quảng Nam</option>
                              <option value="Quảng Ngãi">Quảng Ngãi</option>
                              <option value="Quảng Ninh">Quảng Ninh</option>
                              <option value="Quảng Trị">Quảng Trị</option>
                              <option value="Sóc Trăng">Sóc Trăng</option>
                              <option value="Sơn La">Sơn La</option>
                              <option value="Tây Ninh">Tây Ninh</option>
                              <option value="Thái Bình">Thái Bình</option>
                              <option value="Thái Nguyên">Thái Nguyên</option>
                              <option value="Thanh Hóa">Thanh Hóa</option>
                              <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                              <option value="Tiền Giang">Tiền Giang</option>
                              <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                              <option value="Trà Vinh">Trà Vinh</option>
                              <option value="Tuyên Quang">Tuyên Quang</option>
                              <option value="Vĩnh Long">Vĩnh Long</option>
                              <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                              <option value="Yên Bái">Yên Bái</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <select class="form-control form-control-lg" id="district" required>
                              <option value="">Quận/Huyện</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <input type="text" class="form-control form-control-lg" id="ward"
                                  placeholder="Phường/Xã" required>
                        </div>
                     </div>
                  </div>

                  <!-- Hàng 3: Địa chỉ cụ thể -->
                  <div class="form-group position-relative">
                     <input type="text" class="form-control form-control-lg" id="address"
                            placeholder="Địa chỉ cụ thể" required>
                     <i class="mdi mdi-magnify search-icon"></i>
                  </div>

                  <!-- Hàng 4: Ghi chú -->
                  <div class="form-group">
                     <textarea class="form-control form-control-lg" id="notes" rows="2"
                            placeholder="Ghi chú thêm về địa chỉ (căn hộ, dãy nhà, v.v.)"></textarea>
                  </div>

                  <div class="custom-control custom-checkbox mb-3">
                     <input type="checkbox" class="custom-control-input" id="saveInfo">
                     <label class="custom-control-label" for="saveInfo">
                        Lưu thông tin này cho lần sau
                     </label>
                  </div>

                  <!-- Mapbox Map -->
                  <div id="map" style="width: 100%; height: 400px; border-radius: 8px; margin-top: 20px;"></div>
               </div>

               <!-- Payment Section -->
               <div class="form-section mb-4">
                  <h4 class="section-title mb-3">Thanh toán</h4>
                  <p class="text-muted small mb-3">Tất cả giao dịch đều được bảo mật và mã hóa.</p>

                  <div class="payment-methods">
                     <div class="payment-option">
                        <div class="custom-control custom-radio">
                           <input type="radio" class="custom-control-input" id="visa" name="payment" value="visa" checked>
                           <label class="custom-control-label w-100" for="visa">
                              <span class="d-flex justify-content-between align-items-center">
                                 <span>Thẻ tín dụng</span>
                                 <span class="payment-icons">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                 </span>
                              </span>
                           </label>
                        </div>
                     </div>

                     <div class="payment-option">
                        <div class="custom-control custom-radio">
                           <input type="radio" class="custom-control-input" id="momo" name="payment" value="momo">
                           <label class="custom-control-label" for="momo">
                              Ví điện tử (MoMo, ZaloPay)
                           </label>
                        </div>
                     </div>

                     <div class="payment-option">
                        <div class="custom-control custom-radio">
                           <input type="radio" class="custom-control-input" id="cod" name="payment" value="cod">
                           <label class="custom-control-label" for="cod">
                              Thanh toán khi giao hàng (COD)
                           </label>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Hidden fields for order data -->
               <input type="hidden" name="order_data" id="orderDataInput">

               <!-- Complete Order Button -->
               <button type="submit" class="btn btn-primary btn-lg btn-block complete-order-btn">
                  Hoàn tất đơn hàng
               </button>
            </div>
         </div>

         <!-- Right Side - Order Summary -->
         <div class="col-lg-5 col-md-12">
            <div class="order-summary">
               <h4 class="summary-title mb-4">Đơn hàng của bạn</h4>

               <!-- Cart Items -->
               <div id="orderItems" class="order-items mb-4">
                  <!-- Loading state -->
                  <div class="text-center py-3">
                     <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                     </div>
                  </div>
               </div>

               <!-- Gift Card / Discount Code -->
               <div class="discount-section mb-4">
                  <div class="input-group">
                     <input type="text" class="form-control" id="discountCode" placeholder="Mã giảm giá">
                     <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="discountBtn">
                           Áp dụng
                        </button>
                     </div>
                  </div>
               </div>

               <!-- Summary Details -->
               <div class="summary-details">
                  <!-- Tổng tiền hàng -->
                  <div class="summary-row">
                     <span>Tổng tiền hàng</span>
                     <span id="subtotal">0 VNĐ</span>
                  </div>

                  <!-- Tổng tiền phí vận chuyển -->
                  <div class="summary-row">
                     <span>Tổng tiền phí vận chuyển</span>
                     <span id="totalShippingFee">0 VNĐ</span>
                  </div>

                  <!-- Giảm giá phí vận chuyển -->
                  <div class="summary-row" id="shippingDiscountRow" style="display: none;">
                     <span style="color: #ef4444;">Giảm giá phí vận chuyển</span>
                     <span style="color: #ef4444; font-weight: 600;" id="shippingDiscount">-0 VNĐ</span>
                  </div>

                  <!-- Tổng cộng Voucher giảm giá -->
                  <div class="summary-row" id="voucherDiscountRow" style="display: none;">
                     <span style="color: #ef4444;">Tổng cộng Voucher giảm giá</span>
                     <span style="color: #ef4444; font-weight: 600;" id="voucherDiscount">-0 VNĐ</span>
                  </div>

                  @if(auth()->check() && auth()->user()->role === 'scrap_dealer')
                  <!-- Tiền cọc 7.5% (chỉ hiển thị cho cơ sở phế liệu) -->
                  <div class="summary-row" id="depositRow">
                     <span style="color: #f59e0b;">Tiền cọc (7.5%)</span>
                     <span style="color: #f59e0b; font-weight: 600;" id="depositAmount">0 VNĐ</span>
                  </div>
                  @endif

                  <hr>
                  <div class="summary-row total-row">
                     <span class="total-label">Tổng thanh toán</span>
                     <span class="total-amount" id="total">
                        0 <small class="text-muted">VNĐ</small>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </form>
</section>
@endsection

@section('custom-css')
<style>
/* Checkout Section */
.checkout-section {
   background-color: #f8f9fa;
   min-height: 100vh;
}

/* Form Sections */
.checkout-form {
   background: white;
   padding: 30px;
   border-radius: 10px;
   box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-section {
   padding-bottom: 20px;
}

.section-title {
   font-size: 20px;
   font-weight: 700;
   color: #333;
}

.sign-in-link {
   color: #667eea;
   font-size: 14px;
   text-decoration: none;
}

.sign-in-link:hover {
   text-decoration: underline;
}

/* Form Controls */
.form-control-lg {
   height: 50px;
   border-radius: 8px;
   border: 1px solid #d1d5db;
   font-size: 15px;
}

.form-control-lg:focus {
   border-color: #667eea;
   box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-icon {
   position: absolute;
   right: 15px;
   top: 50%;
   transform: translateY(-50%);
   color: #9ca3af;
   font-size: 20px;
}

/* Custom Checkbox & Radio */
.custom-control-label {
   font-size: 14px;
   color: #4b5563;
   cursor: pointer;
}

.custom-control-input:checked ~ .custom-control-label::before {
   background-color: #667eea;
   border-color: #667eea;
}

/* Shipping Message */
.shipping-message {
   background-color: #f3f4f6;
   padding: 15px;
   border-radius: 8px;
   color: #6b7280;
   font-size: 14px;
}

/* Payment Methods */
.payment-methods {
   border: 1px solid #e5e7eb;
   border-radius: 8px;
   overflow: hidden;
}

.payment-option {
   padding: 15px 20px;
   border-bottom: 1px solid #e5e7eb;
   transition: background-color 0.2s;
}

.payment-option:last-child {
   border-bottom: none;
}

.payment-option:hover {
   background-color: #f9fafb;
}

.payment-option .custom-control-input:checked ~ .custom-control-label {
   font-weight: 600;
}

.payment-icons i {
   font-size: 24px;
   margin-left: 5px;
}

.payment-icons .fa-cc-visa {
   color: #1a1f71;
}

.payment-icons .fa-cc-mastercard {
   color: #eb001b;
}

/* Billing Options */
.billing-options {
   border: 1px solid #e5e7eb;
   border-radius: 8px;
   overflow: hidden;
}

.billing-option {
   padding: 15px 20px;
   border-bottom: 1px solid #e5e7eb;
}

.billing-option:last-child {
   border-bottom: none;
}

/* Complete Order Button */
.complete-order-btn {
   height: 56px;
   font-size: 16px;
   font-weight: 700;
   background: #667eea;
   border: none;
   border-radius: 8px;
   margin-top: 20px;
}

.complete-order-btn:hover {
   background: #5568d3;
   box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* Order Summary */
.order-summary {
   background: #fafafa;
   padding: 30px;
   border-radius: 10px;
   position: sticky;
   top: 20px;
}

.summary-title {
   font-size: 20px;
   font-weight: 700;
   color: #333;
}

/* Order Items */
.order-items {
   max-height: 600px;
   overflow-y: auto;
}

/* Seller Group */
.seller-group {
   background: white;
   border: 1px solid #e5e7eb;
   border-radius: 8px;
   padding: 15px;
   margin-bottom: 15px;
}

.seller-header {
   display: flex;
   align-items: center;
   gap: 8px;
   padding-bottom: 12px;
   border-bottom: 1px solid #e5e7eb;
   margin-bottom: 12px;
   font-size: 15px;
}

.seller-header i {
   font-size: 18px;
   color: #667eea;
}

.seller-items {
   margin-bottom: 12px;
}

.order-item {
   display: flex;
   gap: 15px;
   padding: 12px 0;
   border-bottom: 1px solid #f3f4f6;
}

.order-item:last-child {
   border-bottom: none;
}

/* Seller Voucher */
.seller-voucher {
   margin: 12px 0;
}

.voucher-input-group {
   display: flex;
   gap: 8px;
}

.voucher-input {
   flex: 1;
   height: 40px;
   border: 1px solid #e5e7eb;
   border-radius: 6px;
   padding: 0 12px;
   font-size: 14px;
}

.voucher-input:focus {
   outline: none;
   border-color: #667eea;
}

.voucher-btn {
   height: 40px;
   padding: 0 20px;
   background: white;
   border: 2px solid #667eea;
   border-radius: 6px;
   font-size: 14px;
   font-weight: 600;
   color: #667eea;
   cursor: pointer;
   white-space: nowrap;
   transition: all 0.2s;
}

.voucher-btn:hover {
   background: #667eea;
   color: white;
   border-color: #667eea;
}

.voucher-remove-btn {
   height: 40px;
   padding: 0 20px;
   background: #ef4444;
   border: 2px solid #ef4444;
   border-radius: 6px;
   font-size: 14px;
   font-weight: 600;
   color: white;
   cursor: pointer;
   white-space: nowrap;
   transition: all 0.2s;
}

.voucher-remove-btn:hover {
   background: #dc2626;
   border-color: #dc2626;
}

/* Seller Note */
.seller-note {
   margin: 12px 0;
}

.note-input {
   width: 100%;
   height: 40px;
   border: 1px solid #e5e7eb;
   border-radius: 6px;
   padding: 0 12px;
   font-size: 14px;
   color: #6b7280;
}

.note-input:focus {
   outline: none;
   border-color: #667eea;
}

/* Seller Shipping */
.seller-shipping {
   margin: 12px 0;
   padding: 12px 0;
   border-top: 1px solid #f3f4f6;
}

.shipping-label {
   display: block;
   font-size: 14px;
   font-weight: 600;
   color: #333;
   margin-bottom: 8px;
}

.shipping-select {
   width: 100%;
   height: 40px;
   border: 1px solid #e5e7eb;
   border-radius: 6px;
   padding: 0 12px;
   font-size: 14px;
   background: white;
   cursor: pointer;
}

.shipping-select:focus {
   outline: none;
   border-color: #667eea;
}

/* Shipping Detail Box */
.shipping-detail-box {
   margin-top: 12px;
   background: #e8f5e9;
   border: 1px solid #4caf50;
   border-radius: 8px;
   padding: 16px;
}

.shipping-detail-content {
   display: flex;
   align-items: flex-start;
   gap: 12px;
}

.shipping-detail-content > i {
   color: #4caf50;
   font-size: 22px;
   flex-shrink: 0;
   margin-top: 2px;
}

.shipping-info {
   flex: 1;
}

.shipping-method-name {
   font-size: 14px;
   font-weight: 600;
   color: #333;
   margin-bottom: 6px;
}

.shipping-date {
   font-size: 13px;
   color: #666;
   margin-bottom: 6px;
}

.shipping-note {
   font-size: 12px;
   color: #666;
   line-height: 1.5;
}

.shipping-fee {
   font-size: 15px;
   font-weight: 600;
   color: #4caf50;
   white-space: nowrap;
   display: flex;
   flex-direction: column;
   align-items: flex-end;
   gap: 4px;
}

.shipping-fee .fee-amount {
   color: #4caf50;
}

.shipping-fee .fee-original {
   color: #999;
   text-decoration: line-through;
   font-size: 13px;
   font-weight: 400;
}

/* Seller Subtotal */
.seller-subtotal {
   display: flex;
   justify-content: space-between;
   align-items: center;
   padding-top: 12px;
   border-top: 1px solid #e5e7eb;
   font-size: 14px;
   color: #4b5563;
}

.subtotal-amount {
   font-weight: 600;
   color: #333;
}

/* Seller Discount */
.seller-discount {
   display: flex;
   justify-content: space-between;
   align-items: center;
   padding-top: 8px;
   font-size: 14px;
   color: #ef4444;
}

.seller-discount .discount-amount {
   font-weight: 600;
   color: #ef4444;
}

.item-image-wrapper {
   position: relative;
   width: 64px;
   height: 64px;
   flex-shrink: 0;
}

.item-image {
   width: 100%;
   height: 100%;
   object-fit: cover;
   border-radius: 8px;
   border: 1px solid #e5e7eb;
}

.item-quantity-badge {
   position: absolute;
   top: -8px;
   right: -8px;
   width: 24px;
   height: 24px;
   background: #6b7280;
   color: white;
   border-radius: 50%;
   display: flex;
   align-items: center;
   justify-content: center;
   font-size: 12px;
   font-weight: 600;
}

.item-details {
   flex: 1;
}

.item-title {
   font-size: 14px;
   font-weight: 600;
   color: #333;
   margin-bottom: 5px;
}

.item-meta {
   font-size: 13px;
   color: #6b7280;
}

.item-price {
   font-size: 14px;
   font-weight: 600;
   color: #333;
   white-space: nowrap;
}

/* Discount Section */
.discount-section .form-control {
   height: 48px;
   border-radius: 8px 0 0 8px;
   border: 2px solid #667eea;
}

.discount-section .form-control:focus {
   border-color: #667eea;
   box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.discount-section .btn {
   height: 48px;
   border-radius: 0 8px 8px 0;
   padding: 0 20px;
   font-weight: 600;
   background-color: #667eea;
   border-color: #667eea;
   color: white;
}

.discount-section .btn:hover {
   background-color: #5568d3;
   border-color: #5568d3;
}

/* Summary Details */
.summary-details {
   margin-top: 20px;
}

.summary-row {
   display: flex;
   justify-content: space-between;
   align-items: center;
   padding: 10px 0;
   font-size: 14px;
   color: #4b5563;
}

.summary-row.total-row {
   padding-top: 15px;
}

.total-label {
   font-size: 16px;
   font-weight: 600;
   color: #333;
}

.total-amount {
   font-size: 24px;
   font-weight: 700;
   color: #667eea;
}

.total-amount small {
   font-size: 14px;
   font-weight: 400;
   color: #9ca3af;
   margin-left: 5px;
}

/* Scrollbar for Order Items */
.order-items::-webkit-scrollbar {
   width: 6px;
}

.order-items::-webkit-scrollbar-track {
   background: #f1f1f1;
   border-radius: 10px;
}

.order-items::-webkit-scrollbar-thumb {
   background: #888;
   border-radius: 10px;
}

.order-items::-webkit-scrollbar-thumb:hover {
   background: #555;
}

/* Responsive */
@media (max-width: 991px) {
   .order-summary {
      margin-top: 30px;
      position: relative;
      top: 0;
   }
}

@media (max-width: 768px) {
   .checkout-form,
   .order-summary {
      padding: 20px;
   }

   .section-title {
      font-size: 18px;
   }

   .form-control-lg {
      height: 45px;
      font-size: 14px;
   }
}
</style>
@endsection

@section('custom-js')
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script>
// Mapbox Access Token - Same as posts index page
mapboxgl.accessToken = 'pk.eyJ1IjoiaG9hbmdoYW5kbiIsImEiOiJjbTdsbTkydm8wZGpiMmxxcTdvdzVqbHd3In0.HUUli-jvI1ALTBuzSeKTpw';

let map;
let marker;

// Data mapping Quận/Huyện theo Tỉnh/Thành phố
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
   "Đồng Tháp": ["TP. Cao Lãnh", "TP. Sa Đéc", "TP. Hồng Ngự", "Huyện Tân Hồng", "Huyện Hồng Ngự", "Huyện Tam Nông", "Huyện Tháp Mười", "Huyện Cao Lãnh", "Huyện Thanh Bình", "Huyện Lấp Vò", "Huyện Lai Vung", "Huyện Châu Thành"],
   "Gia Lai": ["TP. Pleiku", "TP. An Khê", "Thị xã Ayun Pa", "Huyện KBang", "Huyện Đăk Đoa", "Huyện Chư Păh", "Huyện Ia Grai", "Huyện Mang Yang", "Huyện Kông Chro", "Huyện Đức Cơ", "Huyện Chư Prông", "Huyện Chư Sê", "Huyện Đăk Pơ", "Huyện Ia Pa", "Huyện Krông Pa", "Huyện Phú Thiện", "Huyện Chư Pưh"],
   "Hà Giang": ["TP. Hà Giang", "Huyện Đồng Văn", "Huyện Mèo Vạc", "Huyện Yên Minh", "Huyện Quản Bạ", "Huyện Vị Xuyên", "Huyện Bắc Mê", "Huyện Hoàng Su Phì", "Huyện Xín Mần", "Huyện Bắc Quang", "Huyện Quang Bình"],
   "Hà Nam": ["TP. Phủ Lý", "Thị xã Duy Tiên", "Huyện Kim Bảng", "Huyện Thanh Liêm", "Huyện Bình Lục", "Huyện Lý Nhân"],
   "Hà Tĩnh": ["TP. Hà Tĩnh", "Thị xã Hồng Lĩnh", "Huyện Hương Sơn", "Huyện Đức Thọ", "Huyện Vũ Quang", "Huyện Nghi Xuân", "Huyện Can Lộc", "Huyện Hương Khê", "Huyện Thạch Hà", "Huyện Cẩm Xuyên", "Huyện Kỳ Anh", "Huyện Lộc Hà", "Thị xã Kỳ Anh"],
   "Hải Dương": ["TP. Hải Dương", "Thị xã Chí Linh", "Huyện Nam Sách", "Huyện Kinh Môn", "Huyện Kim Thành", "Huyện Thanh Hà", "Huyện Cẩm Giàng", "Huyện Bình Giang", "Huyện Gia Lộc", "Huyện Tứ Kỳ", "Huyện Ninh Giang", "Huyện Thanh Miện"],
   "Hậu Giang": ["TP. Vị Thanh", "TP. Ngã Bảy", "Huyện Châu Thành A", "Huyện Châu Thành", "Huyện Phụng Hiệp", "Huyện Vị Thuỷ", "Huyện Long Mỹ", "Thị xã Long Mỹ"],
   "Hòa Bình": ["TP. Hòa Bình", "Huyện Đà Bắc", "Huyện Lương Sơn", "Huyện Kim Bôi", "Huyện Cao Phong", "Huyện Tân Lạc", "Huyện Mai Châu", "Huyện Lạc Sơn", "Huyện Yên Thủy", "Huyện Lạc Thủy"],
   "Hưng Yên": ["TP. Hưng Yên", "Huyện Văn Lâm", "Huyện Văn Giang", "Huyện Yên Mỹ", "Thị xã Mỹ Hào", "Huyện Ân Thi", "Huyện Khoái Châu", "Huyện Kim Động", "Huyện Tiên Lữ", "Huyện Phù Cừ"],
   "Khánh Hòa": ["TP. Nha Trang", "TP. Cam Ranh", "Huyện Cam Lâm", "Huyện Vạn Ninh", "Thị xã Ninh Hòa", "Huyện Khánh Vĩnh", "Huyện Diên Khánh", "Huyện Khánh Sơn", "Huyện Trường Sa"],
   "Kiên Giang": ["TP. Rạch Giá", "TP. Hà Tiên", "Huyện Kiên Lương", "Huyện Hòn Đất", "Huyện Tân Hiệp", "Huyện Châu Thành", "Huyện Giồng Riềng", "Huyện Gò Quao", "Huyện An Biên", "Huyện An Minh", "Huyện Vĩnh Thuận", "Huyện Phú Quốc", "Huyện Kiên Hải", "Huyện U Minh Thượng", "Huyện Giang Thành"],
   "Kon Tum": ["TP. Kon Tum", "Huyện Đắk Glei", "Huyện Ngọc Hồi", "Huyện Đắk Tô", "Huyện Kon Plông", "Huyện Kon Rẫy", "Huyện Đắk Hà", "Huyện Sa Thầy", "Huyện Tu Mơ Rông", "Huyện Ia H' Drai"],
   "Lai Châu": ["TP. Lai Châu", "Huyện Tam Đường", "Huyện Mường Tè", "Huyện Sìn Hồ", "Huyện Phong Thổ", "Huyện Than Uyên", "Huyện Tân Uyên", "Huyện Nậm Nhùn"],
   "Lạng Sơn": ["TP. Lạng Sơn", "Huyện Trang Định", "Huyện Bình Gia", "Huyện Văn Lãng", "Huyện Cao Lộc", "Huyện Văn Quan", "Huyện Bắc Sơn", "Huyện Hữu Lũng", "Huyện Chi Lăng", "Huyện Lộc Bình", "Huyện Đình Lập"],
   "Lào Cai": ["TP. Lào Cai", "Huyện Bát Xát", "Huyện Mường Khương", "Huyện Si Ma Cai", "Huyện Bắc Hà", "Huyện Bảo Thắng", "Huyện Bảo Yên", "Thị xã Sa Pa", "Huyện Văn Bàn"],
   "Lâm Đồng": ["TP. Đà Lạt", "TP. Bảo Lộc", "Huyện Đam Rông", "Huyện Lạc Dương", "Huyện Lâm Hà", "Huyện Đơn Dương", "Huyện Đức Trọng", "Huyện Di Linh", "Huyện Bảo Lâm", "Huyện Đạ Huoai", "Huyện Đạ Tẻh", "Huyện Cát Tiên"],
   "Long An": ["TP. Tân An", "Thị xã Kiến Tường", "Huyện Tân Hưng", "Huyện Vĩnh Hưng", "Huyện Mộc Hóa", "Huyện Tân Thạnh", "Huyện Thạnh Hóa", "Huyện Đức Huệ", "Huyện Đức Hòa", "Huyện Bến Lức", "Huyện Thủ Thừa", "Huyện Tân Trụ", "Huyện Cần Đước", "Huyện Cần Giuộc", "Huyện Châu Thành"],
   "Nam Định": ["TP. Nam Định", "Huyện Mỹ Lộc", "Huyện Vụ Bản", "Huyện Ý Yên", "Huyện Nghĩa Hưng", "Huyện Nam Trực", "Huyện Trực Ninh", "Huyện Xuân Trường", "Huyện Giao Thủy", "Huyện Hải Hậu"],
   "Nghệ An": ["TP. Vinh", "Thị xã Cửa Lò", "Thị xã Thái Hoà", "Huyện Quế Phong", "Huyện Quỳ Châu", "Huyện Kỳ Sơn", "Huyện Tương Dương", "Huyện Nghĩa Đàn", "Huyện Quỳ Hợp", "Huyện Quỳnh Lưu", "Huyện Con Cuông", "Huyện Tân Kỳ", "Huyện Anh Sơn", "Huyện Diễn Châu", "Huyện Yên Thành", "Huyện Đô Lương", "Huyện Thanh Chương", "Huyện Nghi Lộc", "Huyện Nam Đàn", "Huyện Hưng Nguyên", "Thị xã Hoàng Mai"],
   "Ninh Bình": ["TP. Ninh Bình", "TP. Tam Điệp", "Huyện Nho Quan", "Huyện Gia Viễn", "Huyện Hoa Lư", "Huyện Yên Khánh", "Huyện Kim Sơn", "Huyện Yên Mô"],
   "Ninh Thuận": ["TP. Phan Rang-Tháp Chàm", "Huyện Bác Ái", "Huyện Ninh Sơn", "Huyện Ninh Hải", "Huyện Ninh Phước", "Huyện Thuận Bắc", "Huyện Thuận Nam"],
   "Phú Thọ": ["TP. Việt Trì", "Thị xã Phú Thọ", "Huyện Đoan Hùng", "Huyện Hạ Hoà", "Huyện Thanh Ba", "Huyện Phù Ninh", "Huyện Yên Lập", "Huyện Cẩm Khê", "Huyện Tam Nông", "Huyện Lâm Thao", "Huyện Thanh Sơn", "Huyện Thanh Thuỷ", "Huyện Tân Sơn"],
   "Phú Yên": ["TP. Tuy Hoà", "Thị xã Sông Cầu", "Huyện Đồng Xuân", "Huyện Tuy An", "Huyện Sơn Hòa", "Huyện Sông Hinh", "Huyện Tây Hoà", "Huyện Phú Hoà", "Thị xã Đông Hòa"],
   "Quảng Bình": ["TP. Đồng Hới", "Huyện Minh Hóa", "Huyện Tuyên Hóa", "Huyện Quảng Trạch", "Huyện Bố Trạch", "Huyện Quảng Ninh", "Huyện Lệ Thủy", "Thị xã Ba Đồn"],
   "Quảng Nam": ["TP. Tam Kỳ", "TP. Hội An", "Huyện Tây Giang", "Huyện Đông Giang", "Huyện Đại Lộc", "Huyện Điện Bàn", "Huyện Duy Xuyên", "Huyện Quế Sơn", "Huyện Nam Giang", "Huyện Phước Sơn", "Huyện Hiệp Đức", "Huyện Thăng Bình", "Huyện Tiên Phước", "Huyện Bắc Trà My", "Huyện Nam Trà My", "Huyện Núi Thành", "Huyện Phú Ninh", "Huyện Nông Sơn"],
   "Quảng Ngãi": ["TP. Quảng Ngãi", "Huyện Bình Sơn", "Huyện Trà Bồng", "Huyện Sơn Tịnh", "Huyện Tư Nghĩa", "Huyện Nghĩa Hành", "Huyện Minh Long", "Huyện Mộ Đức", "Huyện Đức Phổ", "Huyện Ba Tơ", "Huyện Lý Sơn", "Huyện Sơn Hà", "Huyện Sơn Tây", "Huyện Tây Trà"],
   "Quảng Ninh": ["TP. Hạ Long", "TP. Móng Cái", "TP. Cẩm Phả", "TP. Uông Bí", "Huyện Bình Liêu", "Huyện Tiên Yên", "Huyện Đầm Hà", "Huyện Hải Hà", "Huyện Ba Chẽ", "Huyện Vân Đồn", "Thị xã Đông Triều", "Thị xã Quảng Yên", "Huyện Cô Tô"],
   "Quảng Trị": ["TP. Đông Hà", "Thị xã Quảng Trị", "Huyện Vĩnh Linh", "Huyện Hướng Hóa", "Huyện Gio Linh", "Huyện Đa Krông", "Huyện Cam Lộ", "Huyện Triệu Phong", "Huyện Hải Lăng", "Huyện Cồn Cỏ"],
   "Sóc Trăng": ["TP. Sóc Trăng", "Huyện Châu Thành", "Huyện Kế Sách", "Huyện Mỹ Tú", "Huyện Cù Lao Dung", "Huyện Long Phú", "Huyện Mỹ Xuyên", "Thị xã Ngã Năm", "Huyện Thạnh Trị", "Thị xã Vĩnh Châu", "Huyện Trần Đề"],
   "Sơn La": ["TP. Sơn La", "Huyện Quỳnh Nhai", "Huyện Thuận Châu", "Huyện Mường La", "Huyện Bắc Yên", "Huyện Phù Yên", "Huyện Mộc Châu", "Huyện Yên Châu", "Huyện Mai Sơn", "Huyện Sông Mã", "Huyện Sốp Cộp", "Huyện Vân Hồ"],
   "Tây Ninh": ["TP. Tây Ninh", "Huyện Tân Biên", "Huyện Tân Châu", "Huyện Dương Minh Châu", "Huyện Châu Thành", "Huyện Hòa Thành", "Huyện Gò Dầu", "Huyện Bến Cầu", "Thị xã Trảng Bàng"],
   "Thái Bình": ["TP. Thái Bình", "Huyện Quỳnh Phụ", "Huyện Hưng Hà", "Huyện Đông Hưng", "Huyện Thái Thụy", "Huyện Tiền Hải", "Huyện Kiến Xương", "Huyện Vũ Thư"],
   "Thái Nguyên": ["TP. Thái Nguyên", "TP. Sông Công", "Huyện Định Hóa", "Huyện Phú Lương", "Huyện Đồng Hỷ", "Huyện Võ Nhai", "Huyện Đại Từ", "Thị xã Phổ Yên", "Huyện Phú Bình"],
   "Thanh Hóa": ["TP. Thanh Hóa", "Thị xã Bỉm Sơn", "TP. Sầm Sơn", "Huyện Mường Lát", "Huyện Quan Hóa", "Huyện Bá Thước", "Huyện Quan Sơn", "Huyện Lang Chánh", "Huyện Ngọc Lặc", "Huyện Cẩm Thủy", "Huyện Thạch Thành", "Huyện Hà Trung", "Huyện Vĩnh Lộc", "Huyện Yên Định", "Huyện Thọ Xuân", "Huyện Thường Xuân", "Huyện Triệu Sơn", "Huyện Thiệu Hóa", "Huyện Hoằng Hóa", "Huyện Hậu Lộc", "Huyện Nga Sơn", "Huyện Như Xuân", "Huyện Như Thanh", "Huyện Nông Cống", "Huyện Đông Sơn", "Huyện Quảng Xương", "Thị xã Nghi Sơn", "Huyện Tĩnh Gia"],
   "Thừa Thiên Huế": ["TP. Huế", "Huyện Phong Điền", "Huyện Quảng Điền", "Huyện Phú Vang", "Thị xã Hương Thủy", "Thị xã Hương Trà", "Huyện A Lưới", "Huyện Phú Lộc", "Huyện Nam Đông"],
   "Tiền Giang": ["TP. Mỹ Tho", "Thị xã Gò Công", "Thị xã Cai Lậy", "Huyện Tân Phước", "Huyện Cái Bè", "Huyện Cai Lậy", "Huyện Châu Thành", "Huyện Chợ Gạo", "Huyện Gò Công Tây", "Huyện Gò Công Đông", "Huyện Tân Phú Đông"],
   "Trà Vinh": ["TP. Trà Vinh", "Huyện Càng Long", "Huyện Cầu Kè", "Huyện Tiểu Cần", "Huyện Châu Thành", "Huyện Cầu Ngang", "Huyện Trà Cú", "Huyện Duyên Hải", "Thị xã Duyên Hải"],
   "Tuyên Quang": ["TP. Tuyên Quang", "Huyện Lâm Bình", "Huyện Na Hang", "Huyện Chiêm Hóa", "Huyện Hàm Yên", "Huyện Yên Sơn", "Huyện Sơn Dương"],
   "Vĩnh Long": ["TP. Vĩnh Long", "Huyện Long Hồ", "Huyện Mang Thít", "Huyện Vũng Liêm", "Huyện Tam Bình", "Thị xã Bình Minh", "Huyện Trà Ôn", "Huyện Bình Tân"],
   "Vĩnh Phúc": ["TP. Vĩnh Yên", "TP. Phúc Yên", "Huyện Lập Thạch", "Huyện Tam Dương", "Huyện Tam Đảo", "Huyện Bình Xuyên", "Huyện Yên Lạc", "Huyện Vĩnh Tường", "Huyện Sông Lô"],
   "Yên Bái": ["TP. Yên Bái", "Thị xã Nghĩa Lộ", "Huyện Lục Yên", "Huyện Văn Yên", "Huyện Mù Căng Chải", "Huyện Trấn Yên", "Huyện Trạm Tấu", "Huyện Văn Chấn", "Huyện Yên Bình"]
};

// Flag to prevent auto-zoom when updating from reverse geocoding
let isReverseGeocoding = false;

// Initialize discount objects
window.sellerDiscounts = {};
window.platformDiscount = null;

$(document).ready(function() {
   loadOrderSummary();
   initializeMap();

   // Initialize discount button event
   $('#discountBtn').on('click', function() {
      applyDiscount();
   });

   // Handle province change to update district dropdown
   $('#state').on('change', function() {
      updateDistrictDropdown();
      // Only zoom if user manually selected (not from reverse geocoding)
      if (!isReverseGeocoding) {
         zoomToProvince($(this).val());
      }
   });

   // Handle district change to zoom to district
   $('#district').on('change', function() {
      const province = $('#state').val();
      const district = $(this).val();
      // Only zoom if user manually selected (not from reverse geocoding)
      if (!isReverseGeocoding && province && district) {
         zoomToDistrict(province, district);
      }
   });
});

// Update district dropdown based on selected province
function updateDistrictDropdown() {
   const selectedProvince = $('#state').val();
   const districtDropdown = $('#district');

   // Clear current options
   districtDropdown.html('<option value="">Quận/Huyện</option>');

   // If province is selected and has districts
   if (selectedProvince && districtData[selectedProvince]) {
      const districts = districtData[selectedProvince];

      // Add district options
      districts.forEach(district => {
         districtDropdown.append(`<option value="${district}">${district}</option>`);
      });

      districtDropdown.prop('disabled', false);
   } else {
      districtDropdown.prop('disabled', true);
   }

   // Clear ward field when province changes
   $('#ward').val('');
}

// Zoom to province when selected
function zoomToProvince(province) {
   if (!map || !province) return;

   // Geocode province name to get coordinates
   const searchQuery = `${province}, Vietnam`;
   const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(searchQuery)}.json?access_token=${mapboxgl.accessToken}&country=VN&types=place,region&limit=1`;

   fetch(url)
      .then(response => response.json())
      .then(data => {
         if (data.features && data.features.length > 0) {
            const feature = data.features[0];
            const [lng, lat] = feature.center;

            // Fly to province with appropriate zoom level
            map.flyTo({
               center: [lng, lat],
               zoom: 10, // Province level zoom
               essential: true,
               duration: 2000
            });

            // Move marker to province center
            if (marker) {
               marker.setLngLat([lng, lat]);
            }

            console.log('Zoomed to province:', province);
         }
      })
      .catch(error => console.error('Province geocoding error:', error));
}

// Zoom to district when selected
function zoomToDistrict(province, district) {
   if (!map || !province || !district) return;

   // Geocode district name to get coordinates
   const searchQuery = `${district}, ${province}, Vietnam`;
   const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(searchQuery)}.json?access_token=${mapboxgl.accessToken}&country=VN&types=place,locality,neighborhood&limit=1`;

   fetch(url)
      .then(response => response.json())
      .then(data => {
         if (data.features && data.features.length > 0) {
            const feature = data.features[0];
            const [lng, lat] = feature.center;

            // Fly to district with closer zoom level
            map.flyTo({
               center: [lng, lat],
               zoom: 13, // District level zoom
               essential: true,
               duration: 2000
            });

            // Move marker to district center
            if (marker) {
               marker.setLngLat([lng, lat]);
            }

            console.log('Zoomed to district:', district);
         }
      })
      .catch(error => console.error('District geocoding error:', error));
}

// Initialize Mapbox Map
function initializeMap() {
   if (!document.getElementById('map')) {
      return;
   }

   // Default location: Center of Vietnam - zoom to see whole country
   const defaultLng = 107; // Center of Vietnam longitude (adjusted)
   const defaultLat = 16.5; // Center of Vietnam latitude (adjusted to middle)

   map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v12',
      center: [defaultLng, defaultLat],
      zoom: 4.5, // Lower zoom to see whole Vietnam from North to South
      attributionControl: true,
      logoPosition: 'bottom-left'
   });

   // Add navigation controls
   const nav = new mapboxgl.NavigationControl({
      showCompass: true,
      showZoom: true,
      visualizePitch: true
   });
   map.addControl(nav, 'top-right');

   // Add fullscreen control
   const fullscreen = new mapboxgl.FullscreenControl();
   map.addControl(fullscreen, 'top-right');

   // Add geolocate control
   const geolocate = new mapboxgl.GeolocateControl({
      positionOptions: {
         enableHighAccuracy: true
      },
      trackUserLocation: true,
      showUserHeading: true,
      showAccuracyCircle: true
   });
   map.addControl(geolocate, 'top-right');

   map.on('load', function() {
      console.log('Checkout map loaded successfully');

      // Add marker
      marker = new mapboxgl.Marker({
         draggable: true,
         color: '#667eea'
      })
      .setLngLat([defaultLng, defaultLat])
      .addTo(map);

      // Update address when marker is dragged
      marker.on('dragend', function() {
         const lngLat = marker.getLngLat();
         reverseGeocode(lngLat.lng, lngLat.lat);
      });

      // Click on map to move marker
      map.on('click', function(e) {
         marker.setLngLat(e.lngLat);
         reverseGeocode(e.lngLat.lng, e.lngLat.lat);
      });

      // Listen to address input changes
      $('#address').on('change', function() {
         const address = $(this).val();
         if (address) {
            geocodeAddress(address);
         }
      });

      // Listen for Enter key on address input
      $('#address').on('keypress', function(e) {
         if (e.which === 13) { // Enter key
            e.preventDefault();
            const address = $(this).val();
            if (address) {
               geocodeAddress(address);
            }
         }
      });

      // Click on search icon
      $('.search-icon').on('click', function() {
         const address = $('#address').val();
         if (address) {
            geocodeAddress(address);
         }
      });

      // Force controls to be visible
      forceControlsVisible();
      setTimeout(forceControlsVisible, 100);
      setTimeout(forceControlsVisible, 300);
   });

   // Ensure map resizes correctly
   setTimeout(function() {
      map.resize();
   }, 100);
}

// Force controls to be visible
function forceControlsVisible() {
   const selectors = [
      '.mapboxgl-ctrl',
      '.mapboxgl-ctrl-group',
      '.mapboxgl-ctrl-top-right',
      'button.mapboxgl-ctrl-zoom-in',
      'button.mapboxgl-ctrl-zoom-out',
      'button.mapboxgl-ctrl-compass',
      'button.mapboxgl-ctrl-geolocate',
      'button.mapboxgl-ctrl-fullscreen'
   ];

   selectors.forEach(selector => {
      const elements = document.querySelectorAll(selector);
      elements.forEach(el => {
         el.style.display = 'block';
         el.style.visibility = 'visible';
         el.style.opacity = '1';
         el.style.pointerEvents = 'auto';
      });
   });
}

// Geocode address to coordinates
function geocodeAddress(address) {
   // Lấy thông tin tỉnh và quận đã chọn để tìm kiếm chính xác
   const province = $('#state').val();
   const district = $('#district').val();
   const ward = $('#ward').val();

   // Build full search query với tỉnh, quận để tìm chính xác
   let searchQuery = address;
   if (ward) searchQuery += `, ${ward}`;
   if (district) searchQuery += `, ${district}`;
   if (province) searchQuery += `, ${province}`;
   searchQuery += ', Vietnam';

   console.log('Geocoding address:', searchQuery);

   const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(searchQuery)}.json?access_token=${mapboxgl.accessToken}&country=VN&limit=1`;

   fetch(url)
      .then(response => response.json())
      .then(data => {
         if (data.features && data.features.length > 0) {
            const [lng, lat] = data.features[0].center;
            marker.setLngLat([lng, lat]);
            map.flyTo({
               center: [lng, lat],
               zoom: 16,
               essential: true
            });
            console.log('Found location:', data.features[0].place_name);
         } else {
            console.warn('No location found for:', searchQuery);
            alert('Không tìm thấy địa chỉ. Vui lòng kiểm tra lại thông tin.');
         }
      })
      .catch(error => console.error('Geocoding error:', error));
}

// Reverse geocode coordinates to address
function reverseGeocode(lng, lat) {
   // Sử dụng proximity để tăng độ chính xác và lấy nhiều kết quả
   const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}&country=VN&types=address,poi&proximity=${lng},${lat}&limit=5`;

   fetch(url)
      .then(response => response.json())
      .then(data => {
         if (data.features && data.features.length > 0) {
            // Log tất cả features để xem
            console.log('All features:', data.features);

            // Tìm feature có số nhà (address) trước, nếu không có thì lấy feature đầu tiên
            let feature = data.features.find(f => f.address) || data.features[0];

            // Log feature được chọn
            console.log('Selected feature:', feature);

            // Cấu trúc địa chỉ từ Mapbox:
            // - feature.text: Tên đường hoặc địa điểm (VD: "Nguyễn Văn Quá")
            // - feature.address: Số nhà (VD: "46")
            // - feature.place_name: Địa chỉ đầy đủ (có cả số nhà)
            // - feature.context: Mảng chứa các thành phần:
            //   + neighborhood: Phường/Xã
            //   + locality: Quận/Huyện
            //   + place: Thành phố
            //   + region: Tỉnh (fallback)
            //   + postcode: Mã bưu điện
            //   + country: Quốc gia

            let addressParts = [];
            let houseNumber = '';

            // 1. Số nhà - thử nhiều cách để lấy
            if (feature.address) {
               houseNumber = feature.address;
               addressParts.push(feature.address);
            } else if (feature.place_name) {
               // Thử extract số nhà từ place_name (format: "46 Nguyễn Văn Quá, ...")
               const match = feature.place_name.match(/^(\d+[\w\/\-]*)\s/);
               if (match && match[1]) {
                  houseNumber = match[1];
                  addressParts.push(match[1]);
               }
            }

            console.log('House number found:', houseNumber || 'No house number available');

            // 2. Tên đường - bỏ chữ "Đường" nếu có
            if (feature.text) {
               let streetName = feature.text;
               // Bỏ chữ "Đường" ở đầu tên đường (VD: "Đường Hoàng Diệu 2" → "Hoàng Diệu 2")
               streetName = streetName.replace(/^Đường\s+/i, '');
               addressParts.push(streetName);
            }

            // 3. Lấy các thành phần từ context
            let ward = '';
            let district = '';
            let city = '';

            if (feature.context) {
               console.log('Context items:', feature.context);
               feature.context.forEach(item => {
                  console.log('Context item:', item.id, '->', item.text);

                  if (item.id.startsWith('neighborhood')) {
                     ward = item.text; // Phường/Xã (VD: "Đông Hưng Thuận")
                  } else if (item.id.startsWith('locality')) {
                     // locality là quận/huyện (VD: "Quận 12")
                     district = item.text;
                  } else if (item.id.startsWith('place')) {
                     // place là thành phố (VD: "Ho Chi Minh City")
                     city = item.text;
                  } else if (item.id.startsWith('region')) {
                     // region cũng có thể là thành phố (fallback)
                     if (!city) city = item.text;
                  }
               });
            }

            console.log('Extracted components:', { ward, district, city });

            // Build street address - CHỈ số nhà và tên đường (không có phường, quận, tỉnh)
            const streetAddress = addressParts.join(' ');

            // Update form fields
            // Ô address chỉ chứa số nhà và tên đường
            $('#address').val(streetAddress);

            // Set flag to prevent auto-zoom during reverse geocoding updates
            isReverseGeocoding = true;

            // Update Tỉnh/Thành phố dropdown if we have the city
            if (city) {
               // Tìm và chọn tỉnh phù hợp
               let provinceFound = false;
               $('#state option').each(function() {
                  const optionText = $(this).text();
                  const optionValue = $(this).val();

                  // Kiểm tra match với city name
                  if (optionValue && (
                     optionText.includes(city) ||
                     city.includes(optionText) ||
                     (city.includes('Ho Chi Minh') && optionValue === 'TP. Hồ Chí Minh')
                  )) {
                     $('#state').val(optionValue).trigger('change');
                     provinceFound = true;
                     return false; // break
                  }
               });

               // Nếu tìm thấy tỉnh, cập nhật dropdown quận/huyện
               if (provinceFound) {
                  // Wait for district dropdown to be updated
                  setTimeout(function() {
                     // Update Quận/Huyện dropdown if we have it
                     if (district) {
                        // Tìm và chọn quận/huyện phù hợp
                        $('#district option').each(function() {
                           const optionValue = $(this).val();
                           if (optionValue && (
                              optionValue.includes(district) ||
                              district.includes(optionValue)
                           )) {
                              $('#district').val(optionValue);
                              return false; // break
                           }
                        });
                     }

                     // Reset flag after all updates are done
                     setTimeout(function() {
                        isReverseGeocoding = false;
                     }, 50);
                  }, 100);
               } else {
                  // Reset flag if province not found
                  isReverseGeocoding = false;
               }
            } else {
               // Reset flag if no city
               isReverseGeocoding = false;
            }

            // Update Phường/Xã field if we have it
            if (ward) {
               $('#ward').val(ward);
            }

            console.log('Parsed Address:', {
               address: feature.address,
               street: feature.text,
               ward: ward,
               district: district,
               city: city,
               fullAddress: fullAddress
            });
         }
      })
      .catch(error => console.error('Reverse geocoding error:', error));
}

// Load order summary from cart
function loadOrderSummary() {
   fetch('{{ route('cart.get') }}', {
      headers: {
         'X-Requested-With': 'XMLHttpRequest',
         'Accept': 'application/json'
      }
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         if (data.items.length === 0) {
            // Redirect to cart if empty
            window.location.href = '{{ route('cart.index') }}';
         } else {
            renderOrderItems(data.items, data.total);
         }
      }
   })
   .catch(error => {
      console.error('Error loading cart:', error);
   });
}

// Render order items
function renderOrderItems(items, total) {
   // Group items by seller
   const groupedItems = {};
   items.forEach(item => {
      const sellerId = item.post.user_id;
      if (!groupedItems[sellerId]) {
         groupedItems[sellerId] = {
            seller: item.post.user,
            items: [],
            subtotal: 0
         };
      }
      groupedItems[sellerId].items.push(item);
      groupedItems[sellerId].subtotal += item.post.price * item.quantity;
   });

   let html = '';

   // Render each seller group
   Object.keys(groupedItems).forEach(sellerId => {
      const group = groupedItems[sellerId];
      const sellerName = group.seller.company_name || group.seller.name || 'Người bán';

      html += `
         <div class="seller-group mb-4">
            <div class="seller-header">
               <i class="mdi mdi-store"></i>
               <strong>${sellerName}</strong>
            </div>

            <div class="seller-items">
      `;

      // Render items for this seller
      group.items.forEach(item => {
         const imageUrl = item.post.images && item.post.images.length > 0
            ? '{{ asset('storage') }}/' + item.post.images[0]
            : '{{ asset('img/list/1.png') }}';

         const totalPrice = item.post.price * item.quantity;

         html += `
            <div class="order-item">
               <div class="item-image-wrapper">
                  <img src="${imageUrl}" alt="${item.post.title}" class="item-image">
                  <span class="item-quantity-badge">${item.quantity}</span>
               </div>
               <div class="item-details">
                  <div class="item-title">${item.post.title}</div>
                  <div class="item-meta">${item.post.waste_type.name}</div>
               </div>
               <div class="item-price">${formatNumber(totalPrice)} VNĐ</div>
            </div>
         `;
      });

      html += `
            </div>

            <!-- Voucher Section -->
            <div class="seller-voucher">
               <div class="voucher-input-group">
                  <input type="text" class="voucher-input" placeholder="Mã giảm giá của người bán"
                         data-seller-id="${sellerId}">
                  <button class="voucher-btn" onclick="applySellerVoucher(${sellerId})">
                     Áp dụng
                  </button>
               </div>
            </div>

            <!-- Note for Shop -->
            <div class="seller-note">
               <input type="text" class="note-input" placeholder="Lời nhắn cho người bán"
                      data-seller-id="${sellerId}">
            </div>

            <!-- Shipping Method -->
            <div class="seller-shipping">
               <label class="shipping-label">Phương thức vận chuyển</label>
               <select class="shipping-select" data-seller-id="${sellerId}" onchange="updateShipping(${sellerId})">
                  <option value="standard" data-fee="0" selected>Tiêu chuẩn - Miễn phí</option>
                  <option value="express" data-fee="20000">Nhanh - 20.000đ</option>
                  <option value="super-express" data-fee="50000">Hỏa tốc - 50.000đ</option>
               </select>

               <!-- Shipping Detail Box -->
               <div class="shipping-detail-box" id="shipping-detail-${sellerId}" style="display: none;">
                  <div class="shipping-detail-content">
                     <i class="mdi mdi-check-circle"></i>
                     <div class="shipping-info">
                        <div class="shipping-method-name">Quốc tế Tiêu chuẩn - Standard International</div>
                        <div class="shipping-date">Nhận từ 9 Th11 - 10 Th11</div>
                        <div class="shipping-note">Nhận Voucher trị giá 15.000đ nếu đơn hàng được giao đến bạn sau ngày 10 Tháng 11 2025.</div>
                     </div>
                     <div class="shipping-fee" id="shipping-fee-${sellerId}">
                        <span class="fee-amount">Miễn Phí</span>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Seller Subtotal -->
            <div class="seller-subtotal">
               <span>Tổng số tiền (${group.items.length} sản phẩm)</span>
               <span class="subtotal-amount" id="seller-subtotal-${sellerId}">${formatNumber(group.subtotal)} VNĐ</span>
            </div>

            <!-- Seller Discount (if applied) -->
            <div class="seller-discount" id="seller-discount-${sellerId}" style="display: none;">
               <span>Giảm giá Shop</span>
               <span class="discount-amount">-0 VNĐ</span>
            </div>
         </div>
      `;
   });

   // Save current shipping selections, voucher flags, and seller vouchers before re-rendering
   const savedShippingStates = {};
   const savedSellerVouchers = {};
   $('.shipping-select').each(function() {
      const sellerId = $(this).data('seller-id');
      savedShippingStates[sellerId] = {
         method: $(this).val(),
         platformFreeShipping: $(this).data('platform-free-shipping') || false,
         sellerFreeShipping: $(this).data('seller-free-shipping') || false
      };
   });

   // Save seller voucher states
   $('.voucher-input').each(function() {
      const sellerId = $(this).data('seller-id');
      const voucherCode = $(this).val();
      const isApplied = $(this).prop('disabled');
      savedSellerVouchers[sellerId] = {
         code: voucherCode,
         applied: isApplied
      };
   });

   $('#orderItems').html(html);

   // Restore shipping selections and flags
   Object.keys(savedShippingStates).forEach(sellerId => {
      const state = savedShippingStates[sellerId];
      const select = $(`.shipping-select[data-seller-id="${sellerId}"]`);

      if (state.method) {
         select.val(state.method);
      }

      select.data('platform-free-shipping', state.platformFreeShipping);
      select.data('seller-free-shipping', state.sellerFreeShipping);

      // Update UI for this seller
      updateShipping(sellerId);
   });

   // Restore seller vouchers
   Object.keys(savedSellerVouchers).forEach(sellerId => {
      const voucherState = savedSellerVouchers[sellerId];
      if (voucherState.code) {
         const input = $(`.voucher-input[data-seller-id="${sellerId}"]`);
         const btn = $(`.voucher-btn[onclick*="${sellerId}"], .voucher-remove-btn[onclick*="${sellerId}"]`);

         input.val(voucherState.code);

         if (voucherState.applied) {
            input.prop('disabled', true);
            btn.html('Hủy').removeClass('voucher-btn').addClass('voucher-remove-btn')
               .attr('onclick', `removeSellerVoucher(${sellerId})`);
         }
      }
   });

   // Store original total for discount calculation
   window.orderData = {
      items: items,
      groupedItems: groupedItems,
      originalTotal: total
   };

   // Update totals (will apply discounts if any)
   updateOrderTotals();
}

// Update order totals with discounts
function updateOrderTotals() {
   if (!window.orderData) return;

   const groupedItems = window.orderData.groupedItems;
   let totalProducts = 0; // Tổng tiền hàng
   let totalShipping = 0; // Tổng phí vận chuyển
   let totalShippingDiscount = 0; // Giảm giá phí vận chuyển
   let totalVoucherDiscount = 0; // Tổng voucher giảm giá (sản phẩm)

   // Calculate for each seller
   Object.keys(groupedItems).forEach(sellerId => {
      const group = groupedItems[sellerId];
      let sellerSubtotal = group.subtotal;
      let sellerDiscount = 0;

      // Apply seller-specific discount (chỉ giảm giá sản phẩm)
      if (window.sellerDiscounts && window.sellerDiscounts[sellerId]) {
         const discount = window.sellerDiscounts[sellerId];
         if (discount.type === 'percent') {
            sellerDiscount = sellerSubtotal * discount.value / 100;
         } else if (discount.type === 'fixed') {
            sellerDiscount = Math.min(discount.value, sellerSubtotal);
         }

         // Show discount for seller
         const discountEl = $(`#seller-discount-${sellerId}`);
         discountEl.find('.discount-amount').text(`-${formatNumber(sellerDiscount)} VNĐ`);
         discountEl.show();

         totalVoucherDiscount += sellerDiscount;
      }

      totalProducts += sellerSubtotal;

      // Calculate shipping fee for this seller
      const select = $(`.shipping-select[data-seller-id="${sellerId}"]`);
      const selectedOption = select.find('option:selected');
      const originalFee = parseInt(selectedOption.data('fee') || 0);
      const currentFee = select.data('current-fee') || originalFee;
      const hasFreeShipping = select.data('seller-free-shipping') || select.data('platform-free-shipping');

      if (hasFreeShipping && originalFee > 0) {
         totalShipping += originalFee;
         totalShippingDiscount += originalFee;
      } else {
         totalShipping += currentFee;
      }
   });

   // Apply platform-wide discount (chỉ giảm giá sản phẩm)
   let platformDiscount = 0;
   if (window.platformDiscount) {
      const productsAfterSellerDiscount = totalProducts - totalVoucherDiscount;
      if (window.platformDiscount.type === 'percent') {
         platformDiscount = productsAfterSellerDiscount * window.platformDiscount.value / 100;
      } else if (window.platformDiscount.type === 'fixed') {
         platformDiscount = Math.min(window.platformDiscount.value, productsAfterSellerDiscount);
      }
      totalVoucherDiscount += platformDiscount;
   }

   // Calculate final amounts
   const finalProductTotal = totalProducts - totalVoucherDiscount;
   const finalShippingTotal = totalShipping - totalShippingDiscount;

   // Calculate deposit (7.5% for scrap_dealer users)
   let depositAmount = 0;
   @if(auth()->check() && auth()->user()->role === 'scrap_dealer')
      depositAmount = totalProducts * 0.075; // 7.5% of product total
   @endif

   const grandTotal = finalProductTotal + finalShippingTotal + depositAmount;

   // Update display
   $('#subtotal').text(formatNumber(totalProducts) + ' VNĐ');
   $('#totalShippingFee').text(formatNumber(totalShipping) + ' VNĐ');

   // Show/hide shipping discount
   if (totalShippingDiscount > 0) {
      $('#shippingDiscount').text(`-${formatNumber(totalShippingDiscount)} VNĐ`);
      $('#shippingDiscountRow').show();
   } else {
      $('#shippingDiscountRow').hide();
   }

   // Show/hide voucher discount
   if (totalVoucherDiscount > 0) {
      $('#voucherDiscount').text(`-${formatNumber(totalVoucherDiscount)} VNĐ`);
      $('#voucherDiscountRow').show();
   } else {
      $('#voucherDiscountRow').hide();
   }

   // Update deposit amount (for scrap_dealer users)
   @if(auth()->check() && auth()->user()->role === 'scrap_dealer')
      $('#depositAmount').text(formatNumber(depositAmount) + ' VNĐ');
   @endif

   $('#total').html(`${formatNumber(grandTotal)} <small class="text-muted">VNĐ</small>`);
}

// Apply discount code (platform-wide) - Áp dụng cho toàn bộ đơn hàng
function applyDiscount() {
   const code = $('#discountCode').val().trim();
   if (!code) {
      showToast('Vui lòng nhập mã giảm giá!', 'error');
      return;
   }

   // Calculate total order value (before discount)
   let orderValue = 0;
   if (window.orderData && window.orderData.groupedItems) {
      Object.keys(window.orderData.groupedItems).forEach(sellerId => {
         const group = window.orderData.groupedItems[sellerId];
         if (group.items) {
            group.items.forEach(item => {
               orderValue += parseFloat(item.post.price) * parseFloat(item.quantity);
            });
         }
      });
   }

   // Check if order value is valid
   if (isNaN(orderValue) || orderValue <= 0) {
      showToast('Không có sản phẩm trong giỏ hàng!', 'error');
      return;
   }

   // Calculate total shipping fee
   let totalShippingFee = 0;
   $('.shipping-select').each(function() {
      const selectedOption = $(this).find('option:selected');
      const fee = parseFloat(selectedOption.data('fee')) || 0;
      totalShippingFee += fee;
   });

   // Clear previous platform voucher
   clearPlatformVoucher();

   // Show loading
   const $btn = $('#discountBtn');
   const originalText = $btn.html();
   $btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

   // Call API to validate and apply voucher
   $.ajax({
      url: '{{ route("voucher.apply") }}',
      method: 'POST',
      data: {
         code: code,
         order_value: orderValue,
         shipping_fee: totalShippingFee,
         _token: '{{ csrf_token() }}'
      },
      success: function(response) {
         if (response.success) {
            const voucher = response.data;

            // Apply voucher based on type
            if (voucher.type === 'percent') {
               window.platformDiscount = {
                  type: 'percent',
                  value: voucher.value,
                  id: voucher.voucher_id, // Voucher ID for database tracking
                  code: voucher.code
               };
            } else if (voucher.type === 'fixed') {
               window.platformDiscount = {
                  type: 'fixed',
                  value: voucher.value,
                  id: voucher.voucher_id, // Voucher ID for database tracking
                  code: voucher.code
               };
            } else if (voucher.type === 'freeship') {
               window.platformDiscount = {
                  type: 'freeship',
                  value: 0,
                  id: voucher.voucher_id, // Voucher ID for database tracking
                  code: voucher.code
               };
               // Set platform free shipping flag for all sellers
               $('.shipping-select').each(function() {
                  $(this).data('platform-free-shipping', true);
                  const sellerId = $(this).data('seller-id');
                  // Update UI to show free shipping
                  updateShipping(sellerId);
               });
            }

            // Update order summary
            loadOrderSummary();

            showToast(`${response.message} Giảm ${formatNumber(voucher.discount_amount)} VNĐ`, 'success');

            // Show applied voucher
            $('#discountCode').val(voucher.code).prop('disabled', true);
            $btn.html('Hủy').removeClass('btn-outline-secondary').addClass('btn-danger')
               .off('click').on('click', function() { removePlatformVoucher(); })
               .prop('disabled', false);
         } else {
            showToast(response.message, 'error');
            $btn.html(originalText).prop('disabled', false);
         }
      },
      error: function(xhr) {
         let message = 'Có lỗi xảy ra khi áp dụng mã giảm giá!';
         if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
         }
         showToast(message, 'error');
         $btn.html(originalText).prop('disabled', false);
      }
   });
}

// Remove platform voucher
function removePlatformVoucher() {
   $.ajax({
      url: '{{ route("voucher.remove") }}',
      method: 'POST',
      data: {
         _token: '{{ csrf_token() }}'
      },
      success: function(response) {
         clearPlatformVoucher();

         // Reset input
         $('#discountCode').val('').prop('disabled', false);
         const $btn = $('#discountBtn');
         $btn.html('Áp dụng').removeClass('btn-danger').addClass('btn-outline-secondary')
            .off('click').on('click', function() { applyDiscount(); });

         showToast('Đã hủy mã giảm giá', 'success');
      },
      error: function() {
         showToast('Có lỗi xảy ra!', 'error');
      }
   });
}

// Clear platform voucher
function clearPlatformVoucher() {
   // Reset platform free shipping
   $('.shipping-select').each(function() {
      const select = $(this);
      const sellerId = select.data('seller-id');

      // Only reset platform free shipping, keep seller free shipping
      if (select.data('platform-free-shipping')) {
         select.data('platform-free-shipping', false);

         const selectedOption = select.find('option:selected');
         const originalFee = parseInt(selectedOption.data('fee') || 0);
         const feeContainer = $(`#shipping-fee-${sellerId}`);

         // Restore original fee if no seller free shipping
         if (!select.data('seller-free-shipping')) {
            if (originalFee === 0) {
               feeContainer.html('<span class="fee-amount">Miễn Phí</span>');
            } else {
               feeContainer.html('<span class="fee-amount">' + formatNumber(originalFee) + 'đ</span>');
            }
            select.data('current-fee', originalFee);
         }
      }
   });

   // Reset platform product discount
   window.platformDiscount = null;

   // Update summary
   loadOrderSummary();
}

// Apply platform-wide free shipping (all sellers)
function applyPlatformFreeShipping() {
   // Apply free shipping to all sellers
   $('.shipping-select').each(function() {
      const select = $(this);
      const sellerId = select.data('seller-id');
      const selectedOption = select.find('option:selected');
      const originalFee = parseInt(selectedOption.data('fee') || 0);

      if (originalFee > 0) {
         const feeContainer = $(`#shipping-fee-${sellerId}`);
         feeContainer.html(`
            <span class="fee-original">${formatNumber(originalFee)}đ</span>
            <span class="fee-amount">Miễn Phí</span>
         `);
         select.data('platform-free-shipping', true);
         select.data('current-fee', 0);
      }
   });

   // Update totals
   updateOrderTotals();
}

// Apply platform-wide percent discount
function applyPlatformPercentDiscount(percent) {
   // Store platform discount
   window.platformDiscount = {
      type: 'percent',
      value: percent
   };

   // Update display
   updateOrderTotals();
}

// Apply platform-wide fixed discount
function applyPlatformFixedDiscount(amount) {
   // Store platform discount
   window.platformDiscount = {
      type: 'fixed',
      value: amount
   };

   // Update display
   updateOrderTotals();
}

// Apply seller voucher
// Apply seller voucher - CHỈ áp dụng cho sản phẩm của người bán đó
function applySellerVoucher(sellerId) {
   const input = $(`.voucher-input[data-seller-id="${sellerId}"]`);
   const code = input.val().trim();

   if (!code) {
      showToast('Vui lòng nhập mã giảm giá của người bán!', 'error');
      return;
   }

   // Calculate seller's order value
   let sellerOrderValue = 0;
   if (window.orderData && window.orderData.groupedItems && window.orderData.groupedItems[sellerId]) {
      const sellerItems = window.orderData.groupedItems[sellerId].items;
      sellerItems.forEach(item => {
         sellerOrderValue += parseFloat(item.post.price) * parseFloat(item.quantity);
      });
   }

   // Check if order value is valid
   if (isNaN(sellerOrderValue) || sellerOrderValue <= 0) {
      showToast('Không có sản phẩm của người bán này!', 'error');
      return;
   }

   // Get shipping fee for this seller
   const shippingSelect = $(`.shipping-select[data-seller-id="${sellerId}"]`);
   const selectedOption = shippingSelect.find('option:selected');
   const shippingFee = parseFloat(selectedOption.data('fee')) || 0;

   // Clear previous seller voucher
   clearSellerVoucher(sellerId);

   // Show loading
   const btn = $(`.voucher-btn[onclick*="${sellerId}"]`);
   const originalText = btn.html();
   btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
   input.prop('disabled', true);

   // Call API to validate and apply voucher
   $.ajax({
      url: '{{ route("voucher.apply") }}',
      method: 'POST',
      data: {
         code: code,
         order_value: sellerOrderValue,
         seller_id: sellerId,
         shipping_fee: shippingFee,
         _token: '{{ csrf_token() }}'
      },
      success: function(response) {
         if (response.success) {
            const voucher = response.data;

            // Store seller voucher
            if (!window.sellerDiscounts) {
               window.sellerDiscounts = {};
            }

            window.sellerDiscounts[sellerId] = {
               type: voucher.type,
               value: voucher.value,
               id: voucher.voucher_id,
               code: voucher.code,
               discount_amount: voucher.discount_amount
            };

            // If freeship voucher, set flag and update UI
            if (voucher.type === 'freeship') {
               shippingSelect.data('seller-free-shipping', true);
               updateShipping(sellerId);
            }

            // Update order summary
            updateOrderTotals();

            const discountMsg = voucher.type === 'freeship'
               ? 'Miễn phí vận chuyển!'
               : `Giảm ${formatNumber(voucher.discount_amount)} VNĐ`;
            showToast(`${response.message} ${discountMsg}`, 'success');

            // Change button to remove
            input.prop('disabled', true);
            btn.html('Hủy').removeClass('voucher-btn').addClass('voucher-remove-btn')
               .attr('onclick', `removeSellerVoucher(${sellerId})`)
               .prop('disabled', false);
         } else {
            showToast(response.message, 'error');
            input.prop('disabled', false);
            btn.html(originalText).prop('disabled', false);
         }
      },
      error: function(xhr) {
         let message = 'Có lỗi xảy ra khi áp dụng mã giảm giá!';
         if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
         }
         showToast(message, 'error');
         input.prop('disabled', false);
         btn.html(originalText).prop('disabled', false);
      }
   });
}

// Remove seller voucher
function removeSellerVoucher(sellerId) {
   // Check if it was a freeship voucher
   const wasFreeship = window.sellerDiscounts && window.sellerDiscounts[sellerId]
      && window.sellerDiscounts[sellerId].type === 'freeship';

   // If it was freeship, restore original shipping fee
   if (wasFreeship) {
      const shippingSelect = $(`.shipping-select[data-seller-id="${sellerId}"]`);
      const selectedOption = shippingSelect.find('option:selected');
      const originalFee = parseInt(selectedOption.data('fee') || 0);
      const feeContainer = $(`#shipping-fee-${sellerId}`);

      if (originalFee === 0) {
         feeContainer.html('<span class="fee-amount">Miễn Phí</span>');
      } else {
         feeContainer.html('<span class="fee-amount">' + formatNumber(originalFee) + 'đ</span>');
      }
      shippingSelect.data('current-fee', originalFee);
   }

   clearSellerVoucher(sellerId);
   updateOrderTotals();
   showToast('Đã hủy mã giảm giá của shop này', 'info');

   // Reset button
   const input = $(`.voucher-input[data-seller-id="${sellerId}"]`);
   const btn = $(`.voucher-remove-btn[onclick*="${sellerId}"], .voucher-btn[onclick*="${sellerId}"]`);

   input.val('').prop('disabled', false);
   btn.html('Áp dụng').removeClass('voucher-remove-btn').addClass('voucher-btn')
      .attr('onclick', `applySellerVoucher(${sellerId})`);
}

// Clear seller voucher data
function clearSellerVoucher(sellerId) {
   if (window.sellerDiscounts && window.sellerDiscounts[sellerId]) {
      delete window.sellerDiscounts[sellerId];
   }
   $(`#seller-discount-${sellerId}`).hide();
}

// Update shipping method for seller
function updateShipping(sellerId) {
   const select = $(`.shipping-select[data-seller-id="${sellerId}"]`);
   const method = select.val();
   const selectedOption = select.find('option:selected');
   const fee = parseInt(selectedOption.data('fee') || 0);

   const detailBox = $(`#shipping-detail-${sellerId}`);
   const feeContainer = $(`#shipping-fee-${sellerId}`);

   if (method) {
      // Show detail box
      detailBox.show();

      // Get shipping method details
      let methodName = '';
      let methodDate = '';
      let methodNote = '';
      
      // Calculate delivery dates based on current date
      const today = new Date();
      let startDays, endDays;

      switch(method) {
         case 'standard':
            methodName = 'Tiêu chuẩn - Standard Shipping';
            startDays = 10;
            endDays = 15;
            methodNote = 'Giao hàng tiêu chuẩn trong 10-15 ngày.';
            break;
         case 'express':
            methodName = 'Nhanh - Express Shipping';
            startDays = 5;
            endDays = 7;
            methodNote = 'Giao hàng nhanh trong 5-7 ngày.';
            break;
         case 'super-express':
            methodName = 'Hỏa tốc - Super Express';
            startDays = 2;
            endDays = 3;
            methodNote = 'Giao hàng hỏa tốc trong 2-3 ngày.';
            break;
      }
      
      // Calculate delivery date range
      const startDate = new Date(today);
      startDate.setDate(today.getDate() + startDays);
      const endDate = new Date(today);
      endDate.setDate(today.getDate() + endDays);
      
      const formatDate = (date) => {
         const day = date.getDate();
         const month = date.getMonth() + 1;
         return `${day}/${month}`;
      };
      
      methodDate = `Nhận từ ${formatDate(startDate)} - ${formatDate(endDate)}`;

      // Update content
      detailBox.find('.shipping-method-name').text(methodName);
      detailBox.find('.shipping-date').text(methodDate);
      detailBox.find('.shipping-note').text(methodNote);

      // Check for free shipping vouchers
      const hasPlatformFreeShip = select.data('platform-free-shipping');
      const hasSellerFreeShip = select.data('seller-free-shipping');

      // Update fee display
      if (hasPlatformFreeShip || hasSellerFreeShip) {
         if (fee > 0) {
            feeContainer.html('<span class="fee-amount" style="text-decoration: line-through;">' + formatNumber(fee) + 'đ</span> <span class="free-label" style="color: #28a745; font-weight: 600;">Miễn phí</span>');
         } else {
            feeContainer.html('<span class="fee-amount">Miễn Phí</span>');
         }
         select.data('current-fee', 0);
      } else if (fee === 0) {
         feeContainer.html('<span class="fee-amount">Miễn Phí</span>');
         select.data('current-fee', fee);
      } else {
         feeContainer.html('<span class="fee-amount">' + formatNumber(fee) + 'đ</span>');
         select.data('current-fee', fee);
      }

      // Clear seller free shipping flag when changing method (but keep platform flag)
      select.data('seller-free-shipping', false);
   } else {
      // Hide detail box if no method selected
      detailBox.hide();
      select.data('current-fee', 0);
   }

   // Recalculate total with shipping fees
   updateOrderTotals();
}// Complete order
// Form submit handler
$('#checkoutForm').on('submit', function(e) {
   e.preventDefault();

   console.log('Form submit triggered');
   console.log('window.orderData:', window.orderData);

   // Validate form
   const email = $('#email').val();
   const phone = $('#phone').val();
   const fullName = $('#fullName').val();
   const address = $('#address').val();
   const state = $('#state').val();
   const district = $('#district').val();
   const ward = $('#ward').val();
   const notes = $('#notes').val();
   const saveInfo = $('#saveInfo').is(':checked');

   if (!email || !phone || !fullName || !address || !state || !district || !ward) {
      alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
      return false;
   }

   // Validate email format
   const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
   if (!emailRegex.test(email)) {
      alert('Email không hợp lệ!');
      return false;
   }

   // Validate phone format (Vietnamese phone number)
   // Accept 10-11 digits starting with 0 or +84
   const phoneClean = phone.replace(/[\s\-]/g, '');
   const phoneRegex = /^(0|\+84)\d{9,10}$/;
   if (!phoneRegex.test(phoneClean)) {
      alert('Số điện thoại không hợp lệ! Vui lòng nhập số điện thoại Việt Nam hợp lệ (VD: 0912345678 hoặc 0123456789)');
      return false;
   }

   // Build full address
   let fullAddress = `${address}, ${ward}, ${district}, ${state}`;

   // Get coordinates from marker
   let latitude = null;
   let longitude = null;
   if (marker) {
      const lngLat = marker.getLngLat();
      latitude = lngLat.lat;
      longitude = lngLat.lng;
   }

   // Collect order data
   const orderData = {
      // Customer information
      email: email,
      phone: phone,
      full_name: fullName,

      // Shipping address
      address: address,
      notes: notes,
      ward: ward,
      district: district,
      province: state,
      full_address: fullAddress,
      latitude: latitude,
      longitude: longitude,
      save_info: saveInfo ? 1 : 0,

      // Payment method
      payment_method: $('input[name="payment"]:checked').val() || 'cod',

      // Order items grouped by seller
      sellers: [],

      // Totals
      subtotal: 0,
      shipping_total: 0,
      discount_total: 0,
      grand_total: 0
   };

   // Collect items, shipping, and notes for each seller
   if (window.orderData && window.orderData.groupedItems) {
      Object.keys(window.orderData.groupedItems).forEach(sellerId => {
         const group = window.orderData.groupedItems[sellerId];
         const items = group.items; // Get items array from group object
         const shippingSelect = $(`.shipping-select[data-seller-id="${sellerId}"]`);
         const shippingMethod = shippingSelect.val();
         const shippingFee = parseInt(shippingSelect.data('current-fee') || 0);
         const noteInput = $(`.seller-note[data-seller-id="${sellerId}"]`);
         const note = noteInput.val();

         // Get seller discount if any
         const sellerDiscount = (window.sellerDiscounts && window.sellerDiscounts[sellerId])
            ? window.sellerDiscounts[sellerId]
            : { type: 'none', value: 0 };

         // Calculate seller subtotal
         let sellerSubtotal = 0;
         const sellerItems = [];

         items.forEach(item => {
            const itemPrice = item.post.price;
            sellerSubtotal += itemPrice * item.quantity;
            sellerItems.push({
               post_id: item.post.id,
               quantity: item.quantity,
               price: itemPrice,
               subtotal: itemPrice * item.quantity
            });
         });

         orderData.sellers.push({
            seller_id: sellerId,
            items: sellerItems,
            shipping_method: shippingMethod,
            shipping_fee: shippingFee,
            note: note,
            voucher_id: sellerDiscount.id || null, // Seller voucher ID
            discount_amount: sellerDiscount.discount_amount || 0, // Discount amount from voucher
            subtotal: sellerSubtotal
         });

         orderData.subtotal += sellerSubtotal;
         orderData.shipping_total += shippingFee;

         // Add seller discount to total discount
         if (sellerDiscount.discount_amount) {
            orderData.discount_total += sellerDiscount.discount_amount;
         }
      });
   }

   // Add platform discount
   if (window.platformDiscount) {
      orderData.voucher_id = window.platformDiscount.id; // Voucher ID for tracking

      // Calculate platform discount amount
      let platformDiscountAmount = 0;
      if (window.platformDiscount.type === 'percent') {
         platformDiscountAmount = Math.round(orderData.subtotal * window.platformDiscount.value / 100);
      } else if (window.platformDiscount.type === 'fixed') {
         platformDiscountAmount = window.platformDiscount.value;
      }

      // Add platform discount to total discount
      orderData.discount_total += platformDiscountAmount;
   }

   // Calculate grand total
   orderData.grand_total = orderData.subtotal + orderData.shipping_total - orderData.discount_total;

   // Validate cart has items
   if (!orderData.sellers || orderData.sellers.length === 0) {
      alert('Giỏ hàng trống! Vui lòng thêm sản phẩm vào giỏ hàng trước khi đặt hàng.');
      return false;
   }

   // Debug: log order data
   console.log('Order Data:', orderData);

   // Populate hidden field with order data
   $('#orderDataInput').val(JSON.stringify(orderData));

   // Disable button and show loading
   const $button = $('.complete-order-btn');
   $button.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Đang xử lý...');

   // Submit the form
   this.submit();
});

// Format number
function formatNumber(num) {
   return new Intl.NumberFormat('vi-VN').format(num);
}

// Show toast notification
function showToast(message, type = 'success') {
   // Remove existing toast if any
   $('.toast-notification').remove();

   const bgColor = type === 'success' ? '#d4edda' : '#f8d7da';
   const textColor = type === 'success' ? '#155724' : '#721c24';
   const borderColor = type === 'success' ? '#c3e6cb' : '#f5c6cb';

   const toast = $(`
      <div class="toast-notification" style="
         position: fixed;
         top: 20px;
         right: 20px;
         background: ${bgColor};
         color: ${textColor};
         border: 1px solid ${borderColor};
         padding: 15px 20px;
         border-radius: 8px;
         box-shadow: 0 4px 12px rgba(0,0,0,0.15);
         z-index: 9999;
         min-width: 250px;
         animation: slideIn 0.3s ease;
      ">
         ${message}
      </div>
   `);

   $('body').append(toast);

   setTimeout(() => {
      toast.fadeOut(300, function() {
         $(this).remove();
      });
   }, 3000);
}
</script>

<style>
@keyframes slideIn {
   from {
      transform: translateX(100%);
      opacity: 0;
   }
   to {
      transform: translateX(0);
      opacity: 1;
   }
}
</style>

@endsection
