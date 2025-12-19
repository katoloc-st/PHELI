@extends('layouts.app')

@section('title', 'Thanh toán MoMo - Hệ thống quản lý phế liệu')

@section('content')
<section class="payment-section py-5">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
               <div class="card-body text-center p-5">
                  <div class="mb-4">
                     <img src="https://developers.momo.vn/v3/img/logo.svg" alt="MoMo" style="height: 60px;">
                  </div>

                  <h4 class="mb-3">Quét mã QR để thanh toán</h4>

                  <div class="alert alert-info mb-4">
                     <strong>Đơn hàng:</strong> {{ $order->order_number }}<br>
                     <strong>Số tiền:</strong> <span class="text-danger font-weight-bold">{{ number_format($order->grand_total, 0, ',', '.') }}₫</span>
                  </div>

                  @if(isset($qrData['pay_url']) && $qrData['pay_url'])
                     <!-- Redirect to MoMo Payment -->
                     <div class="text-center mb-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                           <span class="sr-only">Đang chuyển hướng...</span>
                        </div>
                        <p>Đang chuyển đến trang thanh toán MoMo...</p>
                        <p class="text-muted small">Nếu không tự động chuyển, <a href="{{ $qrData['pay_url'] }}" class="btn btn-primary btn-sm">nhấn vào đây</a></p>
                     </div>

                     <script>
                        // Auto redirect to MoMo payment page
                        setTimeout(function() {
                           window.location.href = '{{ $qrData['pay_url'] }}';
                        }, 1000);
                     </script>
                  @else
                     <div class="alert alert-danger">
                        <strong>Lỗi:</strong> Không thể tạo thanh toán MoMo. Vui lòng thử lại.
                     </div>
                     <div class="mt-4">
                        <a href="{{ route('cart.checkout') }}" class="btn btn-outline-secondary">
                           <i class="mdi mdi-arrow-left"></i> Quay lại thanh toán
                        </a>
                     </div>
                  @endif

                  <p class="text-muted mb-4">
                     Đang chuyển hướng đến trang thanh toán MoMo...
                  </p>

                  <!-- Payment Status hidden -->
                  <div id="paymentStatus" class="mb-4" style="display: none;">
                     <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Đang chờ thanh toán...</span>
                     </div>
                     <p class="mt-2">Đang chờ xác nhận thanh toán...</p>
                  </div>

                  <div class="mt-4" style="display: none;">
                     <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Quay lại đơn hàng
                     </a>
                  </div>
               </div>
            </div>

            <!-- Payment Instructions removed -->
         </div>
      </div>
   </div>
</section>

<style>
.payment-section {
   background: #f8f9fa;
   min-height: 100vh;
}

.card {
   border: none;
   border-radius: 12px;
}

.qr-code-container {
   background: #fff;
   padding: 20px;
   border-radius: 12px;
   display: inline-block;
}

.spinner-border {
   width: 3rem;
   height: 3rem;
}

#paymentStatus.success {
   color: #28a745;
}

#paymentStatus.error {
   color: #dc3545;
}
</style>

@push('scripts')
<script>
// No polling or manual confirmation needed anymore
// Page will auto-redirect to MoMo
</script>
@endpush
@endsection
