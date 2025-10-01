@extends('layouts.app')

@section('title', 'إضافة موقع سياحي جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        إضافة موقع سياحي جديد
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <form action="{{ route('tourist-sites-new.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                                   value="{{ old('name_ar') }}" required>
                                            @error('name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="name_en">اسم الموقع (إنجليزي) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_en" id="name_en" 
                                                   class="form-control @error('name_en') is-invalid @enderror" 
                                                   value="{{ old('name_en') }}" required>
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_ar">الوصف (عربي) <span class="text-danger">*</span></label>
                                            <textarea name="description_ar" id="description_ar" rows="4" 
                                                      class="form-control @error('description_ar') is-invalid @enderror" required>{{ old('description_ar') }}</textarea>
                                            @error('description_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description_en">الوصف (إنجليزي) <span class="text-danger">*</span></label>
                                            <textarea name="description_en" id="description_en" rows="4" 
                                                      class="form-control @error('description_en') is-invalid @enderror" required>{{ old('description_en') }}</textarea>
                                            @error('description_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="location">الموقع الجغرافي</label>
                                            <input type="text" name="location" id="location" 
                                                   class="form-control @error('location') is-invalid @enderror" 
                                                   value="{{ old('location') }}">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الاتصال والموقع -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">معلومات الاتصال والموقع</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="website_url">رابط الموقع</label>
                                            <input type="url" name="website_url" id="website_url" 
                                                   class="form-control @error('website_url') is-invalid @enderror" 
                                                   value="{{ old('website_url') }}">
                                            @error('website_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">رقم الهاتف</label>
                                            <input type="text" name="phone" id="phone" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">البريد الإلكتروني</label>
                                            <input type="email" name="email" id="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="latitude">خط العرض</label>
                                                    <input type="number" name="latitude" id="latitude" step="any" 
                                                           class="form-control @error('latitude') is-invalid @enderror" 
                                                           value="{{ old('latitude') }}">
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="longitude">خط الطول</label>
                                                    <input type="number" name="longitude" id="longitude" step="any" 
                                                           class="form-control @error('longitude') is-invalid @enderror" 
                                                           value="{{ old('longitude') }}">
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                                       class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    الموقع نشط
                                                </label>
                                            </div>
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
                                                            {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
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
                            </div>

                            <!-- الصور -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">الصور</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="featured_image">الصورة المميزة</label>
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
                                            <label for="images">صور إضافية</label>
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

                        <!-- SEO -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">إعدادات SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="meta_title_ar">عنوان SEO (عربي)</label>
                                                    <input type="text" name="meta_title_ar" id="meta_title_ar" 
                                                           class="form-control @error('meta_title_ar') is-invalid @enderror" 
                                                           value="{{ old('meta_title_ar') }}">
                                                    @error('meta_title_ar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="meta_title_en">عنوان SEO (إنجليزي)</label>
                                                    <input type="text" name="meta_title_en" id="meta_title_en" 
                                                           class="form-control @error('meta_title_en') is-invalid @enderror" 
                                                           value="{{ old('meta_title_en') }}">
                                                    @error('meta_title_en')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="meta_description_ar">وصف SEO (عربي)</label>
                                                    <textarea name="meta_description_ar" id="meta_description_ar" rows="3" 
                                                              class="form-control @error('meta_description_ar') is-invalid @enderror">{{ old('meta_description_ar') }}</textarea>
                                                    @error('meta_description_ar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="meta_description_en">وصف SEO (إنجليزي)</label>
                                                    <textarea name="meta_description_en" id="meta_description_en" rows="3" 
                                                              class="form-control @error('meta_description_en') is-invalid @enderror">{{ old('meta_description_en') }}</textarea>
                                                    @error('meta_description_en')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                حفظ الموقع السياحي
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
        
        // إعادة تعيين قيمة الولاية
        wilayatSelect.value = '';
    });
</script>
@endpush
