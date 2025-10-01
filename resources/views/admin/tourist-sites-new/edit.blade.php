@extends('layouts.app')

@section('title', 'تعديل الموقع السياحي - ' . $touristSite->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        تعديل الموقع السياحي
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('tourist-sites-new.show', $touristSite->id) }}" class="btn btn-info">
                            <i class="fas fa-eye mr-1"></i>
                            عرض
                        </a>
                        <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <form action="{{ route('tourist-sites-new.update', $touristSite->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">المعلومات الأساسية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name_ar">اسم الموقع (عربي) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_ar" id="name_ar" 
                                                   class="form-control @error('name_ar') is-invalid @enderror" 
                                                   value="{{ old('name_ar', $touristSite->name_ar) }}" required>
                                            @error('name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="name_en">اسم الموقع (إنجليزي) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_en" id="name_en" 
                                                   class="form-control @error('name_en') is-invalid @enderror" 
                                                   value="{{ old('name_en', $touristSite->name_en) }}" required>
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_ar">الوصف (عربي) <span class="text-danger">*</span></label>
                                            <textarea name="description_ar" id="description_ar" rows="4" 
                                                      class="form-control @error('description_ar') is-invalid @enderror" required>{{ old('description_ar', $touristSite->description_ar) }}</textarea>
                                            @error('description_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_en">الوصف (إنجليزي) <span class="text-danger">*</span></label>
                                            <textarea name="description_en" id="description_en" rows="4" 
                                                      class="form-control @error('description_en') is-invalid @enderror" required>{{ old('description_en', $touristSite->description_en) }}</textarea>
                                            @error('description_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="location">الموقع الجغرافي</label>
                                            <input type="text" name="location" id="location" 
                                                   class="form-control @error('location') is-invalid @enderror" 
                                                   value="{{ old('location', $touristSite->location) }}">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <!-- المحافظة والولاية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">المحافظة والولاية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="governorate_id">المحافظة</label>
                                            <select name="governorate_id" id="governorate_id" 
                                                    class="form-control @error('governorate_id') is-invalid @enderror">
                                                <option value="">اختر المحافظة</option>
                                                @foreach($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}" 
                                                            {{ old('governorate_id', $touristSite->governorate_id) == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('governorate_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="wilayat_id">الولاية</label>
                                            <select name="wilayat_id" id="wilayat_id" 
                                                    class="form-control @error('wilayat_id') is-invalid @enderror">
                                                <option value="">اختر الولاية</option>
                                                @foreach($wilayats as $wilayat)
                                                    <option value="{{ $wilayat->id }}" 
                                                            data-governorate="{{ $wilayat->governorate_id }}"
                                                            {{ old('wilayat_id', $touristSite->wilayat_id) == $wilayat->id ? 'selected' : '' }}>
                                                        {{ $wilayat->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('wilayat_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                                       class="form-check-input" {{ old('is_active', $touristSite->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    الموقع نشط
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الصور -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">الصور</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- الصورة المميزة الحالية -->
                                        @if($touristSite->featured_image)
                                            <div class="form-group">
                                                <label>الصورة المميزة الحالية</label>
                                                <div class="text-center">
                                                    <img src="{{ $touristSite->featured_image }}" alt="{{ $touristSite->name_ar }}" 
                                                         class="img-thumbnail" style="max-height: 150px;">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="featured_image">
                                                {{ $touristSite->featured_image ? 'استبدال الصورة المميزة' : 'الصورة المميزة' }}
                                            </label>
                                            <input type="file" name="featured_image" id="featured_image" 
                                                   class="form-control @error('featured_image') is-invalid @enderror" 
                                                   accept="image/*">
                                            @error('featured_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                الصيغ المقبولة: JPEG, PNG, JPG, GIF. الحد الأقصى: 2MB
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="images">إضافة صور جديدة</label>
                                            <input type="file" name="images[]" id="images" 
                                                   class="form-control @error('images') is-invalid @enderror" 
                                                   accept="image/*" multiple>
                                            @error('images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                يمكن اختيار عدة صور في نفس الوقت
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الصور الحالية -->
                        @if($touristSite->images->count() > 0)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">الصور الحالية ({{ $touristSite->images->count() }})</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($touristSite->images as $image)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card">
                                                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text_ar }}" 
                                                                 class="card-img-top" style="height: 150px; object-fit: cover;">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <small class="text-muted">ترتيب: {{ $image->sort_order }}</small>
                                                                    <form action="{{ route('tourist-sites-new.images.destroy', [$touristSite->id, $image->id]) }}" 
                                                                          method="POST" style="display: inline-block;" 
                                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" title="حذف الصورة">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tourist-sites-new.show', $touristSite->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    });

    // تشغيل الحدث عند تحميل الصفحة لإظهار الولايات المناسبة
    document.addEventListener('DOMContentLoaded', function() {
        const governorateSelect = document.getElementById('governorate_id');
        if (governorateSelect.value) {
            governorateSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
