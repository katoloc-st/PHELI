<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
   <div class="cart-sidebar-header">
      <h3>Giỏ hàng của bạn</h3>
      <button class="cart-close-btn" onclick="closeCart()">
         <i class="fas fa-times"></i>
      </button>
   </div>

   <div class="cart-sidebar-body">
      <!-- Cart items will be loaded dynamically via JavaScript -->
      <div class="text-center py-5">
         <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
         </div>
         <p class="text-muted mt-2">Đang tải giỏ hàng...</p>
      </div>
   </div>

   <div class="cart-sidebar-footer">
      <div class="cart-summary">
         <div class="cart-summary-row">
            <span class="cart-summary-label">Tổng cộng</span>
            <span class="cart-summary-value">0 VNĐ</span>
         </div>
         <p class="cart-summary-note">Phí vận chuyển và thuế sẽ được tính khi thanh toán.</p>
      </div>

      <div class="cart-buttons">
         <button class="btn btn-dark btn-block btn-lg mb-2" onclick="checkout()">
            THANH TOÁN
         </button>
         <button class="btn btn-outline-dark btn-block btn-lg" onclick="viewCart()">
            XEM GIỎ HÀNG
         </button>
      </div>
   </div>
</div>

<!-- Cart Overlay -->
<div id="cartOverlay" class="cart-overlay" onclick="closeCart()"></div>

<style>
   /* Cart Sidebar */
   .cart-sidebar {
      position: fixed;
      top: 0;
      right: -500px;
      width: 500px;
      height: 100vh;
      background: white;
      box-shadow: -2px 0 15px rgba(0,0,0,0.2);
      transition: right 0.3s ease;
      z-index: 9999;
      display: flex;
      flex-direction: column;
   }

   .cart-sidebar.active {
      right: 0;
   }

   .cart-sidebar-header {
      padding: 25px 30px;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
      align-items: center;
   }

   .cart-sidebar-header h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
   }

   .cart-close-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      padding: 0;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
   }

   .cart-close-btn:hover {
      transform: rotate(90deg);
   }

   .cart-sidebar-body {
      flex: 1;
      overflow-y: auto;
      padding: 20px 30px;
   }

   .cart-item {
      display: flex;
      gap: 15px;
      padding: 20px 0;
      border-bottom: 1px solid #e9ecef;
   }

   .cart-item:first-child {
      padding-top: 0;
   }

   .cart-item-image {
      width: 100px;
      height: 130px;
      flex-shrink: 0;
   }

   .cart-item-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 8px;
   }

   .cart-item-details {
      flex: 1;
   }

   .cart-item-title {
      font-size: 16px;
      font-weight: 700;
      margin: 0 0 5px 0;
   }

   .cart-item-meta {
      font-size: 14px;
      color: #666;
      margin: 0 0 10px 0;
   }

   .cart-item-price-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 5px;
   }

   .cart-item-price {
      font-size: 18px;
      font-weight: 700;
      color: #000;
   }

   .cart-item-original-price {
      font-size: 14px;
      color: #999;
      text-decoration: line-through;
   }

   .cart-item-discount {
      font-size: 12px;
      color: #dc3545;
      margin: 0 0 15px 0;
   }

   .cart-item-actions {
      display: flex;
      align-items: center;
      gap: 10px;
   }

   .qty-btn {
      width: 32px;
      height: 32px;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      transition: all 0.2s ease;
   }

   .qty-btn:hover {
      background: #f8f9fa;
      border-color: #667eea;
   }

   .qty-input {
      width: 50px;
      height: 32px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-weight: 600;
      font-size: 14px;
   }

   /* Ẩn spinner của input number */
   .qty-input::-webkit-inner-spin-button,
   .qty-input::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
   }

   .qty-input[type=number] {
      -moz-appearance: textfield;
   }

   .qty-input:focus {
      outline: none;
      border-color: #667eea;
   }

   .qty-input:disabled {
      background-color: #f8f9fa;
      cursor: not-allowed;
   }

   .delete-btn {
      width: 32px;
      height: 32px;
      border: none;
      background: transparent;
      cursor: pointer;
      font-size: 18px;
      color: #dc3545;
      margin-left: auto;
      transition: all 0.2s ease;
   }

   .delete-btn:hover {
      transform: scale(1.2);
   }

   .cart-sidebar-footer {
      padding: 20px 30px;
      border-top: 1px solid #e9ecef;
      background: #f8f9fa;
   }

   .cart-summary {
      margin-bottom: 20px;
   }

   .cart-summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
   }

   .cart-summary-label {
      font-size: 16px;
      font-weight: 700;
   }

   .cart-summary-value {
      font-size: 18px;
      font-weight: 700;
   }

   .cart-summary-note {
      font-size: 12px;
      color: #666;
      margin: 15px 0 0 0;
   }

   .cart-buttons .btn {
      font-weight: 700;
      border-radius: 8px;
   }

   .cart-buttons .btn-dark {
      background: #667eea;
      border: none;
      color: white;
   }

   .cart-buttons .btn-dark:hover {
      background: #5568d3;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
   }

   .cart-buttons .btn-outline-dark {
      border: 2px solid #667eea;
      color: #667eea;
      background: white;
   }

   .cart-buttons .btn-outline-dark:hover {
      background: #667eea;
      color: white;
      border-color: #667eea;
   }

   /* Cart Overlay */
   .cart-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 9998;
      display: none;
   }

   .cart-overlay.active {
      display: block;
   }

   /* Responsive */
   @media (max-width: 576px) {
      .cart-sidebar {
         width: 100%;
         right: -100%;
      }
   }

   /* Custom Scrollbar */
   .cart-sidebar-body::-webkit-scrollbar {
      width: 8px;
   }

   .cart-sidebar-body::-webkit-scrollbar-track {
      background: #f1f1f1;
   }

   .cart-sidebar-body::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
   }

   .cart-sidebar-body::-webkit-scrollbar-thumb:hover {
      background: #555;
   }

   /* Toast Notification */
   .cart-toast {
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
      max-width: 400px;
   }

   .cart-toast.show {
      opacity: 1;
      transform: translateX(0);
   }

   .cart-toast-success {
      background: #d4edda;
      border: 1px solid #c3e6cb;
   }

   .cart-toast-success i {
      color: #28a745;
      font-size: 24px;
   }

   .cart-toast-success span {
      color: #155724;
   }

   .cart-toast-error {
      background: #f8d7da;
      border: 1px solid #f5c6cb;
   }

   .cart-toast-error i {
      color: #dc3545;
      font-size: 24px;
   }

   .cart-toast-error span {
      color: #721c24;
   }

   .cart-toast span {
      font-size: 15px;
      font-weight: 500;
      flex: 1;
   }
</style>

<script>
   // Load cart từ server khi mở sidebar
   function openCart() {
      document.getElementById('cartSidebar').classList.add('active');
      document.getElementById('cartOverlay').classList.add('active');
      document.body.style.overflow = 'hidden';
      loadCart();
   }

   function closeCart() {
      document.getElementById('cartSidebar').classList.remove('active');
      document.getElementById('cartOverlay').classList.remove('active');
      document.body.style.overflow = '';
   }

   // Load giỏ hàng từ server
   function loadCart() {
      const cartBody = document.querySelector('.cart-sidebar-body');

      // Hiển thị loading
      cartBody.innerHTML = `
         <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
               <span class="sr-only">Loading...</span>
            </div>
            <p class="text-muted mt-2">Đang tải giỏ hàng...</p>
         </div>
      `;

      fetch('{{ route('cart.get') }}', {
         headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
         }
      })
      .then(response => response.json())
      .then(data => {
         if (data.success) {
            renderCart(data);
            updateCartBadge(data.count);
         } else {
            // Nếu chưa đăng nhập hoặc có lỗi
            cartBody.innerHTML = `
               <div class="text-center py-5">
                  <i class="fas fa-user-lock" style="font-size: 48px; color: #ffc107; margin-bottom: 20px;"></i>
                  <h5 style="color: #666; margin-bottom: 10px;">${data.message || 'Không thể tải giỏ hàng'}</h5>
                  <p class="text-muted" style="font-size: 14px;">Vui lòng đăng nhập để xem giỏ hàng</p>
                  <button class="btn btn-primary mt-3" onclick="closeCart(); window.location.href='{{ route('login') }}'">
                     <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                  </button>
               </div>
            `;
            updateCartBadge(0);
         }
      })
      .catch(error => {
         console.error('Error loading cart:', error);
         cartBody.innerHTML = `
            <div class="text-center py-5">
               <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc3545;"></i>
               <p class="text-muted mt-3">Có lỗi xảy ra khi tải giỏ hàng!</p>
               <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadCart()">
                  <i class="fas fa-redo mr-1"></i>Thử lại
               </button>
            </div>
         `;
      });
   }

   // Render giỏ hàng
   function renderCart(data) {
      const cartBody = document.querySelector('.cart-sidebar-body');

      if (data.items.length === 0) {
         cartBody.innerHTML = `
            <div class="text-center py-5">
               <i class="fas fa-shopping-bag" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
               <h5 style="color: #666; margin-bottom: 10px;">Giỏ hàng trống</h5>
               <p class="text-muted" style="font-size: 14px;">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm!</p>
               <button class="btn btn-primary mt-3" onclick="closeCart(); window.location.href='{{ route('posts.index') }}'">
                  <i class="mdi mdi-magnify mr-2"></i>Khám phá sản phẩm
               </button>
            </div>
         `;
         document.querySelector('.cart-summary-value').textContent = '0 VNĐ';
         return;
      }

      let html = '';
      data.items.forEach(item => {
         const imageUrl = item.post.images && item.post.images.length > 0
            ? '{{ asset('storage') }}/' + item.post.images[0]
            : '{{ asset('img/list/1.png') }}';

         const canAdjustQty = !item.is_fixed_quantity;
         const totalPrice = item.post.price * item.quantity;
         const maxQty = item.post.quantity;

         const qtyControls = canAdjustQty ? `
            <button class="qty-btn" onclick="decreaseQty(${item.id})">
               <i class="fas fa-minus"></i>
            </button>
            <input type="number" class="qty-input" value="${item.quantity}" min="1" max="${maxQty}"
               data-item-id="${item.id}"
               data-max-qty="${maxQty}"
               onblur="handleQtyBlur(this, ${item.id})"
               onkeypress="handleQtyKeypress(event, this, ${item.id})">
            <button class="qty-btn" onclick="increaseQty(${item.id})">
               <i class="fas fa-plus"></i>
            </button>
         ` : `
            <span style="font-weight: 600; color: #666;">Số lượng: ${item.quantity} kg</span>
         `;

         html += `
            <div class="cart-item" data-cart-id="${item.id}">
               <div class="cart-item-image">
                  <img src="${imageUrl}" alt="${item.post.title}">
               </div>
               <div class="cart-item-details">
                  <h5 class="cart-item-title">${item.post.title}</h5>
                  <p class="cart-item-meta">${item.post.waste_type.name}</p>
                  <div class="cart-item-price-container">
                     <span class="cart-item-price">${formatNumber(totalPrice)} VNĐ</span>
                     <small class="text-muted">(${formatNumber(item.post.price)} VNĐ/kg)</small>
                  </div>
                  <div class="cart-item-actions">
                     ${qtyControls}
                     <button class="delete-btn" onclick="removeFromCart(${item.id})">
                        <i class="far fa-trash-alt"></i>
                     </button>
                  </div>
               </div>
            </div>
         `;
      });

      cartBody.innerHTML = html;
      document.querySelector('.cart-summary-value').textContent = formatNumber(data.total) + ' VNĐ';
   }

   // Tăng số lượng
   function increaseQty(itemId) {
      const input = event.target.closest('.cart-item-actions').querySelector('.qty-input');
      const currentQty = parseInt(input.value || 1);
      const maxQty = parseInt(input.dataset.maxQty || 999999);

      if (currentQty >= maxQty) {
         showToast(`Đã đạt số lượng tối đa (${maxQty} kg)!`, 'error');
         return;
      }

      const newQty = currentQty + 1;
      input.value = newQty;
      updateQuantityOnServer(itemId, newQty);
   }

   // Giảm số lượng
   function decreaseQty(itemId) {
      const input = event.target.closest('.cart-item-actions').querySelector('.qty-input');
      const currentValue = parseInt(input.value || 1);
      if (currentValue > 1) {
         const newQty = currentValue - 1;
         input.value = newQty;
         updateQuantityOnServer(itemId, newQty);
      }
   }

   // Lưu giá trị cũ khi focus vào input
   document.addEventListener('focusin', function(e) {
      if (e.target.classList.contains('qty-input')) {
         e.target.dataset.oldValue = e.target.value;
      }
   });

   // Xử lý khi blur
   function handleQtyBlur(inputElement, itemId) {
      let value = parseInt(inputElement.value);
      const oldValue = parseInt(inputElement.dataset.oldValue || 1);
      const maxQty = parseInt(inputElement.dataset.maxQty || 999999);

      if (isNaN(value) || value < 1 || inputElement.value === '') {
         value = oldValue;
         inputElement.value = oldValue;
      }

      if (value > maxQty) {
         showToast(`Số lượng không được vượt quá ${maxQty} kg!`, 'error');
         value = maxQty;
         inputElement.value = maxQty;
      }

      if (value !== oldValue) {
         updateQuantityOnServer(itemId, value);
      }
   }

   // Xử lý khi nhấn Enter
   function handleQtyKeypress(event, inputElement, itemId) {
      if (event.key === 'Enter') {
         event.preventDefault();
         inputElement.blur();
      }
   }

   // Chặn input không hợp lệ
   document.addEventListener('input', function(e) {
      if (e.target.classList.contains('qty-input')) {
         e.target.value = e.target.value.replace(/[^0-9]/g, '');
      }
   });

   // Cập nhật số lượng lên server
   function updateQuantityOnServer(itemId, quantity) {
      const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
      if (input) {
         input.disabled = true;
         input.style.opacity = '0.5';
      }

      fetch(`/api/cart/${itemId}/update`, {
         method: 'PUT',
         headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
         },
         body: JSON.stringify({ quantity: quantity })
      })
      .then(response => response.json())
      .then(data => {
         if (input) {
            input.disabled = false;
            input.style.opacity = '1';
         }

         if (data.success) {
            document.querySelector('.cart-summary-value').textContent = formatNumber(data.total) + ' VNĐ';

            const cartItem = document.querySelector(`.cart-item[data-cart-id="${itemId}"]`);
            if (cartItem && data.subtotal) {
               const priceElement = cartItem.querySelector('.cart-item-price');
               if (priceElement) {
                  priceElement.textContent = formatNumber(data.subtotal) + ' VNĐ';
               }
            }
         } else {
            showToast(data.message || 'Không thể cập nhật số lượng!', 'error');
            loadCart();
         }
      })
      .catch(error => {
         if (input) {
            input.disabled = false;
            input.style.opacity = '1';
         }

         console.error('Error updating quantity:', error);
         showToast('Có lỗi xảy ra khi cập nhật số lượng!', 'error');
         loadCart();
      });
   }

   // Xóa sản phẩm khỏi giỏ hàng
   function removeFromCart(itemId) {
      if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
         const cartItem = document.querySelector(`.cart-item[data-cart-id="${itemId}"]`);

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
               if (cartItem) {
                  cartItem.style.transition = 'opacity 0.3s ease';
                  cartItem.style.opacity = '0';
                  setTimeout(() => {
                     cartItem.remove();
                     loadCart();
                  }, 300);
               } else {
                  loadCart();
               }
               updateCartBadge(data.cartCount);
            } else {
               showToast(data.message || 'Có lỗi xảy ra khi xóa sản phẩm!', 'error');
            }
         })
         .catch(error => {
            console.error('Error removing item:', error);
            showToast('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
         });
      }
   }

   // Thêm vào giỏ hàng
   window.addToCart = function(postId) {
      fetch('{{ route('cart.add') }}', {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
         },
         body: JSON.stringify({ post_id: postId, quantity: 1 })
      })
      .then(response => {
         // Kiểm tra nếu response là 401 (Unauthorized) hoặc 403 (Forbidden)
         if (response.status === 401 || response.status === 403) {
            // Hiển thị toast trước khi chuyển hướng
            showToast('Bạn cần đăng nhập để có thể thêm bài đăng vào giỏ hàng', 'error');
            // Chuyển hướng đến trang đăng nhập sau 1.5 giây
            setTimeout(() => {
               window.location.href = '{{ route('login') }}';
            }, 1500);
            return null;
         }
         return response.json();
      })
      .then(data => {
         if (!data) return; // Nếu đã redirect thì dừng lại

         if (data.success) {
            showToast(data.message, 'success');
            updateCartBadge(data.cartCount);

            setTimeout(() => {
               openCart();
            }, 500);
         } else {
            // Nếu message báo chưa đăng nhập thì redirect
            if (data.message && (data.message.includes('đăng nhập') || data.message.includes('login'))) {
               showToast('Bạn cần đăng nhập để có thể thêm bài đăng vào giỏ hàng', 'error');
               setTimeout(() => {
                  window.location.href = '{{ route('login') }}';
               }, 1500);
            } else {
               showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
         }
      })
      .catch(error => {
         console.error('Error adding to cart:', error);
         showToast('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
      });
   }

   // Toast notification
   function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `cart-toast cart-toast-${type}`;
      toast.innerHTML = `
         <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
         <span>${message}</span>
      `;

      document.body.appendChild(toast);

      setTimeout(() => {
         toast.classList.add('show');
      }, 100);

      setTimeout(() => {
         toast.classList.remove('show');
         setTimeout(() => {
            toast.remove();
         }, 300);
      }, 3000);
   }

   // Checkout
   function checkout() {
      window.location.href = '{{ route('cart.checkout') }}';
   }

   // Xem giỏ hàng đầy đủ
   function viewCart() {
      window.location.href = '{{ route('cart.index') }}';
   }

   // Update cart badge
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

   // Format số tiền
   function formatNumber(num) {
      return new Intl.NumberFormat('vi-VN').format(num);
   }

   // Close cart when pressing ESC
   document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
         closeCart();
      }
   });

   // Load cart count khi trang load
   document.addEventListener('DOMContentLoaded', function() {
      @auth
      fetch('{{ route('cart.get') }}')
         .then(response => response.json())
         .then(data => {
            if (data.success) {
               updateCartBadge(data.count);
            }
         })
         .catch(error => {
            console.error('Error loading cart count:', error);
            updateCartBadge(0);
         });
      @endauth
   });
</script>
