@extends('layouts.app')

@section('title', 'Chỉnh sửa bài đăng - Hệ thống quản lý phế liệu')

@section('content')
<!-- Inner Header -->
<section class="bg-dark py-5 user-header">
   <div class="container">
      <div class="row align-items-center mt-2 mb-5 pb-4">
         <div class="col">
            <!-- Heading -->
            <h1 class="text-white mb-2">
               Chỉnh sửa bài đăng
            </h1>
            <!-- Text -->
            <h6 class="font-weight-normal text-white-50 mb-0">
               Cập nhật thông tin bài đăng phế liệu
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

<!-- Edit Post -->
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

            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
               @csrf
               @method('PUT')

               <div class="card padding-card">
                  <div class="card-body">
                     <h5 class="card-title mb-4">
                        <i class="mdi mdi-information"></i> Thông tin cơ bản
                     </h5>

                     <!-- Thông báo loại bài đăng dựa trên role -->
                     <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline"></i>
                        <strong>Loại bài đăng của bạn:</strong>
                        @if(Auth::user()->role === 'waste_company')
                           <span class="badge badge-success">Doanh nghiệp xanh</span>
                           <small class="d-block mt-1">Bạn có thể đăng bài bán phế liệu với giá không vượt quá giá thị trường.</small>
                        @elseif(Auth::user()->role === 'scrap_dealer')
                           <span class="badge badge-primary">Cơ sở phế liệu</span>
                           <small class="d-block mt-1">Bạn có thể đăng bài mua phế liệu với giá từ giá thị trường trở lên.</small>
                        @elseif(Auth::user()->role === 'recycling_plant')
                           <span class="badge badge-primary">Cơ sở phế liệu</span>
                           <small class="d-block mt-1">Bạn có thể đăng bài mua phế liệu với giá từ giá thị trường trở lên.</small>
                        @endif
                     </div>

                     <div class="form-group">
                        <label>Tiêu đề bài đăng <span class="required-field">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" value="{{ old('title', $post->title) }}"
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
                                 <option value="{{ $collectionPoint->id }}"
                                         {{ old('collection_point_id', $post->collection_point_id) == $collectionPoint->id ? 'selected' : '' }}>
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
                        <select class="form-control @error('waste_type_id') is-invalid @enderror"
                                id="waste_type_id" name="waste_type_id" required>
                           <option value="">Chọn loại phế liệu</option>
                           @foreach($wasteTypes as $wasteType)
                              <option value="{{ $wasteType->id }}"
                                      {{ old('waste_type_id', $post->waste_type_id) == $wasteType->id ? 'selected' : '' }}>
                                 {{ $wasteType->name }}
                              </option>
                           @endforeach
                        </select>
                        @error('waste_type_id')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>

                     <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  name="description" rows="4"
                                  placeholder="Mô tả chi tiết về phế liệu">{{ old('description', $post->description) }}</textarea>
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
                                     name="quantity" value="{{ old('quantity', $post->quantity) }}"
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
                                     id="price" name="price" value="{{ old('price', $post->price) }}"
                                     placeholder="Nhập giá" required>
                              @error('price')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror

                              @if($user->role === 'waste_company')
                              <!-- Slider cho doanh nghiệp -->
                              <div class="price-slider-container mt-2" id="priceSliderContainer" style="display: none;">
                                 <label class="slider-label">Điều chỉnh giá bằng thanh kéo:</label>
                                 <div class="price-range-wrapper">
                                    <span class="price-min">0</span>
                                    <input type="range" class="form-range price-slider"
                                           id="priceSlider" min="0" max="100000"
                                           value="{{ old('price', $post->price) }}" step="1000">
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

               <div class="card mt-3">
                  <div class="card-body">
                     <h5 class="card-title mb-4">
                        <i class="mdi mdi-image-multiple"></i> Hình ảnh phế liệu
                     </h5>

                     <!-- Quản lý ảnh (tối đa 3 ảnh) -->
                     <div class="row" id="imageManager">
                        @php
                            $currentImages = $post->images ?? [];
                            $maxImages = 3;
                            $remainingSlots = $maxImages - count($currentImages);
                        @endphp

                        <!-- Hiển thị ảnh hiện tại -->
                        @foreach($currentImages as $index => $image)
                           <div class="col-md-4 mb-2">
                              <div class="fuzone has-image current-image-slot clickable-image"
                                   data-index="{{ $index }}" style="cursor: pointer;" title="Nhấn để thay đổi ảnh">
                                 <div class="preview-container">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         class="preview-image current-image"
                                         alt="Ảnh phế liệu {{ $index + 1 }}"
                                         style="display: block;">
                                    <button type="button" class="remove-current-image"
                                            title="Xóa ảnh này" data-index="{{ $index }}">
                                       ×
                                    </button>
                                 </div>
                                 <input type="file" class="upload-replace"
                                        accept="image/*" style="display: none;"
                                        data-index="{{ $index }}">
                                 <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                 <input type="hidden" name="remove_images[]" value="" class="remove-flag">
                              </div>
                           </div>
                        @endforeach

                        <!-- Ô trống cho ảnh mới -->
                        @for($i = 0; $i < $remainingSlots; $i++)
                           <div class="col-md-4 mb-2">
                              <div class="fuzone empty-slot">
                                 <div class="fu-text">
                                    <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                                 </div>
                                 <input class="upload" type="file" name="new_images[]" accept="image/*">
                              </div>
                           </div>
                        @endfor
                     </div>
                     <small class="text-muted">Tải lên tối đa 3 hình ảnh. Định dạng hỗ trợ: JPG, PNG, GIF. Kích thước tối đa: 5MB mỗi file.</small>
                  </div>
               </div>



               <div class="form-group mt-4">
                  <button type="submit" class="btn btn-success btn-lg">
                     <i class="mdi mdi-check"></i> CẬP NHẬT
                  </button>
                  <a href="{{ route('posts.my-posts') }}" class="btn btn-secondary btn-lg ml-2">
                     <i class="mdi mdi-arrow-left"></i> Hủy
                  </a>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>
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

.text-danger, .required-field {
    color: #dc3545 !important;
}

.alert {
    border-radius: 0.375rem;
}

.invalid-feedback {
    display: block;
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

/* Image Upload Styles */
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
    overflow: visible;
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
    height: 150px;
    overflow: hidden;
}

.fuzone.has-image.clickable-image {
    cursor: pointer;
    transition: all 0.3s ease;
}

.fuzone.has-image.clickable-image:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
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
    height: 150px;
    overflow: hidden;
    border-radius: 12px;
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
    top: 5px;
    right: 5px;
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
    border: 2px solid white;
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

.remove-image i {
    pointer-events: none;
    line-height: 1;
}

/* Current Image Action Buttons */
.remove-current-image {
    position: absolute;
    top: 5px;
    right: 5px;
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
    z-index: 1001;
    opacity: 0.95;
    user-select: none;
    pointer-events: auto;
    border: 2px solid white;
}

.remove-current-image:hover {
    background: linear-gradient(135deg, #ff3742, #ff2f3a);
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 5px 15px rgba(255, 71, 87, 0.6);
    opacity: 1;
}

.change-current-image {
    position: absolute;
    top: 5px;
    left: 5px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(0, 123, 255, 0.4);
    transition: all 0.3s ease;
    z-index: 1001;
    opacity: 0.95;
    user-select: none;
    pointer-events: auto;
    border: 2px solid white;
}

.change-current-image:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.6);
    opacity: 1;
}

.current-image-slot {
    border: 2px solid #28a745;
    background: #fff;
}

.current-image-slot .preview-container {
    height: 150px;
}

/* Current Images Styles */
.current-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.current-image-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.current-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.6));
    padding: 8px 12px;
    text-align: center;
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
</style>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {
    // Variables for price control
    let currentMarketPrice = 0;
    let userRole = '{{ $user->role }}';
    let currentWasteTypeId = '{{ $post->waste_type_id }}';

    // Initialize for companies
    if (userRole === 'waste_company' && currentWasteTypeId) {
        loadMarketPrice(currentWasteTypeId);
    }

    // Handle waste type selection for companies
    $('#waste_type_id').on('change', function() {
        const wasteTypeId = $(this).val();

        if (wasteTypeId && userRole === 'waste_company') {
            loadMarketPrice(wasteTypeId);
        } else if (userRole === 'waste_company') {
            // Hide slider if no waste type selected
            $('#priceSliderContainer').slideUp();
            currentMarketPrice = 0;
        }
    });

    // Function to load market price
    function loadMarketPrice(wasteTypeId) {
        $.ajax({
            url: `/api/waste-type/${wasteTypeId}/price`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    currentMarketPrice = response.price;

                    // Update UI elements
                    $('#marketPrice').text(new Intl.NumberFormat('vi-VN').format(currentMarketPrice));

                    // Setup slider
                    const slider = $('#priceSlider');
                    const currentPrice = parseInt($('#price').val()) || 0;

                    slider.attr('max', currentMarketPrice);
                    slider.val(currentPrice);
                    $('.price-max').text(new Intl.NumberFormat('vi-VN').format(currentMarketPrice));

                    // Show slider container
                    $('#priceSliderContainer').slideDown();

                    // Validate current price
                    validateCompanyPrice(currentPrice);
                }
            },
            error: function() {
                console.error('Failed to fetch market price');
            }
        });
    }

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

    // Auto hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000);

    // Remove invalid class when user types in any required field
    $('input[required], textarea[required], select[required]').on('input change', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });



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
        let missingFields = [];



        requiredFields.forEach(function(field) {
            let input = $(`[name="${field}"]`);

            // Get value with special handling for textarea
            let value;
            if (field === 'description') {
                // For description textarea, use native DOM method as backup
                let textarea = $('textarea[name="description"]').first();
                value = textarea.val();

                // If jQuery val() returns empty, try native DOM value
                if (!value || value.trim() === '') {
                    if (textarea[0]) {
                        value = textarea[0].value || textarea[0].textContent || '';
                    }
                }
            } else {
                value = input.val();
            }

            // Trim whitespace for validation
            if (input.is('textarea')) {
                // For textarea, also check if it contains only whitespace/newlines
                value = value ? value.replace(/\s+/g, ' ').trim() : '';
            } else {
                // For other inputs, normal trim
                value = value ? value.trim() : '';
            }

            // Check if empty after processing
            if (!value || value === '') {
                input.addClass('is-invalid');
                isValid = false;

                // Get field label for better error message
                let label = input.closest('.form-group').find('label').first().text().replace('*', '').trim();
                if (label) {
                    missingFields.push(label);
                } else {
                    missingFields.push(field);
                }
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

        if (!isValid) {
            e.preventDefault();

            // Focus on first invalid field
            let firstInvalid = $('.is-invalid').first();
            if (firstInvalid.length) {
                firstInvalid.focus();
                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100
                }, 500);
            }

            alert('Vui lòng điền đầy đủ thông tin bắt buộc: ' + missingFields.join(', '));
        }
    });

    // Remove current image with confirmation
    $(document).on('click', '.remove-current-image', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Show confirmation dialog
        if (!confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
            return;
        }

        const index = $(this).data('index');
        const $slot = $(this).closest('.current-image-slot');

        // Mark image for removal
        $slot.find('.remove-flag').val('1');

        // Convert to empty slot
        $slot.removeClass('has-image current-image-slot clickable-image')
             .addClass('empty-slot')
             .removeAttr('style')
             .removeAttr('title')
             .html(`
                <div class="fu-text">
                    <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                </div>
                <input class="upload" type="file" name="new_images[]" accept="image/*">
             `);

        updateImageSlots();
    });

    // Click on image to change
    $(document).on('click', '.clickable-image', function(e) {
        // Don't trigger if clicking on remove button
        if ($(e.target).hasClass('remove-current-image') || $(e.target).closest('.remove-current-image').length) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        console.log('Clicked on image to change'); // Debug

        const index = $(this).data('index');
        const $fileInput = $(this).find('.upload-replace');

        console.log('File input found:', $fileInput.length); // Debug

        if ($fileInput.length > 0) {
            $fileInput[0].click(); // Use native click
        } else {
            console.error('File input not found');
        }
    });

    // Handle replace image upload
    $(document).on('change', '.upload-replace', function() {
        const $this = $(this);
        const files = this.files;
        const index = $this.data('index');
        const $slot = $this.closest('.current-image-slot');

        console.log('File input changed, files:', files.length); // Debug

        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                if (file.size > 5242880) {
                    alert('Kích thước file quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
                    $this.val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Image loaded, updating preview'); // Debug

                    // Update preview image
                    $slot.find('.preview-image').attr('src', e.target.result);

                    // Mark original image for removal
                    $slot.find('.remove-flag').val('1');

                    // Remove existing new-image-input if any
                    $slot.find('.new-image-input').remove();

                    // Add hidden input for new image
                    $slot.append(`<input type="file" name="new_images[]" class="new-image-input" style="display:none;">`);

                    // Transfer file to new input
                    try {
                        const newInput = $slot.find('.new-image-input')[0];
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        newInput.files = dt.files;
                        console.log('File transferred successfully'); // Debug
                    } catch (error) {
                        console.error('Error transferring file:', error);
                    }
                };
                reader.readAsDataURL(file);
            } else {
                alert('Vui lòng chọn file ảnh (JPG, PNG, GIF)!');
                $this.val('');
            }
        }
    });

    // Update image slots to maintain max 3 images
    function updateImageSlots() {
        const $manager = $('#imageManager');
        const currentImages = $manager.find('.current-image-slot:not(.remove-flag[value="1"])').length;
        const uploadedImages = $manager.find('.fuzone.has-image:not(.current-image-slot)').length;
        const totalImages = currentImages + uploadedImages;
        const maxImages = 3;
        const remainingSlots = maxImages - totalImages;

        // Remove excess empty slots
        const $emptySlots = $manager.find('.empty-slot');
        $emptySlots.each(function(index) {
            if (index >= remainingSlots) {
                $(this).closest('.col-md-4').remove();
            }
        });

        // Add missing empty slots
        for (let i = $emptySlots.length; i < remainingSlots; i++) {
            $manager.append(`
                <div class="col-md-4 mb-2">
                    <div class="fuzone empty-slot">
                        <div class="fu-text">
                            <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
                        </div>
                        <input class="upload" type="file" name="new_images[]" accept="image/*">
                    </div>
                </div>
            `);
        }
    }

    // File upload handling for new images
    $(document).on('change', '.upload', function() {
        var $this = $(this);
        var files = this.files;
        var $fuzone = $this.closest('.fuzone');

        // Check if we've reached the limit
        const totalImages = $('#imageManager .fuzone.has-image').length;
        if (totalImages >= 3) {
            alert('Tối đa chỉ được tải lên 3 ảnh!');
            $this.val('');
            return;
        }

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
                    $fuzone.removeClass('empty-slot').addClass('has-image').html(`
                        <div class="preview-container">
                            <img src="${e.target.result}" class="preview-image" alt="Preview">
                            <button type="button" class="remove-image" title="Xóa ảnh" onclick="return false;">
                                ×
                            </button>
                        </div>
                        <input class="upload-hidden" type="file" name="new_images[]" accept="image/*" style="display: none;">
                    `);

                    // Đặt lại file cho input mới
                    var newInput = $fuzone.find('.upload-hidden')[0];
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    newInput.files = dt.files;

                    // Update slots after adding new image
                    updateImageSlots();
                };
                reader.readAsDataURL(file);
            } else {
                alert('Vui lòng chọn file ảnh (JPG, PNG, GIF)!');
                $this.val(''); // Clear the input
            }
        }
    });

    // Remove new uploaded image
    $(document).on('click', '.remove-image, .remove-image *', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var $button = $(this).hasClass('remove-image') ? $(this) : $(this).closest('.remove-image');
        var $fuzone = $button.closest('.fuzone');

        // Reset to empty slot state
        $fuzone.removeClass('has-image').addClass('empty-slot').html(`
            <div class="fu-text">
                <span><i class="mdi mdi-image-area"></i> Nhấn hoặc kéo thả để up hình phế liệu</span>
            </div>
            <input class="upload" type="file" name="new_images[]" accept="image/*">
        `);

        // Update slots after removing image
        updateImageSlots();

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
});
</script>
@endsection
