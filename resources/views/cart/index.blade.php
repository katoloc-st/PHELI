@extends('layouts.app')

@section('title', 'Giỏ hàng - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Inner Header -->
      <section class="bg-dark py-5 user-header">
         <div class="container">
            <div class="row align-items-center mt-2 mb-5 pb-4">
               <div class="col">
                  <!-- Heading -->
                  <h1 class="text-white mb-2" style="font-family: 'Barlow', sans-serif;">
                     Giỏ hàng của bạn
                  </h1>
                  <!-- Text -->
                  <h6 class="font-weight-normal text-white-50 mb-0">
                     Quản lý các sản phẩm phế liệu bạn muốn mua
                  </h6>
               </div>
               <div class="col-auto">
                  <!-- Button -->
                  <a href="{{ route('posts.index') }}" class="btn btn-outline-light mr-2">
                     <i class="mdi mdi-arrow-left"></i> Tiếp tục mua sắm
                  </a>
               </div>
            </div>
            <!-- / .row -->
         </div>
         <!-- / .container -->
      </section>
      <!-- End Inner Header -->

      <!-- Cart Content -->
      <section class="section-padding pt-0 user-pages-main">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-3">
                  <!-- Sidebar -->
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

                  <!-- Cart Items -->
                  <div id="cartItemsContainer">
                     <!-- Loading state -->
                     <div class="text-center py-5" id="cartLoading">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                           <span class="sr-only">Loading...</span>
                        </div>
                        <p class="text-muted mt-3">Đang tải giỏ hàng...</p>
                     </div>
                  </div>

                  <!-- Cart Summary (will be shown when cart has items) -->
                  <div id="cartSummarySection" style="display: none;">
                     <div class="card mt-4">
                        <div class="card-body">
                           <h4 class="card-title mb-4">Tóm tắt đơn hàng</h4>

                           <div class="d-flex justify-content-between mb-3">
                              <span class="h5">Tạm tính:</span>
                              <span class="h5" id="subtotalAmount">0 VNĐ</span>
                           </div>

                           <div class="d-flex justify-content-between mb-3 text-muted">
                              <span>Phí vận chuyển:</span>
                              <span>Liên hệ sau</span>
                           </div>

                           <div class="d-flex justify-content-between mb-3 text-muted">
                              <span>Thuế VAT:</span>
                              <span>Liên hệ sau</span>
                           </div>

                           <hr>

                           <div class="d-flex justify-content-between mb-4">
                              <span class="h4 font-weight-bold">Tổng cộng:</span>
                              <span class="h4 font-weight-bold text-success" id="totalAmount">0 VNĐ</span>
                           </div>

                           <button class="btn btn-primary btn-lg btn-block mb-2" onclick="proceedToCheckout()">
                              <i class="fas fa-credit-card mr-2"></i>Tiến hành thanh toán
                           </button>

                           <button class="btn btn-outline-danger btn-lg btn-block" onclick="clearAllCart()">
                              <i class="fas fa-trash-alt mr-2"></i>Xóa toàn bộ giỏ hàng
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Cart Content -->

      <!-- Join Team -->
      <section class="py-5 bg-primary">
         <div class="container">
            <div class="row align-items-center text-center text-md-left">
               <div class="col-md-8">
                  <h2 class="text-white my-2">Tham gia đội ngũ chuyên nghiệp của chúng tôi</h2>
               </div>
               <div class="col-md-4 text-center text-md-right">
                  <button type="button" class="btn btn-outline-light my-2">Tìm hiểu thêm</button>
                  <button type="button" class="btn btn-light my-2">Liên hệ</button>
               </div>
            </div>
         </div>
      </section>
      <!-- End Join Team -->
@endsection

@section('custom-css')
<style>
/* Cart Item Card Styles */
.cart-item-card {
   transition: all 0.3s ease;
   border: 1px solid #e9ecef;
   margin-bottom: 20px;
   border-radius: 10px;
   overflow: hidden;
}

.cart-item-card:hover {
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.cart-item-card .row {
   min-height: 180px;
}

.cart-item-card .col-lg-4,
.cart-item-card .col-md-5 {
   min-height: 180px;
   overflow: hidden;
   padding: 0 !important;
   position: relative;
}

.cart-item-image {
   position: absolute;
   top: 0;
   left: 0;
   width: 100% !important;
   height: 100% !important;
   min-width: 100%;
   min-height: 180px;
   max-width: none;
   object-fit: cover;
   object-position: center;
   border-radius: 10px 0 0 0;
   display: block;
}

.cart-item-title {
   font-size: 20px;
   font-weight: 700;
   color: #333;
   margin-bottom: 0;
}

.cart-item-meta {
   font-size: 14px;
   color: #666;
   line-height: 1.6;
}

.cart-item-meta i {
   margin-right: 5px;
}

.cart-item-meta strong {
   color: #333;
}

.cart-item-price-section {
   margin-top: 15px;
}

.cart-item-price {
   font-size: 28px;
   font-weight: 700;
   color: #667eea;
   margin-bottom: 5px;
}

.cart-item-unit-price {
   font-size: 14px;
   color: #999;
}

/* Cart Item Footer */
.cart-item-footer {
   background-color: #f8f9fa;
}

.qty-label {
   font-size: 16px;
   font-weight: 600;
   color: #333;
}

/* Quantity Controls Wrapper */
.qty-control-wrapper {
   display: flex;
   align-items: center;
   gap: 10px;
}

.qty-control-wrapper.readonly {
   gap: 0;
}

.qty-btn {
   width: 36px;
   height: 36px;
   border: 1px solid #ddd;
   background: white;
   cursor: pointer;
   display: flex;
   align-items: center;
   justify-content: center;
   border-radius: 4px;
   transition: all 0.2s ease;
   font-size: 14px;
   color: #667eea;
}

.qty-btn:hover:not(:disabled) {
   background: #667eea;
   border-color: #667eea;
   color: white;
}

.qty-btn:disabled {
   opacity: 0.3;
   cursor: not-allowed;
}

.qty-input {
   width: 80px;
   height: 36px;
   text-align: center;
   border: 1px solid #ddd;
   border-radius: 4px;
   font-weight: 600;
   font-size: 16px;
}

.qty-input.readonly {
   background-color: #e9ecef;
   cursor: not-allowed;
   border-color: #ced4da;
   color: #495057;
}

.qty-input::-webkit-inner-spin-button,
.qty-input::-webkit-outer-spin-button {
   -webkit-appearance: none;
   margin: 0;
}

.qty-input[type=number] {
   -moz-appearance: textfield;
}

.qty-input:focus:not(.readonly) {
   outline: none;
   border-color: #667eea;
   box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Action Buttons */
.btn-remove {
   color: #dc3545;
   border-color: #dc3545;
   padding: 5px 10px;
   font-size: 14px;
}

.btn-remove:hover {
   background-color: #dc3545;
   color: white;
   transform: scale(1.05);
}

.btn-remove i {
   font-size: 16px;
}

/* Empty Cart State */
.empty-cart-state {
   text-align: center;
   padding: 60px 20px;
}

.empty-cart-icon {
   font-size: 80px;
   color: #ddd;
   margin-bottom: 20px;
}

/* Summary Card */
.card {
   border-radius: 10px;
   border: 1px solid #e9ecef;
}

.card-title {
   font-weight: 700;
   color: #333;
}

/* Toast Notification */
.toast-notification {
   position: fixed;
   top: 20px;
   right: 20px;
   padding: 15px 20px;
   border-radius: 8px;
   box-shadow: 0 4px 15px rgba(0,0,0,0.2);
   display: flex;
   align-items: center;
   gap: 12px;
   z-index: 10000;
   opacity: 0;
   transform: translateX(400px);
   transition: all 0.3s ease;
   min-width: 300px;
}

.toast-notification.show {
   opacity: 1;
   transform: translateX(0);
}

.toast-success {
   background: #d4edda;
   border: 1px solid #c3e6cb;
   color: #155724;
}

.toast-error {
   background: #f8d7da;
   border: 1px solid #f5c6cb;
   color: #721c24;
}

/* Loading Overlay */
.loading-overlay {
   position: absolute;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background: rgba(255, 255, 255, 0.8);
   display: flex;
   align-items: center;
   justify-content: center;
   z-index: 1000;
   border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
   .cart-item-image {
      height: 150px;
   }

   .cart-item-title {
      font-size: 16px;
   }

   .cart-item-price {
      font-size: 20px;
   }
}
</style>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
   loadCartItems();

   // Auto hide success alerts
   setTimeout(function() {
      $('.alert-success').fadeOut('slow');
   }, 5000);
});

// Load cart items
function loadCartItems() {
   fetch('{{ route('cart.get') }}', {
      headers: {
         'X-Requested-With': 'XMLHttpRequest',
         'Accept': 'application/json'
      }
   })
   .then(response => response.json())
   .then(data => {
      $('#cartLoading').hide();

      if (data.success) {
         if (data.items.length === 0) {
            showEmptyCart();
         } else {
            renderCartItems(data.items, data.total);
            $('#cartSummarySection').show();
         }
      } else {
         showError(data.message || 'Không thể tải giỏ hàng');
      }
   })
   .catch(error => {
      console.error('Error loading cart:', error);
      $('#cartLoading').hide();
      showError('Có lỗi xảy ra khi tải giỏ hàng!');
   });
}

// Render cart items
function renderCartItems(items, total) {
   let html = '<div class="row">';

   items.forEach(item => {
      // Decode images if it's a JSON string
      let images = item.post.images;
      if (typeof images === 'string') {
         try {
            images = JSON.parse(images);
         } catch (e) {
            images = [];
         }
      }
      images = Array.isArray(images) ? images : [];

      const imageUrl = images.length > 0
         ? '{{ asset('storage') }}/' + images[0]
         : '{{ asset('img/list/1.png') }}';

      const canAdjustQty = !item.is_fixed_quantity;
      const totalPrice = item.post.price * item.quantity;
      const maxQty = item.post.quantity;

      // Lấy thông tin địa chỉ
      const address = item.post.collection_point
         ? `${item.post.collection_point.detailed_address}, ${item.post.collection_point.ward}, ${item.post.collection_point.province}`
         : 'Chưa có địa chỉ';

      const qtyControls = canAdjustQty ? `
         <div class="qty-control-wrapper">
            <button class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1}, ${maxQty})" ${item.quantity <= 1 ? 'disabled' : ''}>
               <i class="fas fa-minus"></i>
            </button>
            <input type="number" class="qty-input" value="${item.quantity}" min="1" max="${maxQty}"
               id="qty-${item.id}"
               data-item-id="${item.id}"
               data-max-qty="${maxQty}"
               onblur="handleQtyChange(${item.id}, ${maxQty})"
               onkeypress="handleQtyKeypress(event, ${item.id}, ${maxQty})">
            <button class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1}, ${maxQty})" ${item.quantity >= maxQty ? 'disabled' : ''}>
               <i class="fas fa-plus"></i>
            </button>
         </div>
      ` : `
         <div class="qty-control-wrapper readonly">
            <input type="number" class="qty-input readonly" value="${item.quantity}" readonly>
         </div>
      `;

      html += `
         <div class="col-lg-12 col-md-12" id="cart-item-${item.id}">
            <div class="card cart-item-card">
               <div class="card-body p-0">
                  <div class="row no-gutters">
                     <div class="col-lg-4 col-md-5">
                        <img class="cart-item-image" src="${imageUrl}" alt="${item.post.title}">
                     </div>
                     <div class="col-lg-8 col-md-7 p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                           <div class="flex-grow-1">
                              <h5 class="cart-item-title mb-2">${item.post.title}</h5>
                              <p class="cart-item-meta mb-1">
                                 <i class="mdi mdi-map-marker text-danger"></i> ${address}
                              </p>
                              <p class="cart-item-meta mb-0">
                                 <i class="mdi mdi-recycle text-success"></i> Loại phế liệu: <strong>${item.post.waste_type.name}</strong>
                              </p>
                           </div>
                           <button class="btn btn-sm btn-outline-danger btn-remove" onclick="removeCartItem(${item.id})">
                              <i class="far fa-trash-alt"></i>
                           </button>
                        </div>

                        <div class="cart-item-price-section">
                           <div class="cart-item-price">
                              ${formatNumber(totalPrice)} VNĐ
                           </div>
                           <div class="cart-item-unit-price">
                              ${formatNumber(item.post.price)} VNĐ/kg
                           </div>
                        </div>
                     </div>
                  </div>

                  <hr class="my-0">

                  <div class="cart-item-footer p-3">
                     <div class="d-flex align-items-center justify-content-between">
                        <span class="qty-label">Số lượng:</span>
                        ${qtyControls}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      `;
   });

   html += '</div>';
   $('#cartItemsContainer').html(html);

   // Update summary
   $('#subtotalAmount').text(formatNumber(total) + ' VNĐ');
   $('#totalAmount').text(formatNumber(total) + ' VNĐ');
}

// Show empty cart
function showEmptyCart() {
   const html = `
      <div class="card">
         <div class="card-body empty-cart-state">
            <div class="empty-cart-icon">
               <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>Giỏ hàng trống</h3>
            <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng.<br>Hãy khám phá các sản phẩm phế liệu của chúng tôi!</p>
            <a href="{{ route('posts.index') }}" class="btn btn-primary btn-lg">
               <i class="mdi mdi-magnify mr-2"></i>Khám phá sản phẩm
            </a>
         </div>
      </div>
   `;
   $('#cartItemsContainer').html(html);
   $('#cartSummarySection').hide();
}

// Show error
function showError(message) {
   const html = `
      <div class="card">
         <div class="card-body text-center py-5">
            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 48px;"></i>
            <h5 class="mt-3">${message}</h5>
            <button class="btn btn-primary mt-3" onclick="loadCartItems()">
               <i class="fas fa-redo mr-2"></i>Thử lại
            </button>
         </div>
      </div>
   `;
   $('#cartItemsContainer').html(html);
}

// Update cart quantity
function updateCartQuantity(itemId, newQty, maxQty) {
   if (newQty < 1 || newQty > maxQty) {
      showToast(`Số lượng phải từ 1 đến ${maxQty} kg`, 'error');
      return;
   }

   const $item = $(`#cart-item-${itemId}`);
   $item.css('position', 'relative').append('<div class="loading-overlay"><div class="spinner-border text-primary"></div></div>');

   fetch(`/api/cart/${itemId}/update`, {
      method: 'PUT',
      headers: {
         'Content-Type': 'application/json',
         'X-CSRF-TOKEN': '{{ csrf_token() }}',
         'X-Requested-With': 'XMLHttpRequest',
         'Accept': 'application/json'
      },
      body: JSON.stringify({ quantity: newQty })
   })
   .then(response => response.json())
   .then(data => {
      $item.find('.loading-overlay').remove();

      if (data.success) {
         loadCartItems();
         updateCartBadge(data.count);
         showToast('Cập nhật số lượng thành công!', 'success');
      } else {
         showToast(data.message || 'Không thể cập nhật số lượng!', 'error');
      }
   })
   .catch(error => {
      $item.find('.loading-overlay').remove();
      console.error('Error updating quantity:', error);
      showToast('Có lỗi xảy ra khi cập nhật!', 'error');
   });
}

// Handle quantity input change
function handleQtyChange(itemId, maxQty) {
   const $input = $(`#qty-${itemId}`);
   let value = parseInt($input.val());

   if (isNaN(value) || value < 1) {
      value = 1;
   } else if (value > maxQty) {
      value = maxQty;
      showToast(`Số lượng tối đa là ${maxQty} kg`, 'error');
   }

   $input.val(value);
   updateCartQuantity(itemId, value, maxQty);
}

// Handle Enter key
function handleQtyKeypress(event, itemId, maxQty) {
   if (event.key === 'Enter') {
      event.preventDefault();
      handleQtyChange(itemId, maxQty);
   }
}

// Remove cart item
function removeCartItem(itemId) {
   if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
      return;
   }

   const $item = $(`#cart-item-${itemId}`);
   $item.css('position', 'relative').append('<div class="loading-overlay"><div class="spinner-border text-danger"></div></div>');

   fetch(`/api/cart/${itemId}`, {
      method: 'DELETE',
      headers: {
         'X-CSRF-TOKEN': '{{ csrf_token() }}',
         'X-Requested-With': 'XMLHttpRequest',
         'Accept': 'application/json'
      }
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         $item.fadeOut(300, function() {
            $(this).remove();
            loadCartItems();
            updateCartBadge(data.cartCount);
         });
         showToast('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
      } else {
         $item.find('.loading-overlay').remove();
         showToast(data.message || 'Không thể xóa sản phẩm!', 'error');
      }
   })
   .catch(error => {
      $item.find('.loading-overlay').remove();
      console.error('Error removing item:', error);
      showToast('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
   });
}

// Clear all cart
function clearAllCart() {
   if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
      return;
   }

   $('#cartItemsContainer').css('position', 'relative').append('<div class="loading-overlay"><div class="spinner-border text-danger"></div></div>');

   fetch('/api/cart', {
      method: 'DELETE',
      headers: {
         'X-CSRF-TOKEN': '{{ csrf_token() }}',
         'X-Requested-With': 'XMLHttpRequest',
         'Accept': 'application/json'
      }
   })
   .then(response => response.json())
   .then(data => {
      $('#cartItemsContainer').find('.loading-overlay').remove();

      if (data.success) {
         showEmptyCart();
         updateCartBadge(0);
         $('#cartSummarySection').hide();
         showToast('Đã xóa toàn bộ giỏ hàng!', 'success');
      } else {
         showToast(data.message || 'Không thể xóa giỏ hàng!', 'error');
      }
   })
   .catch(error => {
      $('#cartItemsContainer').find('.loading-overlay').remove();
      console.error('Error clearing cart:', error);
      showToast('Có lỗi xảy ra!', 'error');
   });
}

// Proceed to checkout
function proceedToCheckout() {
   window.location.href = '{{ route('cart.checkout') }}';
}

// Update cart badge in header
function updateCartBadge(count) {
   const badge = document.getElementById('cartBadge');
   if (badge) {
      badge.textContent = count;
      if (count > 0) {
         badge.style.display = 'inline-block';
      } else {
         badge.style.display = 'none';
      }
   }
}

// Format number
function formatNumber(num) {
   return new Intl.NumberFormat('vi-VN').format(num);
}

// Show toast notification
function showToast(message, type = 'success') {
   const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
   const toast = $(`
      <div class="toast-notification toast-${type}">
         <i class="fas ${iconClass}"></i>
         <span>${message}</span>
      </div>
   `);

   $('body').append(toast);

   setTimeout(() => {
      toast.addClass('show');
   }, 100);

   setTimeout(() => {
      toast.removeClass('show');
      setTimeout(() => {
         toast.remove();
      }, 300);
   }, 3000);
}

// Prevent input invalid characters
$(document).on('input', '.qty-input', function() {
   this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection
