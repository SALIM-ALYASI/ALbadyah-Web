@extends('layouts.app')

@section('title', 'تفاصيل الموقع السياحي - ' . $touristSite->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        تفاصيل الموقع السياحي
                    </h3>
                    <div>
                        <a href="{{ route('tourist-sites-new.edit', $touristSite->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i>
                            تعديل
                        </a>
                        <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- المعلومات الأساسية -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">المعلومات الأساسية</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الاسم بالعربية</h6>
                                            <p class="mb-3">{{ $touristSite->name_ar }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الاسم بالإنجليزية</h6>
                                            <p class="mb-3">{{ $touristSite->name_en }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الوصف بالعربية</h6>
                                            <p class="mb-3">{{ $touristSite->description_ar }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الوصف بالإنجليزية</h6>
                                            <p class="mb-3">{{ $touristSite->description_en }}</p>
                                        </div>
                                    </div>

                                    @if($touristSite->location)
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="text-primary">الموقع الجغرافي</h6>
                                                <p class="mb-3">{{ $touristSite->location }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- معلومات الاتصال -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($touristSite->website_url)
                                                <h6 class="text-primary">رابط الموقع</h6>
                                                <p class="mb-3">
                                                    <a href="{{ $touristSite->website_url }}" target="_blank" class="text-decoration-none">
                                                        {{ $touristSite->website_url }}
                                                        <i class="fas fa-external-link-alt ml-1"></i>
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($touristSite->phone)
                                                <h6 class="text-primary">رقم الهاتف</h6>
                                                <p class="mb-3">
                                                    <a href="tel:{{ $touristSite->phone }}" class="text-decoration-none">
                                                        {{ $touristSite->phone }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($touristSite->email)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary">البريد الإلكتروني</h6>
                                                <p class="mb-3">
                                                    <a href="mailto:{{ $touristSite->email }}" class="text-decoration-none">
                                                        {{ $touristSite->email }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- الإحداثيات -->
                                    @if($touristSite->latitude && $touristSite->longitude)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary">خط العرض</h6>
                                                <p class="mb-3">{{ $touristSite->latitude }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary">خط الطول</h6>
                                                <p class="mb-3">{{ $touristSite->longitude }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- المحافظة والولاية -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">المحافظة</h6>
                                            <p class="mb-3">
                                                @if($touristSite->governorate)
                                                    <span class="badge badge-info">{{ $touristSite->governorate->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الولاية</h6>
                                            <p class="mb-3">
                                                @if($touristSite->wilayat)
                                                    <span class="badge badge-success">{{ $touristSite->wilayat->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- الحالة -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">الحالة</h6>
                                            <p class="mb-3">
                                                @if($touristSite->is_active)
                                                    <span class="badge badge-success">نشط</span>
                                                @else
                                                    <span class="badge badge-danger">غير نشط</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">تاريخ الإنشاء</h6>
                                            <p class="mb-3">{{ $touristSite->created_at->format('Y-m-d H:i:s') }}</p>
                                        </div>
                                    </div>

                                    <!-- SEO -->
                                    @if($touristSite->meta_title_ar || $touristSite->meta_title_en)
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="text-primary">إعدادات SEO</h6>
                                                <div class="row">
                                                    @if($touristSite->meta_title_ar)
                                                        <div class="col-md-6">
                                                            <strong>عنوان SEO (عربي):</strong>
                                                            <p class="mb-2">{{ $touristSite->meta_title_ar }}</p>
                                                        </div>
                                                    @endif
                                                    @if($touristSite->meta_title_en)
                                                        <div class="col-md-6">
                                                            <strong>عنوان SEO (إنجليزي):</strong>
                                                            <p class="mb-2">{{ $touristSite->meta_title_en }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    @if($touristSite->meta_description_ar)
                                                        <div class="col-md-6">
                                                            <strong>وصف SEO (عربي):</strong>
                                                            <p class="mb-2">{{ $touristSite->meta_description_ar }}</p>
                                                        </div>
                                                    @endif
                                                    @if($touristSite->meta_description_en)
                                                        <div class="col-md-6">
                                                            <strong>وصف SEO (إنجليزي):</strong>
                                                            <p class="mb-2">{{ $touristSite->meta_description_en }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- الصورة المميزة -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">الصورة المميزة</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($touristSite->featured_image)
                                        <img src="{{ $touristSite->featured_image }}" alt="{{ $touristSite->name_ar }}" 
                                             class="img-fluid rounded" style="max-height: 300px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <div class="text-center">
                                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">لا توجد صورة مميزة</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الصور الإضافية -->
                    @if($touristSite->images->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">الصور الإضافية ({{ $touristSite->images->count() }})</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="image-gallery">
                                            @foreach($touristSite->images as $image)
                                                <div class="col-md-3 mb-3" data-image-id="{{ $image->id }}">
                                                    <div class="card">
                                                        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text_ar }}" 
                                                             class="card-img-top" style="height: 200px; object-fit: cover;">
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
                        <div>
                            <small class="text-muted">
                                آخر تحديث: {{ $touristSite->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('tourist-sites-new.edit', $touristSite->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i>
                                تعديل
                            </a>
                            <form action="{{ route('tourist-sites-new.destroy', $touristSite->id) }}" 
                                  method="POST" style="display: inline-block;" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-1"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // يمكن إضافة جافا سكريبت لترتيب الصور أو عرضها في معرض
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثير hover للصور
        const images = document.querySelectorAll('#image-gallery img');
        images.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                // يمكن إضافة modal لعرض الصورة بحجم كبير
                console.log('Image clicked:', this.src);
            });
        });
    });
</script>
@endpush
