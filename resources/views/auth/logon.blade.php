@extends('layouts.app')

@section('title', 'Đăng nhập / Đăng ký - LUCAS')

@section('custom-css')
<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto Mono");

/* Scoped CSS chỉ cho trang logon */
.logon-page-wrapper {
  display: flex;
  background-color: #f6f5f7;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  font-family: "Roboto Mono", sans-serif;
  overflow: hidden;
  min-height: 100vh;
  padding: 20px 0;
}

.logon-page-wrapper * {
  box-sizing: border-box;
}

.logon-page-wrapper h1 {
  font-weight: 700;
  letter-spacing: -1.5px;
  margin: 0;
  margin-bottom: 15px;
}

.logon-page-wrapper h1.title {
  font-size: 45px;
  line-height: 45px;
  margin: 0;
  text-shadow: 0 0 10px rgba(16, 64, 74, 0.5);
}

.logon-page-wrapper p {
  font-size: 14px;
  font-weight: 100;
  line-height: 20px;
  letter-spacing: 0.5px;
  margin: 20px 0 30px;
  text-shadow: 0 0 10px rgba(16, 64, 74, 0.5);
}

.logon-page-wrapper span {
  font-size: 14px;
  margin-top: 25px;
}

.logon-page-wrapper a {
  color: #333;
  font-size: 14px;
  text-decoration: none;
  margin: 15px 0;
  transition: 0.3s ease-in-out;
}

.logon-page-wrapper a:hover {
  color: #4bb6b7;
}

.logon-page-wrapper .content {
  display: flex;
  width: 100%;
  min-height: 40px;
  align-items: center;
  justify-content: center;
  margin: 10px 0;
}

.logon-page-wrapper .content .checkbox {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 100%;
}

.logon-page-wrapper .content input[type="checkbox"] {
  accent-color: #4bb6b7;
  width: 15px;
  height: 15px;
  margin-right: 8px;
}

.logon-page-wrapper .content label {
  font-size: 12px;
  user-select: none;
  color: #333;
  display: flex;
  align-items: center;
}

.logon-page-wrapper button {
  position: relative;
  border-radius: 20px;
  border: 1px solid #4bb6b7;
  background-color: #4bb6b7;
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  margin: 10px;
  padding: 12px 80px;
  letter-spacing: 1px;
  text-transform: capitalize;
  transition: 0.3s ease-in-out;
  white-space: nowrap;
  cursor: pointer;
}

.logon-page-wrapper button:hover {
  letter-spacing: 3px;
}

.logon-page-wrapper button:active {
  transform: scale(0.95);
}

.logon-page-wrapper button:focus {
  outline: none;
}

.logon-page-wrapper button.ghost {
  background-color: rgba(225, 225, 225, 0.2);
  border: 2px solid #fff;
  color: #fff;
}

.logon-page-wrapper button.ghost.i {
  position: absolute;
  opacity: 0;
  transition: 0.3s ease-in-out;
}

.logon-page-wrapper button.ghost.i.register {
  right: 70px;
}

.logon-page-wrapper button.ghost.i.login {
  left: 70px;
}

.logon-page-wrapper button.ghost:hover i.register {
  right: 40px;
  opacity: 1;
}

.logon-page-wrapper button.ghost:hover i.login {
  left: 40px;
  opacity: 1;
}

.logon-page-wrapper form {
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  flex-direction: column;
  padding: 20px 50px;
  height: 100%;
  text-align: center;
  overflow-y: auto;
  overflow-x: hidden;
}

/* Styling cho scrollbar */
.logon-page-wrapper form::-webkit-scrollbar {
  width: 6px;
}

.logon-page-wrapper form::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.logon-page-wrapper form::-webkit-scrollbar-thumb {
  background: #4bb6b7;
  border-radius: 10px;
}

.logon-page-wrapper form::-webkit-scrollbar-thumb:hover {
  background: #3a9899;
}

/* Đảm bảo form register có thê scroll */
.logon-page-wrapper .register-container form {
  justify-content: flex-start;
  padding-top: 30px;
}

.logon-page-wrapper input {
  background-color: #eee;
  border-radius: 10px;
  border: none;
  padding: 12px 15px;
  margin: 6px 0;
  width: 100%;
  outline: none;
  font-size: 14px;
}

.logon-page-wrapper input:focus {
  background-color: #fff;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.logon-page-wrapper select {
  background-color: #eee;
  border: none;
  margin: 6px 0;
  padding: 12px 15px;
  font-size: 14px;
  border-radius: 10px;
  width: 100%;
  outline: none;
}

.logon-page-wrapper select:focus {
  background-color: #fff;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.logon-page-wrapper select option {
  padding: 10px;
  font-size: 13px;
}

.logon-page-wrapper select:required:invalid {
  color: #757575;
}

.logon-page-wrapper .container {
  background-color: #fff;
  border-radius: 25px;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  position: relative;
  overflow: hidden;
  width: 768px;
  max-width: 100%;
  min-height: 500px;
}

.logon-page-wrapper .form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
  overflow: hidden;
}

.logon-page-wrapper .login-container {
  left: 0;
  width: 50%;
  z-index: 2;
}

.logon-page-wrapper .container.right-panel-active .login-container {
  transform: translateX(100%);
}

.logon-page-wrapper .register-container {
  left: 0;
  width: 50%;
  opacity: 0;
  z-index: 1;
}

.logon-page-wrapper .container.right-panel-active .register-container {
  transform: translateX(100%);
  opacity: 1;
  z-index: 5;
  animation: show 0.6s;
}

@keyframes show {
  0%,
  49.99% {
    opacity: 0;
    z-index: 1;
  }

  50%,
  100% {
    opacity: 1;
    z-index: 5;
  }
}

.logon-page-wrapper .overlay-container {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 100%;
  overflow: hidden;
  transition: transform 0.6s ease-in-out;
  z-index: 100;
}

.logon-page-wrapper .container.right-panel-active .overlay-container {
  transform: translate(-100%);
}

.logon-page-wrapper .overlay {
  background-image: url('{{ asset("css/266f392d2b02ea526eb557b6018e6ee9.gif") }}');
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 0 0;
  color: #fff;
  position: relative;
  left: -100%;
  height: 100%;
  width: 200%;
  transform: translateX(0);
  transition: transform 0.6s ease-in-out;
}

.logon-page-wrapper .overlay::before {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: linear-gradient(
    to top,
    rgba(46, 94, 109, 0.4) 40%,
    rgba(46, 94, 109, 0)
  );
}

.logon-page-wrapper .container.right-panel-active .overlay {
  transform: translateX(50%);
}

.logon-page-wrapper .overlay-panel {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 40px;
  text-align: center;
  top: 0;
  height: 100%;
  width: 50%;
  transform: translateX(0);
  transition: transform 0.6s ease-in-out;
}

.logon-page-wrapper .overlay-left {
  transform: translateX(-20%);
}

.logon-page-wrapper .container.right-panel-active .overlay-left {
  transform: translateX(0);
}

.logon-page-wrapper .overlay-right {
  right: 0;
  transform: translateX(0);
}

.logon-page-wrapper .container.right-panel-active .overlay-right {
  transform: translateX(20%);
}

.logon-page-wrapper .social-container {
  margin: 20px 0;
}

.logon-page-wrapper .social-container a {
  border: 1px solid #dddddd;
  border-radius: 50%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin: 0 5px;
  height: 40px;
  width: 40px;
  transition: 0.3s ease-in-out;
}

.logon-page-wrapper .social-container a:hover {
  border: 1px solid #4bb6b7;
}

.logon-page-wrapper .alert {
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 4px;
  width: 100%;
}

.logon-page-wrapper .alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
}

.logon-page-wrapper .alert-success {
  background-color: #d4edda;
  border-color: #c3e6cb;
  color: #155724;
}
</style>
@endsection

@section('content')
<div class="logon-page-wrapper">
<section>
  <div class="container" id="container">
    <div class="form-container register-container">
      <form action="{{ route('register') }}" method="post">
        @csrf
        <h1>Tạo tài khoản</h1>
        <div class="social-container">
          <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social"><i class="fab fa-google"></i></a>
          <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" class="social"><i class="fab fa-github"></i></a>
        </div>
        <span>hoặc sử dụng email để đăng ký</span>

        <input type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}" required/>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required/>
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="tel" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}" required/>
        @error('phone')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <select name="role" required>
          <option value="">Chọn vai trò của bạn</option>
          <option value="waste_company" {{ old('role') == 'waste_company' ? 'selected' : '' }}>Doanh nghiệp xanh</option>
          <option value="scrap_dealer" {{ old('role') == 'scrap_dealer' ? 'selected' : '' }}>Phế liệu</option>
          <option value="recycling_plant" {{ old('role') == 'recycling_plant' ? 'selected' : '' }}>Nhà máy tái chế</option>
        </select>
        @error('role')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="password" name="password" placeholder="Mật khẩu" required/>
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required/>

        <div class="content">
          <div class="checkbox">
            <input type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            <label for="terms">Tôi đồng ý với điều khoản và chính sách</label>
          </div>
        </div>
        @error('terms')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit">Đăng ký</button>
      </form>
    </div>

    <div class="form-container login-container">
      <form action="{{ route('login') }}" method="post">
        @csrf
        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}" />
        @endif

        <h1>Đăng nhập</h1>

        @if(session('registerError'))
            <div class="alert alert-danger">
                <span>{{ session('errorMessage', 'Email đã được đăng ký cho vai trò này!') }}</span>
            </div>
        @endif

        @if(session('logout_success'))
            <div class="alert alert-success">
                Đăng xuất thành công! Hẹn gặp lại bạn.
            </div>
        @endif

        @if(request('register'))
            <div class="alert alert-success">
                Đăng ký tài khoản thành công!
            </div>
        @endif

        @if($errors->has('email') || $errors->has('password'))
            <div class="alert alert-danger">
                Email hoặc mật khẩu không đúng!
            </div>
        @endif

        @if(request('logout'))
            <div class="alert alert-success">
                Đăng xuất thành công!
            </div>
        @endif

        <div class="social-container">
          <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social"><i class="fab fa-google"></i></a>
          <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" class="social"><i class="fab fa-github"></i></a>
        </div>
        <span>hoặc sử dụng tài khoản của bạn</span>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" />
        <input type="password" name="password" placeholder="Password" />
        <a href="#">Quên mật khẩu?</a>
        <button type="submit">Đăng nhập</button>
      </form>
    </div>

    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Chào mừng trở lại!</h1>
          <p style="color: #ffffff">Đăng nhập để kết nối với tài khoản của bạn</p>
          <button class="ghost" id="signIn">Đăng nhập</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Chào bạn mới!</h1>
          <p style="color: #ffffff">Nhập thông tin của bạn để tạo tài khoản</p>
          <button class="ghost" id="signUp">Đăng ký</button>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/scriptlogon.js') }}"></script>
</section>
</div>
@endsection

@section('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const signUpButton = document.getElementById('signUp');
  const signInButton = document.getElementById('signIn');
  const container = document.getElementById('container');

  if (signUpButton) {
    signUpButton.addEventListener('click', () => {
      container.classList.add("right-panel-active");
    });
  }

  if (signInButton) {
    signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
  }

  // Show register form if there are registration errors
  @if($errors->any() && request()->get('mode') === 'register')
    container.classList.add("right-panel-active");
  @endif

  // Password confirmation validation
  const passwordConfirm = document.querySelector('input[name="password_confirmation"]');
  if (passwordConfirm) {
    passwordConfirm.addEventListener('blur', function() {
      const password = document.querySelector('input[name="password"]').value;
      const confirmation = this.value;

      if (password && confirmation && password !== confirmation) {
        this.style.backgroundColor = '#f8d7da';
      } else {
        this.style.backgroundColor = '#eee';
      }
    });
  }

  // Form validation
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        submitBtn.disabled = true;
      }
    });
  });
});
</script>
@endsection
