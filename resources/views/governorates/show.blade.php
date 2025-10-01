@extends('layouts.app')

@section('title', 'تفاصيل المحافظة - ' . $governorate->name_ar)
@section('page-title', 'تفاصيل المحافظة')

@section('content')
<!-- Header Section -->
<div class="governorate-header mb-4" style="
    background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
">
    <!-- تأثير زخرفي في الخلفية -->
    <div style="
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    "></div>
    <div style="
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        z-index: 1;
    "></div>
    
    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
        <div>
            <h1 class="h2 mb-2 fw-bold">{{ $governorate->name_ar }}</h1>
            <p class="mb-1 fs-5 opacity-90">{{ $governorate->name_en }}</p>
            <p class="mb-0 opacity-75">
                <i class="fas fa-building me-2"></i>
                محافظة عُمانية
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('governorates.edit', $governorate->id) }}" class="btn btn-warning btn-lg">
                <i class="fas fa-edit me-2"></i>
                تعديل
            </a>
            <a href="{{ route('governorates.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Info Card -->
    <div class="col-lg-8">
        <div class="card shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-header" style="
                background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
                color: white;
                border-radius: 20px 20px 0 0;
                border: none;
            ">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-building me-2"></i>
                    {{ $governorate->name_ar ?? 'غير محدد' }}
                </h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالعربية</h6>
                            </div>
                            <p class="fs-5 fw-bold mb-0">{{ $governorate->name_ar ?? 'غير محدد' }}</p>
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالإنجليزية</h6>
                            </div>
                            <p class="fs-5 mb-0">{{ $governorate->name_en ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-globe text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الموقع الرسمي</h6>
                            </div>
                            @if($governorate->website_url)
                                <a href="{{ $governorate->website_url }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    زيارة الموقع
                                </a>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-calendar text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">تاريخ الإنشاء</h6>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $governorate->created_at->format('Y-m-d') }}</span>
                                <small class="text-muted">{{ $governorate->created_at->format('H:i:s') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($governorate->has_image)
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="info-icon me-3">
                            <i class="fas fa-image text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">صورة المحافظة</h6>
                    </div>
                    <div class="text-center position-relative">
                        <!-- خلفية بنية فاتحة للصورة -->
                        <div class="governorate-image-container" style="
                            background: linear-gradient(135deg, #f5f1eb 0%, #e8ddd4 50%, #d4c4b0 100%);
                            border-radius: 20px;
                            padding: 20px;
                            box-shadow: 0 8px 25px rgba(97, 76, 57, 0.15);
                            position: relative;
                            overflow: hidden;
                        ">
                            <!-- تأثير زخرفي في الخلفية -->
                            <div style="
                                position: absolute;
                                top: -50px;
                                right: -50px;
                                width: 100px;
                                height: 100px;
                                background: rgba(97, 76, 57, 0.1);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            <div style="
                                position: absolute;
                                bottom: -30px;
                                left: -30px;
                                width: 60px;
                                height: 60px;
                                background: rgba(97, 76, 57, 0.08);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            
                            <!-- الصورة -->
                            <img src="{{ $governorate->image_url }}" 
                                 alt="{{ $governorate->name_ar }}" 
                                 class="img-fluid rounded shadow-lg"
                                 style="
                                    max-height: 400px; 
                                    max-width: 100%; 
                                    object-fit: cover;
                                    border-radius: 15px;
                                    position: relative;
                                    z-index: 2;
                                    transition: transform 0.3s ease;
                                 "
                                 onmouseover="this.style.transform='scale(1.02)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        </div>
                    </div>
                </div>
                @else
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="info-icon me-3">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                        <h6 class="mb-0 text-muted">صورة المحافظة</h6>
                    </div>
                    <div class="text-center">
                        <div class="governorate-image-container" style="
                            background: linear-gradient(135deg, #f5f1eb 0%, #e8ddd4 50%, #d4c4b0 100%);
                            border-radius: 20px;
                            padding: 40px;
                            box-shadow: 0 8px 25px rgba(97, 76, 57, 0.15);
                            position: relative;
                            overflow: hidden;
                            min-height: 250px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <!-- تأثير زخرفي في الخلفية -->
                            <div style="
                                position: absolute;
                                top: -50px;
                                right: -50px;
                                width: 100px;
                                height: 100px;
                                background: rgba(97, 76, 57, 0.1);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            <div style="
                                position: absolute;
                                bottom: -30px;
                                left: -30px;
                                width: 60px;
                                height: 60px;
                                background: rgba(97, 76, 57, 0.08);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            
                            <div class="text-center" style="position: relative; z-index: 2;">
                                <i class="fas fa-image fa-4x text-muted mb-3" style="opacity: 0.6;"></i>
                                <p class="text-muted mb-0 fs-5">لا توجد صورة متاحة</p>
                                <small class="text-muted">يمكن إضافة صورة من خلال تعديل المحافظة</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            آخر تحديث: {{ $governorate->updated_at->format('Y-m-d H:i:s') }}
                        </small>
                    </div>
                    <form action="{{ route('governorates.destroy', $governorate->id) }}" 
                          method="POST" 
                          style="display: inline;" 
                          onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            حذف المحافظة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Side Info Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات إضافية
                </h6>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hashtag text-primary me-2"></i>
                        <small class="text-muted">معرف المحافظة</small>
                    </div>
                    <span class="badge bg-primary fs-6">#{{ $governorate->id }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <small class="text-muted">تاريخ الإنشاء</small>
                    </div>
                    <span class="fw-medium">{{ $governorate->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-edit text-primary me-2"></i>
                        <small class="text-muted">آخر تحديث</small>
                    </div>
                    <span class="fw-medium">{{ $governorate->updated_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <small class="text-muted">الموقع الإلكتروني</small>
                    </div>
                    @if($governorate->website_url)
                        <span class="badge bg-success">متوفر</span>
                    @else
                        <span class="badge bg-secondary">غير متوفر</span>
                    @endif
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-image text-primary me-2"></i>
                        <small class="text-muted">الصورة</small>
                    </div>
                    @if($governorate->image_url)
                        <span class="badge bg-success">متوفرة</span>
                    @else
                        <span class="badge bg-secondary">غير متوفرة</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wilayats Section -->
@if($governorate->wilayats && $governorate->wilayats->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-header" style="
                background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
                color: white;
                border-radius: 20px 20px 0 0;
                border: none;
            ">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    ولايات {{ $governorate->name_ar }}
                </h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="row">
                    @foreach($governorate->wilayats as $wilayat)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="wilayat-card" style="
                            background: #fff;
                            border-radius: 15px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            transition: all 0.3s ease;
                            height: 100%;
                        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'">
                            
                            <!-- صورة الولاية -->
                            <div class="wilayat-image-container" style="
                                background: linear-gradient(135deg, #f5f1eb 0%, #e8ddd4 50%, #d4c4b0 100%);
                                padding: 15px;
                                position: relative;
                                overflow: hidden;
                            ">
                                <!-- تأثير زخرفي في الخلفية -->
                                <div style="
                                    position: absolute;
                                    top: -30px;
                                    right: -30px;
                                    width: 60px;
                                    height: 60px;
                                    background: rgba(97, 76, 57, 0.1);
                                    border-radius: 50%;
                                    z-index: 1;
                                "></div>
                                <div style="
                                    position: absolute;
                                    bottom: -20px;
                                    left: -20px;
                                    width: 40px;
                                    height: 40px;
                                    background: rgba(97, 76, 57, 0.08);
                                    border-radius: 50%;
                                    z-index: 1;
                                "></div>
                                
                                @if($wilayat->has_image)
                                    <img src="{{ $wilayat->image_url }}" 
                                         alt="{{ $wilayat->name_ar }}" 
                                         class="img-fluid rounded shadow"
                                         style="
                                            width: 100%;
                                            height: 200px;
                                            object-fit: cover;
                                            border-radius: 10px;
                                            position: relative;
                                            z-index: 2;
                                         ">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="
                                        height: 200px;
                                        background: rgba(255,255,255,0.8);
                                        border-radius: 10px;
                                        position: relative;
                                        z-index: 2;
                                    ">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x text-muted mb-2" style="opacity: 0.6;"></i>
                                            <p class="text-muted mb-0">لا توجد صورة</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- معلومات الولاية -->
                            <div class="p-3">
                                <h6 class="fw-bold mb-2 text-primary">{{ $wilayat->name_ar }}</h6>
                                <p class="text-muted mb-2">{{ $wilayat->name_en }}</p>
                                
                                @if($wilayat->website_url)
                                <div class="mb-3">
                                    <a href="{{ $wilayat->website_url }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        زيارة الموقع
                                    </a>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $wilayat->created_at->format('Y-m-d') }}
                                    </small>
                                    <a href="{{ route('wilayats.show', $wilayat->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض التفاصيل
                                    </a>
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
@else
<!-- رسالة عدم وجود ولايات -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-header" style="
                background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
                color: white;
                border-radius: 20px 20px 0 0;
                border: none;
            ">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    ولايات {{ $governorate->name_ar }}
                </h5>
            </div>
            <div class="card-body text-center" style="padding: 3rem;">
                <div class="wilayat-image-container" style="
                    background: linear-gradient(135deg, #f5f1eb 0%, #e8ddd4 50%, #d4c4b0 100%);
                    border-radius: 20px;
                    padding: 40px;
                    box-shadow: 0 8px 25px rgba(97, 76, 57, 0.15);
                    position: relative;
                    overflow: hidden;
                    max-width: 400px;
                    margin: 0 auto;
                ">
                    <!-- تأثير زخرفي في الخلفية -->
                    <div style="
                        position: absolute;
                        top: -50px;
                        right: -50px;
                        width: 100px;
                        height: 100px;
                        background: rgba(97, 76, 57, 0.1);
                        border-radius: 50%;
                        z-index: 1;
                    "></div>
                    <div style="
                        position: absolute;
                        bottom: -30px;
                        left: -30px;
                        width: 60px;
                        height: 60px;
                        background: rgba(97, 76, 57, 0.08);
                        border-radius: 50%;
                        z-index: 1;
                    "></div>
                    
                    <div class="text-center" style="position: relative; z-index: 2;">
                        <i class="fas fa-map-marked-alt fa-4x text-muted mb-3" style="opacity: 0.6;"></i>
                        <h5 class="text-muted mb-2">لا توجد ولايات متاحة</h5>
                        <p class="text-muted mb-3">لم يتم إضافة أي ولايات لهذه المحافظة بعد</p>
                        <a href="{{ route('wilayats.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            إضافة أول ولاية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    .info-item {
        padding: 1rem;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: rgba(97, 76, 57, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .info-icon i {
        font-size: 1.2rem;
    }
</style>
@endpush
@endsection
