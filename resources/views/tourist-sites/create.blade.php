@extends('layouts.app')

@section('title', 'إضافة موقع سياحي جديد')
@section('page-title', 'إضافة موقع سياحي جديد')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">إضافة موقع سياحي جديد</h1>
        <p class="text-muted mb-0">أدخل بيانات الموقع السياحي الجديد في النموذج أدناه</p>
    </div>
    <a href="{{ route('tourist-sites.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i>
        العودة للقائمة
    </a>
</div>

<div class="row">
    <!-- Main Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-camera me-2"></i>
                    بيانات الموقع السياحي الجديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tourist-sites.store') }}" method="POST" enctype="multipart/form-data" id="touristSiteForm">
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
                                   placeholder="أدخل اسم الموقع السياحي بالعربية"
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
                                   placeholder="Enter tourist site name in English"
                                   required>
                            @error('name_en')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
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
                        
                        <div class="col-md-6 mb-4">
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
                    
                    <div class="mb-4">
                        <label for="location" class="form-label">
                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                            الموقع الجغرافي
                        </label>
                        <input type="text" 
                               class="form-control @error('location') is-invalid @enderror" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}" 
                               placeholder="أدخل الموقع الجغرافي">
                        @error('location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            أدخل الموقع الجغرافي التفصيلي للموقع السياحي
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
                            أدخل الرابط الكامل للموقع الرسمي للموقع السياحي
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description_ar" class="form-label">
                            <i class="fas fa-align-right me-1 text-primary"></i>
                            الوصف بالعربية *
                        </label>
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                  id="description_ar" 
                                  name="description_ar" 
                                  rows="4" 
                                  placeholder="أدخل وصف الموقع السياحي بالعربية"
                                  required>{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description_en" class="form-label">
                            <i class="fas fa-align-left me-1 text-primary"></i>
                            الوصف بالإنجليزية *
                        </label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" 
                                  name="description_en" 
                                  rows="4" 
                                  placeholder="Enter tourist site description in English"
                                  required>{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- الصورة المميزة -->
                    <div class="mb-4">
                        <label for="featured_image" class="form-label">
                            <i class="fas fa-image me-1 text-primary"></i>
                            الصورة المميزة
                        </label>
                        <input type="file" 
                               class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" 
                               name="featured_image" 
                               accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            اختر صورة مميزة للموقع السياحي (اختياري)
                        </div>
                    </div>

                    <!-- الصور الإضافية -->
                    <div class="mb-4">
                        <label for="images" class="form-label">
                            <i class="fas fa-images me-1 text-primary"></i>
                            الصور الإضافية
                        </label>
                        <input type="file" 
                               class="form-control @error('images') is-invalid @enderror" 
                               id="images" 
                               name="images[]" 
                               accept="image/*"
                               multiple>
                        @error('images')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            يمكنك اختيار عدة صور للموقع السياحي (اختياري)
                        </div>
                    </div>

                    <!-- حالة التفعيل -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-toggle-on me-1 text-success"></i>
                                تفعيل الموقع السياحي
                            </label>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            المواقع المفعلة ستظهر للزوار
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('tourist-sites.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ الموقع السياحي
                        </button>
                    </div>
                </form>

                <!-- JavaScript للتحقق من إرسال الـ form -->
                <script>
                document.getElementById('touristSiteForm').addEventListener('submit', function(e) {
                    console.log('Form is being submitted...');
                    console.log('Form data:', new FormData(this));
                    
                    // إظهار رسالة تحميل
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
                    submitBtn.disabled = true;
                });
                </script>
            </div>
        </div>
    </div>
    
    <!-- Info Section -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات إضافية
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>نصيحة:</strong> بعد حفظ الموقع السياحي، يمكنك إضافة الصور من صفحة عرض الموقع.
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted">الخطوات التالية:</h6>
                    <ol class="small text-muted">
                        <li>احفظ بيانات الموقع الأساسية</li>
                        <li>انتقل لصفحة عرض الموقع</li>
                        <li>أضف الصور من قسم إدارة الصور</li>
                    </ol>
                </div>
                
                <div class="form-text">
                    <i class="fas fa-images me-1"></i>
                    يمكنك إضافة صور متعددة للموقع السياحي بعد الحفظ
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('touristSiteForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Auto-focus on first input
        document.getElementById('name_ar').focus();
        
        // Form validation and submission
        form.addEventListener('submit', function(e) {
            const nameAr = document.getElementById('name_ar').value.trim();
            const nameEn = document.getElementById('name_en').value.trim();
            const descAr = document.getElementById('description_ar').value.trim();
            const descEn = document.getElementById('description_en').value.trim();
            
            // Validate required fields
            if (!nameAr || !nameEn || !descAr || !descEn) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
                return false;
            }
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading"></span> جاري الحفظ...';
            submitBtn.disabled = true;
            
            // Reset button after 10 seconds (in case of server issues)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 10000);
        });
    });
</script>
@endpush
@endsection
