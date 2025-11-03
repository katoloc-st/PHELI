@extends('layouts.app')

@section('title', 'Thêm bài đăng - Hệ thống quản lý phế liệu')

@section('content')
      <!-- Inner Header -->
      <section class="bg-dark py-5 user-header">
         <div class="container">
            <div class="row align-items-center mt-2 mb-5 pb-4">
               <div class="col">
                  <!-- Heading -->
                  <h1 class="text-white mb-2">
                     Thêm bài đăng
                  </h1>
                  <!-- Text -->
                  <h6 class="font-weight-normal text-white-50 mb-0">
                     Tạo bài đăng mua/bán phế liệu mới
                  </h6>
               </div>
               <div class="col-auto">
                  <!-- Button -->
                  <a href="{{ route('posts.my-posts') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
               </div>
            </div>
            <!-- / .row -->
         </div>
         <!-- / .container -->
      </section>
      <!-- End Inner Header -->

      <!-- Add Property -->
      <section class="section-padding pt-0 user-pages-main">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-3">
                  <!-- Collapse -->
                  @include('layouts.sidebar')
               </div>
               <div class="col-lg-9 col-md-9">
                  @if(session('success'))
                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  @endif

                  @if($errors->any())
                     <div class="alert alert-danger">
                        <h6><i class="mdi mdi-alert-circle"></i> Vui lòng kiểm tra lại thông tin:</h6>
                        <ul class="mb-0">
                           @foreach($errors->all() as $error)
                              <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
                  @endif

                  <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                     @csrf
                     <div class="card padding-card">
                        <div class="card-body">
                           <h5 class="card-title mb-4">
                              <i class="mdi mdi-information"></i> Thông tin cơ bản
                           </h5>

                           <div class="form-group">
                              <label>Tiêu đề bài đăng <span class="required-field">*</span></label>
                              <input type="text" class="form-control @error('title') is-invalid @enderror"
                                     name="title" value="{{ old('title') }}"
                                     placeholder="Nhập tiêu đề bài đăng" required>
                              @error('title')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label>Điểm tập kết <span class="required-field">*</span></label>
                              <div class="d-flex align-items-center">
                                 <select class="form-control custom-select @error('collection_point_id') is-invalid @enderror flex-grow-1"
                                         name="collection_point_id" required style="margin-right: 10px;">
                                    <option value="">Chọn điểm tập kết</option>
                                    @foreach(Auth::user()->collectionPoints as $collectionPoint)
                                       <option value="{{ $collectionPoint->id }}" {{ old('collection_point_id') == $collectionPoint->id ? 'selected' : '' }}>
                                          {{ $collectionPoint->name }} - {{ $collectionPoint->full_address }}
                                       </option>
                                    @endforeach
                                 </select>
                                 <a href="{{ route('collection-points.create') }}" class="btn btn-outline-primary flex-shrink-0" target="_blank" title="Thêm điểm tập kết mới">
                                    <i class="fas fa-plus"></i> Thêm mới
                                 </a>
                              </div>
                              @error('collection_point_id')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label>Loại phế liệu <span class="required-field">*</span></label>
                              <select class="form-control custom-select @error('waste_type_id') is-invalid @enderror"
                                      id="waste_type_id" name="waste_type_id" required>
                                 <option value="">Chọn loại phế liệu</option>
                                 @foreach($wasteTypes as $wasteType)
                                    <option value="{{ $wasteType->id }}" {{ old('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                       {{ $wasteType->name }}
                                    </option>
                                 @endforeach
                              </select>
                              @error('waste_type_id')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label>Mô tả chi tiết </label>
                              <textarea class="form-control @error('description') is-invalid @enderror"
                                        name="description" rows="4"
                                        placeholder="Mô tả chi tiết về phế liệu" >{{ old('description') }}</textarea>
                              @error('description')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Số lượng (kg) <span class="required-field">*</span></label>
                                    <input type="number" step="0.01" min="0.01"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           name="quantity" value="{{ old('quantity') }}"
                                           placeholder="Nhập số lượng" required>
                                    @error('quantity')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Giá (VNĐ/kg) <span class="required-field">*</span></label>
                                    <input type="number" step="1" min="1"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}"
                                           placeholder="Nhập giá" required>
                                    @error('price')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if($user->role === 'waste_company')
                                    <!-- Slider cho doanh nghiệp -->
                                    <div class="price-slider-container mt-2" style="display: none;">
                                       <label class="slider-label">Điều chỉnh giá bằng thanh kéo:</label>
                                       <div class="price-range-wrapper">
                                          <span class="price-min">0</span>
                                          <input type="range" class="form-range price-slider"
                                                 id="priceSlider" min="0" max="100000"
                                                 value="{{ old('price', 0) }}" step="1000">
                                          <span class="price-max">100,000</span>
                                       </div>
                                       <div class="price-info mt-2">
                                          <small class="text-info">
                                             <i class="mdi mdi-information"></i>
                                             Giá thị trường: <span id="marketPrice">-</span> VNĐ/kg
                                          </small>
                                       </div>
                                    </div>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="card padding-card">
                        <div class="card-body">
                           <h5 class="card-title mb-4">
                              <i class="mdi mdi-image-multiple"></i> Hình ảnh phế liệu
                           </h5>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="fuzone">
                                    <div class="fu-text">
                                       <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                                    </div>
                                    <input class="upload" type="file" name="images[]" accept="image/*">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="fuzone">
                                    <div class="fu-text">
                                       <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                                    </div>
                                    <input class="upload" type="file" name="images[]" accept="image/*">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="fuzone">
                                    <div class="fu-text">
                                       <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                                    </div>
                                    <input class="upload" type="file" name="images[]" accept="image/*">
                                 </div>
                              </div>
                           </div>
                           <small class="text-muted">Tải lên tối đa 3 hình ảnh. Định dạng hỗ trợ: JPG, PNG, GIF. Kích thước tối đa: 5MB mỗi file.</small>
                        </div>
                     </div>





                     <div class="form-group mb-4">
                        <button type="submit" class="btn btn-success btn-lg">
                           <i class="mdi mdi-check"></i> ĐĂNG BÀI
                        </button>
                        <a href="{{ route('posts.my-posts') }}" class="btn btn-secondary btn-lg ml-2">
                           <i class="mdi mdi-arrow-left"></i> Quay lại
                        </a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </section>
      <!-- End Add Property -->
@endsection

@section('custom-css')
<style>
.card {
    border: 1px solid #e9ecef;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.required-field {
    color: #dc3545;
}

.alert {
    border-radius: 0.375rem;
}

.invalid-feedback {
    display: block;
}

.fuzone {
    width: 100%;
    height: 120px;
    padding: 0;
    text-align: center;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: visible; /* Thay đổi thành visible để nút xóa không bị cắt */
}

.fuzone:hover:not(.has-image) {
    border-color: #28a745;
    background: #f0f8f0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
}

.fuzone.dragover {
    border-color: #28a745;
    background: #e8f5e8;
    transform: scale(1.02);
}

.fuzone.has-image {
    border: 2px solid #28a745;
    background: #fff;
    cursor: default;
    padding: 0;
    height: 200px; /* Tăng chiều cao khi có ảnh */
    overflow: visible; /* Đảm bảo nút xóa không bị cắt */
}

.fu-text {
    pointer-events: none;
    padding: 20px;
    text-align: center;
}

.fu-text span {
    color: #6c757d;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.4;
}

.fu-text i {
    font-size: 28px;
    display: block;
    margin-bottom: 8px;
    color: #28a745;
    opacity: 0.8;
}

.upload {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.preview-container {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: visible; /* Thay đổi từ hidden thành visible */
    border-radius: 12px;
    pointer-events: none;
}

.preview-container * {
    pointer-events: auto;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
}

.remove-image {
    position: absolute;
    top: 5px;   /* Vị trí gần góc trên phải nhưng vẫn hiển thị đầy đủ */
    right: 5px; /* Vị trí gần góc trên phải nhưng vẫn hiển thị đầy đủ */
    background: linear-gradient(135deg, #ff4757, #ff3742);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(255, 71, 87, 0.4);
    transition: all 0.3s ease;
    z-index: 1000;
    opacity: 0.95;
    user-select: none;
    pointer-events: auto;
    border: 2px solid white; /* Border trắng để nổi bật trên ảnh */
}

.remove-image:hover {
    background: linear-gradient(135deg, #ff3742, #ff2f3a);
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 5px 15px rgba(255, 71, 87, 0.6);
    opacity: 1;
}

.remove-image:active {
    transform: scale(0.95) rotate(90deg);
}

.remove-image::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    z-index: -1;
}

/* Đảm bảo icon bên trong nút xóa cũng có thể click được */
.remove-image i {
    pointer-events: none;
    line-height: 1;
}

.required-field {
    color: #dc3545;
}

/* Collection Point Button Style */
.btn-outline-primary {
    white-space: nowrap;
    min-width: 180px;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Additional hover effects */
.fuzone:not(.has-image) .fu-text i {
    transition: all 0.3s ease;
}

.fuzone:not(.has-image):hover .fu-text i {
    transform: scale(1.1);
    color: #28a745;
}

.fuzone:not(.has-image):hover .fu-text span {
    color: #495057;
}

/* Loading animation for image upload */
.preview-container.loading::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 5;
}

.preview-container.loading::after {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #28a745;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    position: absolute;
    z-index: 6;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Price Slider Styles */
.price-slider-container {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
}

.slider-label {
    font-size: 13px;
    font-weight: 500;
    color: #495057;
    margin-bottom: 10px;
    display: block;
}

.price-range-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.price-min, .price-max {
    font-size: 12px;
    color: #6c757d;
    min-width: 60px;
    text-align: center;
}

.form-range.price-slider {
    flex: 1;
    height: 6px;
    background: #dee2e6;
    border-radius: 3px;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
}

.form-range.price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: #28a745;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.form-range.price-slider::-webkit-slider-thumb:hover {
    background: #218838;
    transform: scale(1.1);
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
}

.form-range.price-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: #28a745;
    border-radius: 50%;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.form-range.price-slider::-moz-range-thumb:hover {
    background: #218838;
    transform: scale(1.1);
}

.price-info {
    text-align: center;
}

.price-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 10px;
    border-radius: 6px;
    margin-top: 10px;
    font-size: 13px;
}

.price-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 10px;
    border-radius: 6px;
    margin-top: 10px;
    font-size: 13px;
}
</style>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
    // Variables for price control
    let currentMarketPrice = 0;
    let userRole = '{{ $user->role }}';

    // Handle waste type selection for companies
    $('#waste_type_id').on('change', function() {
        const wasteTypeId = $(this).val();

        if (wasteTypeId && userRole === 'waste_company') {
            // Fetch market price for selected waste type
            $.ajax({
                url: `/api/waste-type/${wasteTypeId}/price`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        currentMarketPrice = response.price;

                        // Update UI elements
                        $('#marketPrice').text(new Intl.NumberFormat('vi-VN').format(currentMarketPrice));
                        $('#price').val(currentMarketPrice);

                        // Setup slider
                        const slider = $('#priceSlider');
                        slider.attr('max', currentMarketPrice);
                        slider.val(currentMarketPrice);
                        $('.price-max').text(new Intl.NumberFormat('vi-VN').format(currentMarketPrice));

                        // Show slider container
                        $('.price-slider-container').slideDown();

                        // Remove any existing warnings
                        $('.price-warning, .price-error').remove();
                    }
                },
                error: function() {
                    console.error('Failed to fetch market price');
                }
            });
        } else if (userRole === 'waste_company') {
            // Hide slider if no waste type selected
            $('.price-slider-container').slideUp();
            currentMarketPrice = 0;
        }
    });

    // Handle slider input for companies
    $('#priceSlider').on('input', function() {
        const sliderValue = parseInt($(this).val());
        $('#price').val(sliderValue);

        // Update price display and validation
        validateCompanyPrice(sliderValue);
    });

    // Handle manual price input for companies
    $('#price').on('input', function() {
        const inputValue = parseInt($(this).val()) || 0;

        if (userRole === 'waste_company' && currentMarketPrice > 0) {
            // Update slider position
            $('#priceSlider').val(inputValue);

            // Validate price
            validateCompanyPrice(inputValue);
        }
    });

    // Price validation function for companies
    function validateCompanyPrice(price) {
        // Remove existing warnings
        $('.price-warning, .price-error').remove();

        if (userRole !== 'waste_company' || currentMarketPrice === 0) {
            return true;
        }

        const priceContainer = $('#price').closest('.form-group');

        if (price > currentMarketPrice) {
            // Price exceeds market price - show error
            const errorHtml = `
                <div class="price-error">
                    <i class="mdi mdi-alert-circle"></i>
                    <strong>Không được phép:</strong> Với vai trò doanh nghiệp, giá bán không được vượt quá giá thị trường
                    (${new Intl.NumberFormat('vi-VN').format(currentMarketPrice)} VNĐ/kg).
                </div>
            `;
            priceContainer.append(errorHtml);

            // Reset to market price
            $('#price').val(currentMarketPrice);
            $('#priceSlider').val(currentMarketPrice);

            return false;
        } else if (price < currentMarketPrice * 0.5) {
            // Price is too low - show warning
            const warningHtml = `
                <div class="price-warning">
                    <i class="mdi mdi-alert"></i>
                    <strong>Lưu ý:</strong> Giá bán thấp hơn 50% so với giá thị trường.
                    Giá thị trường hiện tại: ${new Intl.NumberFormat('vi-VN').format(currentMarketPrice)} VNĐ/kg.
                </div>
            `;
            priceContainer.append(warningHtml);
        }

        return true;
    }

    // File upload handling
    $(document).on('change', '.upload', function() {
        var $this = $(this);
        var files = this.files;
        var $fuzone = $this.closest('.fuzone');

        if (files.length > 0) {
            var file = files[0];
            if (file.type.startsWith('image/')) {
                // Validate file size (5MB = 5242880 bytes)
                if (file.size > 5242880) {
                    alert('Kích thước file quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
                    $this.val(''); // Clear the input
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $fuzone.addClass('has-image').html(`
                        <div class="preview-container">
                            <img src="${e.target.result}" class="preview-image" alt="Preview">
                            <button type="button" class="remove-image" title="Xóa ảnh" onclick="return false;">
                                ×
                            </button>
                        </div>
                        <input class="upload" type="file" name="images[]" accept="image/*" style="display: none;">
                    `);

                    // Đặt lại file cho input mới
                    var newInput = $fuzone.find('.upload')[0];
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    newInput.files = dt.files;
                };
                reader.readAsDataURL(file);
            } else {
                alert('Vui lòng chọn file ảnh (JPG, PNG, GIF)!');
                $this.val(''); // Clear the input
            }
        }
    });

    // Remove image - sử dụng event delegation với specificity cao hơn
    $(document).on('click', '.remove-image, .remove-image *', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $button = $(this).hasClass('remove-image') ? $(this) : $(this).closest('.remove-image');
        var $fuzone = $button.closest('.fuzone');

        // Reset to original upload state
        $fuzone.removeClass('has-image').html(`
            <div class="fu-text">
                <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
            </div>
            <input class="upload" type="file" name="images[]" accept="image/*">
        `);

        console.log('Image removed successfully'); // Debug log
        return false;
    });

    // Drag and drop handling
    $(document).on('dragover', '.fuzone', function(e) {
        e.preventDefault();
        if (!$(this).hasClass('has-image')) {
            $(this).addClass('dragover');
        }
    });

    $(document).on('dragleave', '.fuzone', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $(document).on('drop', '.fuzone', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');

        // Only allow drop if no image is already uploaded
        if ($(this).hasClass('has-image')) {
            return;
        }

        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            var $input = $(this).find('.upload');
            if ($input.length) {
                // Create a new FileList object and assign it to the input
                var dt = new DataTransfer();
                dt.items.add(files[0]);
                $input[0].files = dt.files;
                $input.trigger('change');
            }
        }
    });

    // Click on fuzone to trigger file input (only if no image uploaded)
    $(document).on('click', '.fuzone', function(e) {
        // Kiểm tra xem có click vào nút xóa hay bên trong nút xóa
        if ($(e.target).hasClass('remove-image') ||
            $(e.target).closest('.remove-image').length > 0 ||
            $(e.target).hasClass('mdi-close')) {
            console.log('Clicked on remove button, preventing file dialog'); // Debug log
            return false;
        }

        // Chỉ mở file dialog nếu chưa có ảnh
        if (!$(this).hasClass('has-image')) {
            console.log('Opening file dialog'); // Debug log
            $(this).find('.upload').click();
        } else {
            console.log('Image already exists, not opening file dialog'); // Debug log
        }
    });

    // Auto hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);

    // Price input formatting
    $('#price').on('input', function() {
        var value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    });

    // Remove price formatting before submit
    $('form').on('submit', function() {
        $('#price').val($('#price').val().replace(/,/g, ''));
    });

    // Dynamic form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        let requiredFields = ['title', 'waste_type_id', 'quantity', 'price', 'collection_point_id'];

        requiredFields.forEach(function(field) {
            let input = $(`[name="${field}"]`);
            if (!input.val()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        // Additional validation for company price
        if (userRole === 'waste_company' && currentMarketPrice > 0) {
            const price = parseInt($('#price').val()) || 0;
            if (price > currentMarketPrice) {
                e.preventDefault();
                alert(`Giá bán không được vượt quá giá thị trường (${new Intl.NumberFormat('vi-VN').format(currentMarketPrice)} VNĐ/kg)`);
                $('#price').focus();
                return false;
            }
        }

        // Debug: Kiểm tra file inputs
        console.log('Form submit - checking files:');
        $('input[name="images[]"]').each(function(index) {
            console.log(`File input ${index}:`, this.files.length > 0 ? this.files[0] : 'No file');
        });

        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
@endsection
