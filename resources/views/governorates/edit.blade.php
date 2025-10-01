@extends('layouts.app')

@section('title', 'تعديل المحافظة - ' . $governorate->name_ar)
@section('page-title', 'تعديل المحافظة')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تعديل المحافظة</h1>
        <p class="text-muted mb-0">تعديل بيانات المحافظة: {{ $governorate->name_ar }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.governorates.show', $governorate->id) }}" class="btn btn-success">
            <i class="fas fa-eye"></i>
            عرض التفاصيل
        </a>
        <a href="{{ route('dashboard.governorates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- Edit Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    تعديل بيانات المحافظة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.governorates.update', $governorate->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                                   value="{{ old('name_ar', $governorate->name_ar) }}" 
                                   placeholder="أدخل اسم المحافظة بالعربية"
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
                                   value="{{ old('name_en', $governorate->name_en) }}" 
                                   placeholder="Enter governorate name in English"
                                   required>
                            @error('name_en')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
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
                                   value="{{ old('website_url', $governorate->website_url) }}" 
                                   placeholder="https://example.com">
                        </div>
                        @error('website_url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            أدخل الرابط الكامل للموقع الرسمي للمحافظة
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image_url" class="form-label">
                            <i class="fas fa-image me-1 text-primary"></i>
                            رابط صورة المحافظة (اختياري)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" 
                                   name="image_url" 
                                   value="{{ old('image_url', $governorate->image_url) }}" 
                                   placeholder="https://example.com/image.jpg">
                        </div>
                        @error('image_url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            أدخل رابط الصورة من الإنترنت (اختياري)
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label">
                            <i class="fas fa-upload me-1 text-success"></i>
                            رفع صورة جديدة (مفضل)
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            يمكن رفع ملف صورة (JPG, PNG, GIF) بحجم أقصى 2MB
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.governorates.show', $governorate->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-warning" id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Current Image & Preview -->
    <div class="col-lg-4">
        <!-- Current Image -->
        @if($governorate->image_path || $governorate->image_url)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>
                    الصورة الحالية
                </h6>
            </div>
            <div class="card-body text-center">
                @if($governorate->image_path)
                    <img src="{{ asset('storage/' . $governorate->image_path) }}" 
                         alt="{{ $governorate->name_ar }}" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 200px; max-width: 100%; object-fit: cover;">
                @elseif($governorate->image_url)
                    <img src="{{ $governorate->image_url }}" 
                         alt="{{ $governorate->name_ar }}" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 200px; max-width: 100%; object-fit: cover;">
                @endif
            </div>
        </div>
        @endif
        
        <!-- New Image Preview -->
        <div class="card" id="preview_card" style="display: none;">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    معاينة الصورة الجديدة
                </h6>
            </div>
            <div class="card-body text-center">
                <img id="image_preview" src="" alt="معاينة الصورة الجديدة" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 200px; max-width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value;
        const previewCard = document.getElementById('preview_card');
        const previewImage = document.getElementById('image_preview');
        
        if (url && isValidUrl(url)) {
            previewImage.src = url;
            previewCard.style.display = 'block';
            
            // Add loading state
            previewImage.style.opacity = '0.5';
            previewImage.onload = function() {
                this.style.opacity = '1';
            };
            previewImage.onerror = function() {
                previewCard.style.display = 'none';
                showAlert('خطأ في تحميل الصورة', 'danger');
            };
        } else {
            previewCard.style.display = 'none';
        }
    });

    // Image preview functionality for uploaded file
    document.getElementById('image').addEventListener('change', function() {
        const file = this.files[0];
        const previewCard = document.getElementById('preview_card');
        const previewImage = document.getElementById('image_preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewCard.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewCard.style.display = 'none';
        }
    });
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    // Form validation
    document.getElementById('editForm').addEventListener('submit', function(e) {
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
