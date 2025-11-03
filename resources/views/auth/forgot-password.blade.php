@extends('layouts.app')

@section('title', 'Quên mật khẩu - LUCAS')

@section('content')
      <!-- Login -->
      <section class="hv-100">
         <div class="container">
            <div class="row align-items-center hv-100">
               <div class="col-lg-4 col-md-4 mx-auto">
                  <div class="card padding-card mb-0">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Quên mật khẩu</h5>
                        <p class="text-muted mb-4">Nhập địa chỉ email của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu.</p>
                        
                        @if (session('status'))
                           <div class="alert alert-success">
                              {{ session('status') }}
                           </div>
                        @endif
                        
                        @if ($errors->any())
                           <div class="alert alert-danger">
                              <ul class="mb-0">
                                 @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                           </div>
                        @endif
                        
                        <form method="POST" action="{{ route('password.email') }}">
                           @csrf
                           <div class="form-group">
                              <label>Địa chỉ email <span class="text-danger">*</span></label>
                              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                     placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required>
                              @error('email')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <button type="submit" class="btn btn-success btn-block">
                              <i class="mdi mdi-email-send"></i> GỬI LIÊN KẾT ĐẶT LẠI
                           </button>
                        </form>
                        <div class="mt-4 text-center">
                           <a href="{{ route('login') }}" class="text-muted">
                              <i class="mdi mdi-arrow-left"></i> Quay lại đăng nhập
                           </a>
                        </div>
                        <div class="mt-3 text-center">
                           <a href="{{ route('register') }}">Chưa có tài khoản? Đăng ký ngay</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Reset Password -->
@endsection

@section('custom-css')
<style>
.hv-100 {
    min-height: 100vh;
}

.padding-card {
    padding: 1rem;
}

.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.card-body {
    padding: 2rem;
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn-success:hover {
    background: linear-gradient(45deg, #218838, #1ea871);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}
</style>
@endsection

@section('custom-js')
<script>
// Auto focus on email field
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.focus();
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const email = this.querySelector('input[name="email"]').value;
    
    if (!email) {
        e.preventDefault();
        alert('Vui lòng nhập địa chỉ email');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Đang gửi...';
    submitBtn.disabled = true;
});
</script>
@endsection