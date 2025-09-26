@extends('layouts.app')

@section('title', 'إضافة خدمات للموقع')
@section('page-title', 'إضافة خدمات للموقع')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">إضافة خدمات للموقع</h1>
        <p class="text-muted mb-0">أضف خدمات متعددة للموقع: {{ $location->name_ar }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tourist-services.show', $location->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i>
            عرض الموقع
        </a>
        <a href="{{ route('tourist-services.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<!-- Location Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    معلومات الموقع
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-1">{{ $location->name_ar }}</h6>
                        <p class="text-muted mb-1">{{ $location->name_en }}</p>
                        <div class="d-flex gap-3 text-sm">
                            @if($location->governorate)
                                <span><i class="fas fa-building me-1"></i>{{ $location->governorate->name_ar }}</span>
                            @endif
                            @if($location->wilayat)
                                <span><i class="fas fa-map-marked-alt me-1"></i>{{ $location->wilayat->name_ar }}</span>
                            @endif
                            @if($location->serviceType)
                                <span><i class="fas fa-tags me-1"></i>{{ $location->serviceType->name_ar }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($location->has_location_image)
                            <img src="{{ $location->location_image_url }}" 
                                 alt="{{ $location->name_ar }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-width: 100px; max-height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 80px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    إضافة خدمات جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tourist-services.store-services', $location->id) }}" method="POST" enctype="multipart/form-data" id="servicesForm">
                    @csrf
                    
                    <!-- Services Array Section -->
                    <div id="services-container">
                        <div class="service-item border rounded p-3 mb-3" data-service-index="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-concierge-bell me-2"></i>
                                    الخدمة رقم <span class="service-number">1</span>
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-service" style="display: none;">
                                    <i class="fas fa-trash"></i>
                                    حذف الخدمة
                                </button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-language me-1 text-primary"></i>
                                        اسم الخدمة بالعربية *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="services[0][name_ar]" 
                                           placeholder="أدخل اسم الخدمة بالعربية"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-language me-1 text-primary"></i>
                                        اسم الخدمة بالإنجليزية *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="services[0][name_en]" 
                                           placeholder="Enter service name in English"
                                           required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-tags me-1 text-primary"></i>
                                        نوع الخدمة (اختياري)
                                    </label>
                                    <select class="form-control" name="services[0][service_type_id]">
                                        <option value="">اختر نوع الخدمة (اختياري)</option>
                                        @foreach($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->name_ar }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        نوع الخدمة اختياري - يمكنك تركه فارغاً
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-globe me-1 text-primary"></i>
                                        رابط الموقع الرسمي
                                    </label>
                                    <input type="url" 
                                           class="form-control" 
                                           name="services[0][website_url]" 
                                           placeholder="https://example.com">
                                </div>
                            </div>
                            
                            <!-- Service Image Section -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-upload me-1 text-primary"></i>
                                        رفع صورة الخدمة
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           name="services[0][image_file]" 
                                           accept="image/*"
                                           onchange="previewServiceImage(this, 0)">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        يمكنك رفع صورة للخدمة (JPG, PNG, GIF - الحد الأقصى 2MB)
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-link me-1 text-primary"></i>
                                        أو رابط صورة الخدمة
                                    </label>
                                    <input type="url" 
                                           class="form-control" 
                                           name="services[0][image_url]" 
                                           placeholder="https://example.com/service-image.jpg"
                                           onchange="previewServiceImageUrl(this, 0)">
                                </div>
                            </div>
                            
                            <!-- Service Image Preview -->
                            <div id="service_image_preview_container_0" class="mb-3" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-eye me-1 text-primary"></i>
                                    معاينة صورة الخدمة
                                </label>
                                <div class="text-center">
                                    <img id="service_image_preview_0" src="" alt="معاينة صورة الخدمة" 
                                         class="img-fluid rounded shadow" 
                                         style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Service Button -->
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-outline-primary" id="addServiceBtn">
                            <i class="fas fa-plus"></i>
                            إضافة خدمة أخرى
                        </button>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('tourist-services.show', $location->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ الخدمات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let serviceCount = 1;
    
    // Add new service
    document.getElementById('addServiceBtn').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const newServiceHtml = `
            <div class="service-item border rounded p-3 mb-3" data-service-index="${serviceCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fas fa-concierge-bell me-2"></i>
                        الخدمة رقم <span class="service-number">${serviceCount + 1}</span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-service">
                        <i class="fas fa-trash"></i>
                        حذف الخدمة
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-language me-1 text-primary"></i>
                            اسم الخدمة بالعربية *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="services[${serviceCount}][name_ar]" 
                               placeholder="أدخل اسم الخدمة بالعربية"
                               required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-language me-1 text-primary"></i>
                            اسم الخدمة بالإنجليزية *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="services[${serviceCount}][name_en]" 
                               placeholder="Enter service name in English"
                               required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-tags me-1 text-primary"></i>
                            نوع الخدمة (اختياري)
                        </label>
                        <select class="form-control" name="services[${serviceCount}][service_type_id]">
                            <option value="">اختر نوع الخدمة (اختياري)</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}">{{ $serviceType->name_ar }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            نوع الخدمة اختياري - يمكنك تركه فارغاً
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-globe me-1 text-primary"></i>
                            رابط الموقع الرسمي
                        </label>
                        <input type="url" 
                               class="form-control" 
                               name="services[${serviceCount}][website_url]" 
                               placeholder="https://example.com">
                    </div>
                </div>
                
                <!-- Service Image Section -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-upload me-1 text-primary"></i>
                            رفع صورة الخدمة
                        </label>
                        <input type="file" 
                               class="form-control" 
                               name="services[${serviceCount}][image_file]" 
                               accept="image/*"
                               onchange="previewServiceImage(this, ${serviceCount})">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            يمكنك رفع صورة للخدمة (JPG, PNG, GIF - الحد الأقصى 2MB)
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-link me-1 text-primary"></i>
                            أو رابط صورة الخدمة
                        </label>
                        <input type="url" 
                               class="form-control" 
                               name="services[${serviceCount}][image_url]" 
                               placeholder="https://example.com/service-image.jpg"
                               onchange="previewServiceImageUrl(this, ${serviceCount})">
                    </div>
                </div>
                
                <!-- Service Image Preview -->
                <div id="service_image_preview_container_${serviceCount}" class="mb-3" style="display: none;">
                    <label class="form-label">
                        <i class="fas fa-eye me-1 text-primary"></i>
                        معاينة صورة الخدمة
                    </label>
                    <div class="text-center">
                        <img id="service_image_preview_${serviceCount}" src="" alt="معاينة صورة الخدمة" 
                             class="img-fluid rounded shadow" 
                             style="max-width: 200px; max-height: 150px; object-fit: cover;">
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newServiceHtml);
        serviceCount++;
        
        // Show remove buttons for all services
        document.querySelectorAll('.remove-service').forEach(btn => {
            btn.style.display = 'inline-block';
        });
    });
    
    // Remove service
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            const serviceItem = e.target.closest('.service-item');
            serviceItem.remove();
            
            // Update service numbers
            updateServiceNumbers();
            
            // Hide remove buttons if only one service left
            const remainingServices = document.querySelectorAll('.service-item');
            if (remainingServices.length === 1) {
                document.querySelector('.remove-service').style.display = 'none';
            }
        }
    });
    
    // Update service numbers
    function updateServiceNumbers() {
        document.querySelectorAll('.service-item').forEach((item, index) => {
            const numberSpan = item.querySelector('.service-number');
            numberSpan.textContent = index + 1;
        });
    }
    
    // Preview service image from file
    function previewServiceImage(input, index) {
        const file = input.files[0];
        const preview = document.getElementById(`service_image_preview_container_${index}`);
        const img = document.getElementById(`service_image_preview_${index}`);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
    
    // Preview service image from URL
    function previewServiceImageUrl(input, index) {
        const url = input.value;
        const preview = document.getElementById(`service_image_preview_container_${index}`);
        const img = document.getElementById(`service_image_preview_${index}`);
        
        if (url && isValidUrl(url)) {
            img.src = url;
            preview.style.display = 'block';
            
            img.onload = function() {
                this.style.opacity = '1';
            };
            img.onerror = function() {
                preview.style.display = 'none';
                showAlert('خطأ في تحميل صورة الخدمة', 'danger');
            };
        } else {
            preview.style.display = 'none';
        }
    }
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alert, container.firstChild);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
    
    // Form validation
    document.getElementById('servicesForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<span class="loading"></span> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Reset button after 3 seconds (in case of validation errors)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Auto-focus on first input
    document.querySelector('input[name="services[0][name_ar]"]').focus();
</script>
@endpush
@endsection
