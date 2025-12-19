@extends('layouts.app')

@section('title', 'Đăng ký - LUCAS')

@section('content')
      <!-- Register -->
      <section class="hv-100">
         <div class="container">
            <div class="row align-items-center hv-100">
               <div class="col-lg-5 col-md-5 mx-auto">
                  <div class="card padding-card mb-0">
                     <div class="card-body">
                        <h5 class="card-title mb-4">Đăng ký tài khoản</h5>

                        @if ($errors->any())
                           <div class="alert alert-danger">
                              <ul class="mb-0">
                                 @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                           </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                           @csrf
                           <div class="form-group">
                              <label>Họ và tên <span class="text-danger">*</span></label>
                              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                     placeholder="Nhập họ và tên" value="{{ old('name') }}" required>
                              @error('name')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label>Địa chỉ email <span class="text-danger">*</span></label>
                              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                     placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required>
                              @error('email')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label>Số điện thoại <span class="text-danger">*</span></label>
                              <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                     placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required>
                              @error('phone')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label>Loại tài khoản <span class="text-danger">*</span></label>
                              <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                 <option value="">Chọn loại tài khoản</option>
                                 <option value="waste_company" {{ old('role') == 'waste_company' ? 'selected' : '' }}>Doanh nghiệp xanh</option>
                                 <option value="scrap_dealer" {{ old('role') == 'scrap_dealer' ? 'selected' : '' }}>Cửa hàng thu mua phế liệu</option>
                                 <option value="recycling_plant" {{ old('role') == 'recycling_plant' ? 'selected' : '' }}>Nhà máy tái chế</option>
                              </select>
                              @error('role')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label>Mật khẩu <span class="text-danger">*</span></label>
                              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                     placeholder="Nhập mật khẩu (tối thiểu 8 ký tự)" required>
                              @error('password')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label>Xác nhận mật khẩu <span class="text-danger">*</span></label>
                              <input type="password" name="password_confirmation" class="form-control"
                                     placeholder="Nhập lại mật khẩu" required>
                           </div>
                           <div class="form-group">
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" name="terms" class="custom-control-input" id="terms" required>
                                 <label class="custom-control-label" for="terms">
                                    Tôi đồng ý với <a href="#" target="_blank">Điều khoản sử dụng</a> và <a href="#" target="_blank">Chính sách bảo mật</a>
                                 </label>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-success btn-block">
                              <i class="mdi mdi-account-plus"></i> ĐĂNG KÝ
                           </button>
                        </form>
                        <div class="mt-4 text-center">
                           Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
                        </div>
                        <div class="mt-3 text-center">
                           <a href="{{ route('index') }}" class="btn btn-outline-primary btn-sm">
                              <i class="mdi mdi-home"></i> Về trang chủ
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Register -->
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

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}

select.form-control {
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='m2 0-2 2h4zm0 5 2-2h-4z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}
</style>
@endsection

@section('custom-js')
<script>
// Auto-focus on name field
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        nameInput.focus();
    }
});

// Password confirmation validation
document.querySelector('input[name="password_confirmation"]').addEventListener('blur', function() {
    const password = document.querySelector('input[name="password"]').value;
    const confirmation = this.value;

    if (password && confirmation && password !== confirmation) {
        this.classList.add('is-invalid');
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'Mật khẩu xác nhận không khớp';
            this.parentNode.appendChild(feedback);
        }
    } else {
        this.classList.remove('is-invalid');
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const role = document.querySelector('select[name="role"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
    const terms = document.querySelector('input[name="terms"]').checked;

    if (!name || !email || !phone || !role || !password || !passwordConfirm || !terms) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin và đồng ý với điều khoản');
        return false;
    }

    if (password !== passwordConfirm) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp');
        return false;
    }

    if (password.length < 8) {
        e.preventDefault();
        alert('Mật khẩu phải có ít nhất 8 ký tự');
        return false;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Đang đăng ký...';
    submitBtn.disabled = true;
});
</script>
@endsection
