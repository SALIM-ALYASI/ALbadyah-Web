@extends('layouts.app')

@section('title', 'إضافة موقع سياحي جديد')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark">
                        <i class="fas fa-plus-circle text-primary mr-2"></i>
                        إضافة موقع سياحي جديد
                    </h1>
                    <p class="text-muted mb-0">أضف موقعاً سياحياً جديداً إلى النظام</p>
                </div>
                <div>
                    <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right mr-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('tourist-sites-new.store') }}" method="POST" enctype="multipart/form-data" id="touristSiteForm">
        @csrf
        
        <div class="row">
            <!-- المعلومات الأساسية -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle mr-2"></i>
                            المعلومات الأساسية
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="name_ar" class="form-label fw-bold">
                                        <i class="fas fa-tag text-primary mr-1"></i>
                                        اسم الموقع (عربي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_ar" id="name_ar" 
                                           class="form-control form-control-lg @error('name_ar') is-invalid @enderror" 
                                           value="{{ old('name_ar') }}" 
                                           placeholder="أدخل اسم الموقع باللغة العربية" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="name_en" class="form-label fw-bold">
                                        <i class="fas fa-tag text-primary mr-1"></i>
                                        اسم الموقع (إنجليزي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_en" id="name_en" 
                                           class="form-control form-control-lg @error('name_en') is-invalid @enderror" 
                                           value="{{ old('name_en') }}" 
                                           placeholder="Enter site name in English" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="description_ar" class="form-label fw-bold">
                                <i class="fas fa-align-right text-primary mr-1"></i>
                                الوصف (عربي) <span class="text-danger">*</span>
                            </label>
                            <textarea name="description_ar" id="description_ar" rows="4" 
                                      class="form-control @error('description_ar') is-invalid @enderror" 
                                      placeholder="اكتب وصفاً مفصلاً للموقع باللغة العربية" required>{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="description_en" class="form-label fw-bold">
                                <i class="fas fa-align-left text-primary mr-1"></i>
                                الوصف (إنجليزي) <span class="text-danger">*</span>
                            </label>
                            <textarea name="description_en" id="description_en" rows="4" 
                                      class="form-control @error('description_en') is-invalid @enderror" 
                                      placeholder="Write a detailed description of the site in English" required>{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="location" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                                الموقع الجغرافي
                            </label>
                            <input type="text" name="location" id="location" 
                                   class="form-control form-control-lg @error('location') is-invalid @enderror" 
                                   value="{{ old('location') }}" 
                                   placeholder="مثال: وسط مدينة مسقط">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- المحافظة والولاية -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            المحافظة والولاية
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="governorate_id" class="form-label fw-bold">
                                        <i class="fas fa-building text-success mr-1"></i>
                                        المحافظة
                                    </label>
                                    <select name="governorate_id" id="governorate_id" 
                                            class="form-select form-select-lg @error('governorate_id') is-invalid @enderror">
                                        <option value="">اختر المحافظة</option>
                                        @foreach($governorates as $governorate)
                                            <option value="{{ $governorate->id }}" 
                                                    {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                {{ $governorate->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('governorate_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="wilayat_id" class="form-label fw-bold">
                                        <i class="fas fa-map-pin text-success mr-1"></i>
                                        الولاية
                                    </label>
                                    <select name="wilayat_id" id="wilayat_id" 
                                            class="form-select form-select-lg @error('wilayat_id') is-invalid @enderror">
                                        <option value="">اختر الولاية</option>
                                        @foreach($wilayats as $wilayat)
                                            <option value="{{ $wilayat->id }}" 
                                                    data-governorate="{{ $wilayat->governorate_id }}"
                                                    {{ old('wilayat_id') == $wilayat->id ? 'selected' : '' }}>
                                                {{ $wilayat->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wilayat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    <i class="fas fa-toggle-on text-success mr-1"></i>
                                    الموقع نشط
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الصور -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images mr-2"></i>
                            الصور
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label for="featured_image" class="form-label fw-bold">
                                <i class="fas fa-star text-warning mr-1"></i>
                                الصورة المميزة
                            </label>
                            <input type="file" name="featured_image" id="featured_image" 
                                   class="form-control @error('featured_image') is-invalid @enderror" 
                                   accept="image/*" onchange="previewImage(this, 'featured-preview')">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                الصيغ المقبولة: GIF, JPEG, PNG, JPG. الحد الأقصى: 2MB
                            </small>
                            <div id="featured-preview" class="mt-2" style="display: none;">
                                <img src="" alt="معاينة الصورة" class="img-thumbnail" style="max-width: 100%; height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="images" class="form-label fw-bold">
                                <i class="fas fa-images text-warning mr-1"></i>
                                صور إضافية
                            </label>
                            <input type="file" name="images[]" id="images" 
                                   class="form-control @error('images') is-invalid @enderror" 
                                   accept="image/*" multiple onchange="previewMultipleImages(this, 'images-preview')">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                يمكن اختيار عدة صور في نفس الوقت
                            </small>
                            <div id="images-preview" class="mt-2 row"></div>
                        </div>
                    </div>
                </div>

                <!-- إحصائيات سريعة -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar mr-2"></i>
                            معلومات سريعة
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1" id="char-count-ar">0</h4>
                                    <small class="text-muted">حرف (عربي)</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1" id="char-count-en">0</h4>
                                <small class="text-muted">حرف (إنجليزي)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times mr-2"></i>
                                إلغاء
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-lg mr-2" onclick="saveAsDraft()">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ كمسودة
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg" onclick="console.log('Form submitted');">
                                    <i class="fas fa-check mr-2"></i>
                                    حفظ الموقع السياحي
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#featured-preview img, #images-preview img {
    border-radius: 10px;
}

.preview-item {
    position: relative;
    margin-bottom: 10px;
}

.preview-item .remove-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 12px;
}
</style>
@endsection

@push('scripts')
<script>
// تحديث الولايات عند تغيير المحافظة
document.getElementById('governorate_id').addEventListener('change', function() {
    const governorateId = this.value;
    const wilayatSelect = document.getElementById('wilayat_id');
    const options = wilayatSelect.querySelectorAll('option');
    
    // إخفاء جميع الخيارات عدا الأول
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
    
    // إظهار الولايات التي تنتمي للمحافظة المحددة
    if (governorateId) {
        options.forEach(option => {
            if (option.dataset.governorate === governorateId) {
                option.style.display = 'block';
            }
        });
    } else {
        // إظهار جميع الولايات إذا لم تكن هناك محافظة محددة
        options.forEach(option => {
            option.style.display = 'block';
        });
    }
    
    // إعادة تعيين قيمة الولاية
    wilayatSelect.value = '';
});

// معاينة الصورة المميزة
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

// معاينة الصور المتعددة
function previewMultipleImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-6 preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="معاينة ${index + 1}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                <button type="button" class="remove-btn" onclick="removePreview(this, ${index})">×</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// إزالة معاينة الصورة
function removePreview(btn, index) {
    btn.parentElement.remove();
    // يمكن إضافة منطق لإزالة الملف من input إذا لزم الأمر
}

// عداد الأحرف
document.getElementById('description_ar').addEventListener('input', function() {
    document.getElementById('char-count-ar').textContent = this.value.length;
});

document.getElementById('description_en').addEventListener('input', function() {
    document.getElementById('char-count-en').textContent = this.value.length;
});

// حفظ كمسودة
function saveAsDraft() {
    // يمكن إضافة منطق لحفظ كمسودة
    alert('سيتم تطبيق هذه الميزة قريباً');
}

// التحقق من صحة النموذج
document.getElementById('touristSiteForm').addEventListener('submit', function(e) {
    const nameAr = document.getElementById('name_ar').value.trim();
    const nameEn = document.getElementById('name_en').value.trim();
    const descAr = document.getElementById('description_ar').value.trim();
    const descEn = document.getElementById('description_en').value.trim();
    
    if (!nameAr || !nameEn || !descAr || !descEn) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
        return false;
    }
});
</script>
@endpush