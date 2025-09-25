@extends('layouts.app')

@section('title', 'إضافة ولاية جديدة')
@section('page-title', 'إضافة ولاية جديدة')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">إضافة ولاية جديدة</h1>
        <p class="text-muted mb-0">أدخل بيانات الولاية الجديدة في النموذج أدناه</p>
    </div>
    <a href="{{ route('wilayats.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i>
        العودة للقائمة
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    بيانات الولاية الجديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('wilayats.store') }}" method="POST" id="wilayatForm">
                    @csrf
                    
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
                                   placeholder="أدخل اسم الولاية بالعربية"
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
                                   placeholder="Enter wilayat name in English"
                                   required>
                            @error('name_en')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="governorate_id" class="form-label">
                            <i class="fas fa-building me-1 text-primary"></i>
                            المحافظة *
                        </label>
                        <select class="form-control @error('governorate_id') is-invalid @enderror" 
                                id="governorate_id" 
                                name="governorate_id" 
                                required>
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
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            اختر المحافظة التي تنتمي إليها هذه الولاية
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
                            أدخل الرابط الكامل للموقع الرسمي للولاية
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image_url" class="form-label">
                            <i class="fas fa-image me-1 text-primary"></i>
                            رابط صورة الولاية
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-image"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" 
                                   name="image_url" 
                                   value="{{ old('image_url') }}" 
                                   placeholder="https://example.com/image.jpg">
                        </div>
                        @error('image_url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            أدخل رابط الصورة التي تمثل الولاية
                        </div>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="image_preview_container" class="mb-4" style="display: none;">
                        <label class="form-label">
                            <i class="fas fa-eye me-1 text-primary"></i>
                            معاينة الصورة
                        </label>
                        <div class="text-center">
                            <img id="image_preview" src="" alt="معاينة الصورة" 
                                 class="img-fluid rounded shadow" 
                                 style="max-width: 300px; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('wilayats.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ الولاية
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value;
        const previewContainer = document.getElementById('image_preview_container');
        const previewImage = document.getElementById('image_preview');
        
        if (url && isValidUrl(url)) {
            previewImage.src = url;
            previewContainer.style.display = 'block';
            
            // Add loading state
            previewImage.style.opacity = '0.5';
            previewImage.onload = function() {
                this.style.opacity = '1';
            };
            previewImage.onerror = function() {
                previewContainer.style.display = 'none';
                showAlert('خطأ في تحميل الصورة', 'danger');
            };
        } else {
            previewContainer.style.display = 'none';
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
    document.getElementById('wilayatForm').addEventListener('submit', function(e) {
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
