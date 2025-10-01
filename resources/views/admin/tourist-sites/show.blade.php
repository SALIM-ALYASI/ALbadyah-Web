@extends('layouts.app')

@section('title', 'تفاصيل الموقع السياحي - ' . $touristSite->name_ar)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark">
                        <i class="fas fa-map-marked-alt text-primary mr-2"></i>
                        تفاصيل الموقع السياحي
                    </h1>
                    <p class="text-muted mb-0">{{ $touristSite->name_ar }}</p>
                </div>
                <div>
                    <a href="{{ route('tourist-sites.edit', $touristSite->id) }}" class="btn btn-warning btn-lg mr-2">
                        <i class="fas fa-edit mr-2"></i>
                        تعديل
                    </a>
                    <a href="{{ route('tourist-sites.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-right mr-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    الاسم بالعربية
                                </h6>
                                <p class="mb-0">{{ $touristSite->name_ar }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    الاسم بالإنجليزية
                                </h6>
                                <p class="mb-0">{{ $touristSite->name_en }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-align-right mr-1"></i>
                                    الوصف بالعربية
                                </h6>
                                <p class="mb-0">{{ $touristSite->description_ar }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-align-left mr-1"></i>
                                    الوصف بالإنجليزية
                                </h6>
                                <p class="mb-0">{{ $touristSite->description_en }}</p>
                            </div>
                        </div>
                    </div>

                    @if($touristSite->location)
                        <div class="info-item mb-4">
                            <h6 class="text-primary fw-bold mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                الموقع الجغرافي
                            </h6>
                            <p class="mb-0">{{ $touristSite->location }}</p>
                        </div>
                    @endif

                    @if($touristSite->website_url)
                        <div class="info-item mb-4">
                            <h6 class="text-primary fw-bold mb-2">
                                <i class="fas fa-globe mr-1"></i>
                                الموقع الإلكتروني
                            </h6>
                            <p class="mb-0">
                                <a href="{{ $touristSite->website_url }}" target="_blank" class="text-decoration-none">
                                    {{ $touristSite->website_url }}
                                    <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </p>
                        </div>
                    @endif

                    @if($touristSite->phone)
                        <div class="info-item mb-4">
                            <h6 class="text-primary fw-bold mb-2">
                                <i class="fas fa-phone mr-1"></i>
                                رقم الهاتف
                            </h6>
                            <p class="mb-0">
                                <a href="tel:{{ $touristSite->phone }}" class="text-decoration-none">
                                    {{ $touristSite->phone }}
                                </a>
                            </p>
                        </div>
                    @endif

                    @if($touristSite->email)
                        <div class="info-item mb-4">
                            <h6 class="text-primary fw-bold mb-2">
                                <i class="fas fa-envelope mr-1"></i>
                                البريد الإلكتروني
                            </h6>
                            <p class="mb-0">
                                <a href="mailto:{{ $touristSite->email }}" class="text-decoration-none">
                                    {{ $touristSite->email }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <!-- المحافظة والولاية -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-success fw-bold mb-2">
                                    <i class="fas fa-building mr-1"></i>
                                    المحافظة
                                </h6>
                                @if($touristSite->governorate)
                                    <span class="badge bg-success fs-6">{{ $touristSite->governorate->name_ar }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-success fw-bold mb-2">
                                    <i class="fas fa-map-pin mr-1"></i>
                                    الولاية
                                </h6>
                                @if($touristSite->wilayat)
                                    <span class="badge bg-success fs-6">{{ $touristSite->wilayat->name_ar }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- الحالة والتاريخ -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-info fw-bold mb-2">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    الحالة
                                </h6>
                                @if($touristSite->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-info fw-bold mb-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    تاريخ الإنشاء
                                </h6>
                                <p class="mb-0">{{ $touristSite->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصورة المميزة -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star mr-2"></i>
                        الصورة المميزة
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    @if($touristSite->featured_image)
                        <img src="{{ $touristSite->featured_image }}" alt="{{ $touristSite->name_ar }}" 
                             class="img-fluid rounded shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="text-muted">لا توجد صورة مميزة</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>
                        إحصائيات سريعة
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ strlen($touristSite->description_ar) }}</h4>
                                <small class="text-muted">حرف (عربي)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ strlen($touristSite->description_en) }}</h4>
                            <small class="text-muted">حرف (إنجليزي)</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-info mb-1">{{ $touristSite->images->count() }}</h4>
                            <small class="text-muted">صورة إضافية</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الصور الإضافية -->
    @if($touristSite->images->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images mr-2"></i>
                            الصور الإضافية ({{ $touristSite->images->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            @foreach($touristSite->images as $image)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card shadow-sm">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text_ar }}" 
                                             class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <small class="text-muted">
                                                <i class="fas fa-sort mr-1"></i>
                                                ترتيب: {{ $image->sort_order }}
                                            </small>
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

    <!-- أزرار الإجراءات -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                آخر تحديث: {{ $touristSite->updated_at->format('H:i:s d-m-Y') }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('tourist-sites.edit', $touristSite->id) }}" class="btn btn-warning btn-lg mr-2">
                                <i class="fas fa-edit mr-2"></i>
                                تعديل
                            </a>
                            <form action="{{ route('tourist-sites.destroy', $touristSite->id) }}" method="POST" 
                                  style="display: inline-block;" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-trash mr-2"></i>
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

.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #007bff;
}

.btn {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endsection
