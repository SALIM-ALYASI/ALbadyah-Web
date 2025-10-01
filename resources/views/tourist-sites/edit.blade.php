@extends('layouts.app')

@section('title', 'تعديل الموقع السياحي - ' . $touristSite->name_ar)
@section('page-title', 'تعديل الموقع السياحي')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تعديل الموقع السياحي</h1>
        <p class="text-muted mb-0">تعديل بيانات الموقع السياحي: {{ $touristSite->name_ar }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tourist-sites.show', $touristSite->id) }}" class="btn btn-success">
            <i class="fas fa-eye"></i>
            عرض التفاصيل
        </a>
        <a href="{{ route('tourist-sites.index') }}" class="btn btn-secondary">
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
                    تعديل بيانات الموقع السياحي
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tourist-sites.update', $touristSite->id) }}" method="POST" id="editForm">
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
                                   value="{{ old('name_ar', $touristSite->name_ar) }}" 
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
                                   value="{{ old('name_en', $touristSite->name_en) }}" 
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
                                            {{ (old('governorate_id', $touristSite->governorate_id) == $governorate->id) ? 'selected' : '' }}>
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
                                            {{ (old('wilayat_id', $touristSite->wilayat_id) == $wilayat->id) ? 'selected' : '' }}>
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
                               value="{{ old('location', $touristSite->location) }}" 
                               placeholder="أدخل الموقع الجغرافي">
                        @error('location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
                                   value="{{ old('website_url', $touristSite->website_url) }}" 
                                   placeholder="https://example.com">
                        </div>
                        @error('website_url')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
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
                                  required>{{ old('description_ar', $touristSite->description_ar) }}</textarea>
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
                                  required>{{ old('description_en', $touristSite->description_en) }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('tourist-sites.show', $touristSite->id) }}" class="btn btn-secondary">
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
    
    <!-- Images Management -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-images me-2"></i>
                    إدارة الصور
                </h6>
            </div>
            <div class="card-body">
                @if($touristSite->images->count() > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">الصور الحالية ({{ $touristSite->images->count() }})</span>
                            <a href="{{ route('tourist-sites.show', $touristSite->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-cog"></i>
                                إدارة الصور
                            </a>
                        </div>
                        <div class="row">
                            @foreach($touristSite->images->take(4) as $image)
                            <div class="col-6 mb-2">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $touristSite->name_ar }}" 
                                     class="img-fluid rounded shadow" 
                                     style="width: 100%; height: 60px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if($touristSite->images->count() > 4)
                            <small class="text-muted">و {{ $touristSite->images->count() - 4 }} صورة أخرى...</small>
                        @endif
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-image fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-3">لا توجد صور للموقع السياحي</p>
                        <a href="{{ route('tourist-sites.show', $touristSite->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            إضافة صور
                        </a>
                    </div>
                @endif
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>يمكنك إدارة الصور من صفحة عرض الموقع السياحي</small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
