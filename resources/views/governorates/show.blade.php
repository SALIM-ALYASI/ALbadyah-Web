@extends('layouts.app')

@section('title', 'تفاصيل المحافظة - ' . $governorate->name_ar)
@section('page-title', 'تفاصيل المحافظة')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تفاصيل المحافظة</h1>
        <p class="text-muted mb-0">عرض جميع معلومات المحافظة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('governorates.edit', $governorate->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
        <a href="{{ route('governorates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Info Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    {{ $governorate->name_ar ?? 'غير محدد' }}
                </h5>
            </div>
            <div class="card-body">
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
                    <div class="text-center">
                        <img src="{{ $governorate->image_url }}" 
                             alt="{{ $governorate->name_ar }}" 
                             class="img-fluid rounded shadow"
                             style="max-height: 400px; max-width: 100%; object-fit: cover;">
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
                        <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="text-muted mb-0">لا توجد صورة متاحة</p>
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
