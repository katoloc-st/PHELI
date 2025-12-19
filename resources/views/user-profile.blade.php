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
                     Thông tin người đại diện
                  </h1>
                  <!-- Text -->
                  <h6 class="font-weight-normal text-white-50 mb-0">
                     Quản lý thông tin người đại diện của công ty
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
                  <form class="card padding-card" style="max-width:700px;margin:auto;" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                     @csrf
                     @method('PUT')
                     <div class="card-body">
                        <h4 class="mb-4">Cập nhật thông tin người đại diện</h4>

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

                        <!-- Avatar Upload Section -->
                        <div class="form-group text-center mb-4">
                           <div class="avatar-upload-container mb-3">
                              <div class="avatar-preview d-flex justify-content-center">
                                 @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" id="avatar-preview" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">
                                 @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" id="avatar-preview" style="width: 120px; height: 120px; border: 3px solid #ddd;">
                                       <i class="fas fa-user text-muted" style="font-size: 48px;"></i>
                                    </div>
                                 @endif
                              </div>
                              <div class="avatar-upload mt-3">
                                 <input type="file" class="form-control-file @error('avatar') is-invalid @enderror"
                                        id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(this)">
                                 @if($user->avatar)
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeAvatar()">
                                       <i class="fas fa-trash mr-1"></i>Xóa ảnh đại diện
                                    </button>
                                    <input type="hidden" id="remove-avatar" name="remove_avatar" value="0">
                                    <input type="hidden" id="original-avatar-url" value="{{ asset('storage/' . $user->avatar) }}">
                                 @endif
                                 <small class="form-text text-muted">Chọn ảnh định dạng JPG, PNG. Kích thước tối đa 2MB.</small>
                                 @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                              </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label for="name">Họ và tên <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('name') is-invalid @enderror"
                                  id="name" name="name"
                                  value="{{ old('name', $user->name) }}"
                                  placeholder="Nhập họ và tên" required>
                           @error('name')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                  id="phone" name="phone"
                                  value="{{ old('phone', $user->phone) }}"
                                  placeholder="Nhập số điện thoại" required>
                           @error('phone')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="email">Email <span class="text-danger">*</span></label>
                           <input type="email" class="form-control @error('email') is-invalid @enderror"
                                  id="email" name="email"
                                  value="{{ old('email', $user->email) }}"
                                  placeholder="Nhập email" required>
                           @error('email')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="address">Địa chỉ</label>
                           <input type="text" class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address"
                                  value="{{ old('address', $user->address) }}"
                                  placeholder="Nhập địa chỉ">
                           @error('address')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>

                        <div class="form-group">
                           <label for="description">Giới thiệu bản thân</label>
                           <textarea class="form-control @error('description') is-invalid @enderror"
                                     id="description" name="description" rows="3"
                                     placeholder="Mô tả ngắn về bản thân">{{ old('description', $user->description) }}</textarea>
                           @error('description')
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
.avatar-upload-container {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.avatar-preview {
    margin-bottom: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.avatar-preview img,
.avatar-preview div {
    border: 3px solid #ddd;
    transition: border-color 0.3s;
}

.avatar-preview img:hover,
.avatar-preview div:hover {
    border-color: #007bff;
}

.avatar-upload input[type="file"] {
    max-width: 300px;
    margin: 0 auto;
}

.card.padding-card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
}

/* Ensure avatar placeholder is perfectly centered */
#avatar-preview {
    margin: 0 auto;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Remove avatar button styling */
.avatar-upload .btn-danger,
.avatar-upload .btn-warning {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 4px;
}

.avatar-upload .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.avatar-upload .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}
</style>
@endsection

@section('custom-js')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var previewContainer = document.getElementById('avatar-preview');

            // Replace the content with an img element
            previewContainer.outerHTML = '<img src="' + e.target.result + '" alt="Avatar" class="rounded-circle" id="avatar-preview" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">';

            // Reset remove avatar flag when new file is selected
            var removeAvatarInput = document.getElementById('remove-avatar');
            if (removeAvatarInput) {
                removeAvatarInput.value = '0';
            }

            // Show remove button if it doesn't exist or update existing button
            var avatarUpload = document.querySelector('.avatar-upload');
            var removeBtn = document.querySelector('button[onclick="removeAvatar()"], button[onclick="restoreAvatar()"]');

            if (!removeBtn) {
                var removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger btn-sm mt-2';
                removeButton.onclick = removeAvatar;
                removeButton.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa ảnh đại diện';

                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'remove-avatar';
                hiddenInput.name = 'remove_avatar';
                hiddenInput.value = '0';

                // Create original avatar URL input if it doesn't exist
                var originalUrlInput = document.getElementById('original-avatar-url');
                if (!originalUrlInput) {
                    originalUrlInput = document.createElement('input');
                    originalUrlInput.type = 'hidden';
                    originalUrlInput.id = 'original-avatar-url';
                    originalUrlInput.value = ''; // Will be empty for new uploads, only preserve DB avatar
                }

                var fileInput = document.getElementById('avatar');
                avatarUpload.insertBefore(removeButton, fileInput.nextSibling);
                avatarUpload.insertBefore(hiddenInput, removeButton.nextSibling);
                if (!document.getElementById('original-avatar-url')) {
                    avatarUpload.insertBefore(originalUrlInput, hiddenInput.nextSibling);
                }
            } else {
                // Update existing button to normal remove state
                removeBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa ảnh đại diện';
                removeBtn.setAttribute('onclick', 'removeAvatar()');
                removeBtn.className = 'btn btn-danger btn-sm mt-2';

                // DO NOT update original avatar URL - keep the original DB avatar URL
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}function removeAvatar() {
    // Check if there's an original avatar from DB
    var originalUrlInput = document.getElementById('original-avatar-url');
    var hasOriginalAvatar = originalUrlInput && originalUrlInput.value.trim() !== '';

    var confirmMessage = hasOriginalAvatar
        ? 'Ảnh sẽ được xóa khi bạn nhấn "Cập nhật thông tin". Bạn có muốn tiếp tục?'
        : 'Bạn có muốn xóa ảnh vừa chọn?';

    if (confirm(confirmMessage)) {
        // Set flag to remove avatar when form is submitted (only if has original avatar)
        var removeAvatarFlag = document.getElementById('remove-avatar');
        if (removeAvatarFlag && hasOriginalAvatar) {
            removeAvatarFlag.value = '1';
        }

        // Clear file input
        document.getElementById('avatar').value = '';

        // Replace avatar with default placeholder
        var previewContainer = document.getElementById('avatar-preview');
        previewContainer.outerHTML = '<div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" id="avatar-preview" style="width: 120px; height: 120px; border: 3px solid #ddd;"><i class="fas fa-user text-muted" style="font-size: 48px;"></i></div>';

        // Change button text to indicate pending removal (only if has original avatar)
        var removeBtn = document.querySelector('button[onclick="removeAvatar()"]');
        if (removeBtn && hasOriginalAvatar) {
            removeBtn.innerHTML = '<i class="fas fa-undo mr-1"></i>Khôi phục ảnh';
            removeBtn.setAttribute('onclick', 'restoreAvatar()');
            removeBtn.className = 'btn btn-warning btn-sm mt-2';

            // Show info message
            showTempMessage('Ảnh sẽ được xóa khi bạn nhấn "Cập nhật thông tin"', 'info');
        } else if (removeBtn) {
            // If no original avatar, just hide the button
            removeBtn.remove();
            if (removeAvatarFlag) {
                removeAvatarFlag.remove();
            }
        }
    }
}function restoreAvatar() {
    // Reset flag
    var removeAvatarFlag = document.getElementById('remove-avatar');
    if (removeAvatarFlag) {
        removeAvatarFlag.value = '0';
    }

    // Get original avatar URL
    var originalUrlInput = document.getElementById('original-avatar-url');
    var originalUrl = originalUrlInput ? originalUrlInput.value : '';

    if (originalUrl && originalUrl.trim() !== '') {
        // Restore original avatar image from DB
        var previewContainer = document.getElementById('avatar-preview');
        previewContainer.outerHTML = '<img src="' + originalUrl + '" alt="Avatar" class="rounded-circle" id="avatar-preview" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">';

        // Change button back to remove
        var restoreBtn = document.querySelector('button[onclick="restoreAvatar()"]');
        if (!restoreBtn) {
            // Try finding by class if onclick selector doesn't work
            restoreBtn = document.querySelector('.avatar-upload .btn-warning');
        }

        if (restoreBtn) {
            restoreBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Xóa ảnh đại diện';
            restoreBtn.setAttribute('onclick', 'removeAvatar()');
            restoreBtn.className = 'btn btn-danger btn-sm mt-2';
        }

        showTempMessage('Đã khôi phục ảnh đại diện từ cơ sở dữ liệu', 'success');
    } else {
        // No original avatar to restore, just show placeholder
        var previewContainer = document.getElementById('avatar-preview');
        previewContainer.outerHTML = '<div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" id="avatar-preview" style="width: 120px; height: 120px; border: 3px solid #ddd;"><i class="fas fa-user text-muted" style="font-size: 48px;"></i></div>';

        showTempMessage('Không có ảnh gốc để khôi phục', 'info');
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

