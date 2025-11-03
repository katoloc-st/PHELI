@extends('layouts.app')

@section('title', 'Trang chủ - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Inner Header -->
      <section class="bg-dark py-5 user-header">
         <div class="container">
            <div class="row align-items-center mt-2 mb-5 pb-4">
               <div class="col">
                  <!-- Heading -->
                  <h1 class="text-white mb-2" style="font-family: 'Barlow', sans-serif;">
                     Thông tin công ty
                  </h1>
                  <!-- Text -->
                  <h6 class="font-weight-normal text-white-50 mb-0">
                     Quản lý thông tin công ty của bạn để mọi người có thể biết đến bạn hơn
                  </h6>
               </div>
               <div class="col-auto">
                  <!-- Button -->
                  <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Làm mới
                </button>
               </div>
            </div>
            <!-- / .row -->
         </div>
         <!-- / .container -->
      </section>
      <!-- End Inner Header -->
      <!-- User Profile -->
      <section class="section-padding pt-0 user-pages-main">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-3">
                  <!-- Collapse -->
                  @include('layouts.sidebar')
               </div>
               <div class="col-lg-9 col-md-9">
                  <form class="card padding-card" style="max-width:700px;margin:auto;" method="POST" action="{{ route('company-profile.update') }}" enctype="multipart/form-data">
                     @csrf
                     @method('PUT')
                     <div class="card-body">
                        <h4 class="mb-4">Thông tin công ty</h4>

                        @if(session('success'))
                           <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                        @endif

                        @if($errors->any())
                           <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <ul class="mb-0">
                                 @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                        @endif

                        <!-- Company Logo Upload Section -->
                        <div class="form-group text-center mb-4">
                           <label class="form-label">Logo công ty</label>
                           <div class="logo-upload-container mb-3">
                              <div class="logo-preview d-flex justify-content-center">
                                 @if($user->company_logo)
                                    <img src="{{ asset('storage/' . $user->company_logo) }}" alt="Company Logo" class="rounded" id="logo-preview" style="width: 150px; height: 100px; object-fit: contain; border: 3px solid #ddd; background: white;">
                                 @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto" id="logo-preview" style="width: 150px; height: 100px; border: 3px solid #ddd;">
                                       <i class="fas fa-building text-muted" style="font-size: 36px;"></i>
                                    </div>
                                 @endif
                              </div>
                              <div class="logo-upload mt-3">
                                 <input type="file" class="form-control-file @error('company_logo') is-invalid @enderror"
                                        id="company_logo" name="company_logo" accept="image/*" onchange="previewLogo(this)">
                                 @if($user->company_logo)
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeLogo()">
                                       <i class="fas fa-trash mr-1"></i>Xóa logo
                                    </button>
                                    <input type="hidden" id="remove-logo" name="remove_logo" value="0">
                                    <input type="hidden" id="original-logo-url" value="{{ asset('storage/' . $user->company_logo) }}">
                                 @endif
                                 <small class="form-text text-muted">Chọn logo định dạng JPG, PNG. Kích thước tối đa 2MB.</small>
                                 @error('company_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="company_name">Tên công ty <span class="text-danger">*</span></label>
                                 <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name"
                                        value="{{ old('company_name', $user->company_name) }}"
                                        placeholder="Nhập tên công ty" required>
                                 @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="role">Loại hình công ty</label>
                                 @php
                                    $roleDisplay = '';
                                    switch($user->role) {
                                       case 'scrap_dealer':
                                          $roleDisplay = 'Cơ sở phế liệu';
                                          break;
                                       case 'waste_company':
                                          $roleDisplay = 'Doanh nghiệp xanh';
                                          break;
                                       case 'admin':
                                          $roleDisplay = 'Quản trị viên';
                                          break;
                                       default:
                                          $roleDisplay = $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : 'Chưa xác định';
                                    }
                                 @endphp
                                 <input type="text" class="form-control bg-light"
                                        id="role" name="role"
                                        value="{{ $roleDisplay }}"
                                        readonly>
                                 <small class="form-text text-muted">Liên hệ Admin nếu bạn muốn thay đổi loại hình công ty</small>
                              </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label for="company_phone">Số điện thoại công ty</label>
                           <input type="text" class="form-control @error('company_phone') is-invalid @enderror"
                                  id="company_phone" name="company_phone"
                                  value="{{ old('company_phone', $user->company_phone) }}"
                                  placeholder="Nhập số điện thoại công ty">
                           @error('company_phone')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="company_address">Địa chỉ công ty</label>
                           <input type="text" class="form-control @error('company_address') is-invalid @enderror"
                                  id="company_address" name="company_address"
                                  value="{{ old('company_address', $user->company_address) }}"
                                  placeholder="Nhập địa chỉ công ty">
                           @error('company_address')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="company_email">Email công ty</label>
                           <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                  id="company_email" name="company_email"
                                  value="{{ old('company_email', $user->company_email) }}"
                                  placeholder="Nhập email công ty">
                           @error('company_email')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="business_license">Giấy phép kinh doanh</label>
                                 <input type="text" class="form-control @error('business_license') is-invalid @enderror"
                                        id="business_license" name="business_license"
                                        value="{{ old('business_license', $user->business_license) }}"
                                        placeholder="Số giấy phép kinh doanh">
                                 @error('business_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="tax_code">Mã số thuế</label>
                                 <input type="text" class="form-control @error('tax_code') is-invalid @enderror"
                                        id="tax_code" name="tax_code"
                                        value="{{ old('tax_code', $user->tax_code) }}"
                                        placeholder="Nhập mã số thuế">
                                 @error('tax_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                              </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label for="company_description">Mô tả công ty</label>
                           <textarea class="form-control @error('company_description') is-invalid @enderror"
                                     id="company_description" name="company_description" rows="4"
                                     placeholder="Mô tả về hoạt động kinh doanh của công ty">{{ old('company_description', $user->company_description) }}</textarea>
                           @error('company_description')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                           <i class="fas fa-save mr-2"></i>Cập nhật thông tin
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </section>
      <!-- End User Profile -->
      <!-- Join Team -->
      <section class="py-5 bg-primary">
         <div class="container">
            <div class="row align-items-center text-center text-md-left">
               <div class="col-md-8">
                  <h2 class="text-white my-2">Join Our Professional Team & Agents</h2>
               </div>
               <div class="col-md-4 text-center text-md-right">
                  <button type="button" class="btn btn-outline-light my-2">Read More</button> <button type="button"
                     class="btn btn-light my-2">Contact Us</button>
               </div>
            </div>
         </div>
      </section>
      <!-- End Join Team -->
@endsection

@section('custom-css')
<style>
.logo-upload-container {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-preview {
    margin-bottom: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo-preview img,
.logo-preview div {
    border: 3px solid #ddd;
    transition: border-color 0.3s;
}

.logo-preview img:hover,
.logo-preview div:hover {
    border-color: #007bff;
}

.logo-upload input[type="file"] {
    max-width: 300px;
    margin: 0 auto;
}

.card.padding-card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
}

/* Ensure logo placeholder is perfectly centered */
#logo-preview {
    margin: 0 auto;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Remove logo button styling */
.logo-upload .btn-danger,
.logo-upload .btn-warning {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 4px;
}

.logo-upload .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.logo-upload .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

/* Company form styling */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@endsection

@section('custom-js')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var previewContainer = document.getElementById('logo-preview');

            // Replace the content with an img element
            previewContainer.outerHTML = '<img src="' + e.target.result + '" alt="Logo" class="rounded" id="logo-preview" style="width: 150px; height: 100px; object-fit: contain; border: 3px solid #ddd; background: white;">';

            // Reset remove logo flag when new file is selected
            var removeLogoInput = document.getElementById('remove-logo');
            if (removeLogoInput) {
                removeLogoInput.value = '0';
            }

            // Show remove button if it doesn't exist or update existing button
            var logoUpload = document.querySelector('.logo-upload');
            var removeBtn = document.querySelector('button[onclick="removeLogo()"], button[onclick="restoreLogo()"]');

            if (!removeBtn) {
                var removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger btn-sm mt-2';
                removeButton.onclick = removeLogo;
                removeButton.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa logo';

                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'remove-logo';
                hiddenInput.name = 'remove_logo';
                hiddenInput.value = '0';

                // Create original logo URL input if it doesn't exist
                var originalUrlInput = document.getElementById('original-logo-url');
                if (!originalUrlInput) {
                    originalUrlInput = document.createElement('input');
                    originalUrlInput.type = 'hidden';
                    originalUrlInput.id = 'original-logo-url';
                    originalUrlInput.value = ''; // Will be empty for new uploads, only preserve DB logo
                }

                var fileInput = document.getElementById('company_logo');
                logoUpload.insertBefore(removeButton, fileInput.nextSibling);
                logoUpload.insertBefore(hiddenInput, removeButton.nextSibling);
                if (!document.getElementById('original-logo-url')) {
                    logoUpload.insertBefore(originalUrlInput, hiddenInput.nextSibling);
                }
            } else {
                // Update existing button to normal remove state
                removeBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa logo';
                removeBtn.setAttribute('onclick', 'removeLogo()');
                removeBtn.className = 'btn btn-danger btn-sm mt-2';

                // DO NOT update original logo URL - keep the original DB logo URL
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    // Check if there's an original logo from DB
    var originalUrlInput = document.getElementById('original-logo-url');
    var hasOriginalLogo = originalUrlInput && originalUrlInput.value.trim() !== '';

    var confirmMessage = hasOriginalLogo
        ? 'Logo sẽ được xóa khi bạn nhấn "Cập nhật thông tin". Bạn có muốn tiếp tục?'
        : 'Bạn có muốn xóa logo vừa chọn?';

    if (confirm(confirmMessage)) {
        // Set flag to remove logo when form is submitted (only if has original logo)
        var removeLogoFlag = document.getElementById('remove-logo');
        if (removeLogoFlag && hasOriginalLogo) {
            removeLogoFlag.value = '1';
        }

        // Clear file input
        document.getElementById('company_logo').value = '';

        // Replace logo with default placeholder
        var previewContainer = document.getElementById('logo-preview');
        previewContainer.outerHTML = '<div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto" id="logo-preview" style="width: 150px; height: 100px; border: 3px solid #ddd;"><i class="fas fa-building text-muted" style="font-size: 36px;"></i></div>';

        // Change button text to indicate pending removal (only if has original logo)
        var removeBtn = document.querySelector('button[onclick="removeLogo()"]');
        if (removeBtn && hasOriginalLogo) {
            removeBtn.innerHTML = '<i class="fas fa-undo mr-1"></i>Khôi phục logo';
            removeBtn.setAttribute('onclick', 'restoreLogo()');
            removeBtn.className = 'btn btn-warning btn-sm mt-2';

            // Show info message
            showTempMessage('Logo sẽ được xóa khi bạn nhấn "Cập nhật thông tin"', 'info');
        } else if (removeBtn) {
            // If no original logo, just hide the button
            removeBtn.remove();
            if (removeLogoFlag) {
                removeLogoFlag.remove();
            }
        }
    }
}

function restoreLogo() {
    // Reset flag
    var removeLogoFlag = document.getElementById('remove-logo');
    if (removeLogoFlag) {
        removeLogoFlag.value = '0';
    }

    // Get original logo URL
    var originalUrlInput = document.getElementById('original-logo-url');
    var originalUrl = originalUrlInput ? originalUrlInput.value : '';

    if (originalUrl && originalUrl.trim() !== '') {
        // Restore original logo image from DB
        var previewContainer = document.getElementById('logo-preview');
        previewContainer.outerHTML = '<img src="' + originalUrl + '" alt="Logo" class="rounded" id="logo-preview" style="width: 150px; height: 100px; object-fit: contain; border: 3px solid #ddd; background: white;">';

        // Change button back to remove
        var restoreBtn = document.querySelector('button[onclick="restoreLogo()"]');
        if (!restoreBtn) {
            // Try finding by class if onclick selector doesn't work
            restoreBtn = document.querySelector('.logo-upload .btn-warning');
        }

        if (restoreBtn) {
            restoreBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa logo';
            restoreBtn.setAttribute('onclick', 'removeLogo()');
            restoreBtn.className = 'btn btn-danger btn-sm mt-2';
        }

        showTempMessage('Đã khôi phục logo từ cơ sở dữ liệu', 'success');
    } else {
        // No original logo to restore, just show placeholder
        var previewContainer = document.getElementById('logo-preview');
        previewContainer.outerHTML = '<div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto" id="logo-preview" style="width: 150px; height: 100px; border: 3px solid #ddd;"><i class="fas fa-building text-muted" style="font-size: 36px;"></i></div>';

        showTempMessage('Không có logo gốc để khôi phục', 'info');
    }
}

function showTempMessage(message, type) {
    // Create alert element
    var alertClass = 'alert-success';
    var iconClass = 'fa-check-circle';

    if (type === 'error') {
        alertClass = 'alert-danger';
        iconClass = 'fa-exclamation-circle';
    } else if (type === 'info') {
        alertClass = 'alert-info';
        iconClass = 'fa-info-circle';
    }

    var alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass} mr-2"></i>${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

    // Remove existing temp alerts
    var existingAlerts = document.querySelectorAll('.alert.temp-message');
    existingAlerts.forEach(function(alert) {
        alert.remove();
    });

    // Insert alert at the top of form
    var cardBody = document.querySelector('.card-body');
    var firstChild = cardBody.querySelector('h4');
    var alertDiv = document.createElement('div');
    alertDiv.className = 'temp-message';
    alertDiv.innerHTML = alertHtml;
    firstChild.insertAdjacentElement('afterend', alertDiv);

    // Auto dismiss after 3 seconds
    setTimeout(function() {
        var alert = document.querySelector('.alert.temp-message');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
</script>
@endsection

