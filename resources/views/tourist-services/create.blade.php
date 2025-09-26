@extends('layouts.app')

@section('title', 'إضافة خدمة سياحية سريعة')
@section('page-title', 'إضافة خدمة سياحية سريعة')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">إضافة خدمة سياحية سريعة</h1>
        <p class="text-muted mb-0">أدخل بيانات الخدمة السياحية في النموذج أدناه</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tourist-services.create-location') }}" class="btn btn-primary">
            <i class="fas fa-map-marker-alt"></i>
            إضافة موقع خدمة جديد
        </a>
        <a href="{{ route('tourist-services.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-concierge-bell me-2"></i>
                    بيانات الخدمة السياحية
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tourist-services.store') }}" method="POST" id="serviceForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="name_ar" class="form-label">
                                <i class="fas fa-language me-1 text-primary"></i>
                                الاسم بالعربية *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name_ar') is-invalid @enderror" 
                                   id="name_ar" 
                                   name="name_ar" 
                                   value="{{ old('name_ar') }}" 
                                   placeholder="أدخل اسم الخدمة السياحية بالعربية"
                                   required>
                            @error('name_ar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="name_en" class="form-label">
                                <i class="fas fa-language me-1 text-primary"></i>
                                الاسم بالإنجليزية *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name_en') is-invalid @enderror" 
                                   id="name_en" 
                                   name="name_en" 
                                   value="{{ old('name_en') }}" 
                                   placeholder="Enter tourist service name in English"
                                   required>
                            @error('name_en')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="service_type_id" class="form-label">
                                <i class="fas fa-tags me-1 text-primary"></i>
                                نوع الخدمة (اختياري)
                            </label>
                            <select class="form-control @error('service_type_id') is-invalid @enderror" 
                                    id="service_type_id" 
                                    name="service_type_id">
                                <option value="">اختر نوع الخدمة (اختياري)</option>
                                @foreach($serviceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}" 
                                            {{ old('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                        {{ $serviceType->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_type_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                نوع الخدمة اختياري - يمكنك تركه فارغاً
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label for="governorate_id" class="form-label">
                                <i class="fas fa-building me-1 text-primary"></i>
                                المحافظة
                            </label>
                            <select class="form-control @error('governorate_id') is-invalid @enderror" 
                                    id="governorate_id" 
                                    name="governorate_id">
                                <option value="">اختر المحافظة</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}" 
                                            {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                        {{ $governorate->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label for="wilayat_id" class="form-label">
                                <i class="fas fa-map-marked-alt me-1 text-primary"></i>
                                الولاية
                            </label>
                            <select class="form-control @error('wilayat_id') is-invalid @enderror" 
                                    id="wilayat_id" 
                                    name="wilayat_id">
                                <option value="">اختر الولاية</option>
                                @foreach($wilayats as $wilayat)
                                    <option value="{{ $wilayat->id }}" 
                                            {{ old('wilayat_id') == $wilayat->id ? 'selected' : '' }}>
                                        {{ $wilayat->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wilayat_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Website URL -->
                    <div class="mb-4">
                        <label for="website_url" class="form-label">
                            <i class="fas fa-globe me-1 text-primary"></i>
                            رابط الموقع الرسمي
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('website_url') is-invalid @enderror" 
                                   id="website_url" 
                                   name="website_url" 
                                   value="{{ old('website_url') }}" 
                                   placeholder="https://example.com">
                        </div>
                        @error('website_url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            أدخل الرابط الكامل للموقع الرسمي للخدمة السياحية
                        </div>
                    </div>
                    
                    <!-- Service Image -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-image me-2"></i>
                            صورة الخدمة
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-upload me-1 text-primary"></i>
                                    رفع صورة الخدمة
                                </label>
                                <input type="file" 
                                       class="form-control @error('image_file') is-invalid @enderror" 
                                       id="image_file" 
                                       name="image_file" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                @error('image_file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
                                       class="form-control @error('image_url') is-invalid @enderror" 
                                       id="image_url" 
                                       name="image_url" 
                                       value="{{ old('image_url') }}" 
                                       placeholder="https://example.com/service-image.jpg"
                                       onchange="previewImageUrl(this)">
                                @error('image_url')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    أدخل رابط صورة الخدمة من الإنترنت
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="image_preview_container" class="mb-3" style="display: none;">
                            <label class="form-label">
                                <i class="fas fa-eye me-1 text-primary"></i>
                                معاينة صورة الخدمة
                            </label>
                            <div class="text-center">
                                <img id="image_preview" src="" alt="معاينة صورة الخدمة" 
                                     class="img-fluid rounded shadow" 
                                     style="max-width: 300px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('tourist-services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ الخدمة السياحية
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image from file
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('image_preview_container');
        const img = document.getElementById('image_preview');
        
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
    
    // Preview image from URL
    function previewImageUrl(input) {
        const url = input.value;
        const preview = document.getElementById('image_preview_container');
        const img = document.getElementById('image_preview');
        
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
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
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
    document.getElementById('name_ar').focus();
</script>
@endpush
@endsection