@extends('layouts.app')

@section('title', 'تفاصيل الخدمة السياحية - ' . $touristService->name_ar)
@section('page-title', 'تفاصيل الخدمة السياحية')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تفاصيل الخدمة السياحية</h1>
        <p class="text-muted mb-0">عرض جميع معلومات الخدمة السياحية</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tourist-services.edit', $touristService->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
        <a href="{{ route('tourist-services.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-concierge-bell me-2"></i>
                    {{ $touristService->name_ar ?? 'غير محدد' }}
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
                            <p class="fs-5 fw-bold mb-0">{{ $touristService->name_ar ?? 'غير محدد' }}</p>
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالإنجليزية</h6>
                            </div>
                            <p class="fs-5 mb-0">{{ $touristService->name_en ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-tags text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">نوع الخدمة</h6>
                            </div>
                            @if($touristService->serviceType)
                                <span class="badge bg-primary fs-6">{{ $touristService->serviceType->name_ar }}</span>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">المحافظة</h6>
                            </div>
                            @if($touristService->governorate)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info fs-6 me-2">{{ $touristService->governorate->name_ar }}</span>
                                    <a href="{{ route('governorates.show', $touristService->governorate->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض المحافظة
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-map-marked-alt text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الولاية</h6>
                            </div>
                            @if($touristService->wilayat)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary fs-6 me-2">{{ $touristService->wilayat->name_ar }}</span>
                                    <a href="{{ route('wilayats.show', $touristService->wilayat->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض الولاية
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
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
                            @if($touristService->website_url)
                                <a href="{{ $touristService->website_url }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    زيارة الموقع
                                </a>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($touristService->image_url)
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="info-icon me-3">
                            <i class="fas fa-image text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">صورة الخدمة</h6>
                    </div>
                    <div class="text-center">
                        <img src="{{ $touristService->image_url }}" 
                             alt="{{ $touristService->name_ar }}" 
                             class="img-fluid rounded shadow"
                             style="max-height: 400px; max-width: 100%; object-fit: cover;">
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            آخر تحديث: {{ $touristService->updated_at->format('Y-m-d H:i:s') }}
                        </small>
                    </div>
                    <form action="{{ route('tourist-services.destroy', $touristService->id) }}" 
                          method="POST" 
                          style="display: inline;" 
                          onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            حذف الخدمة السياحية
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
                        <small class="text-muted">معرف الخدمة</small>
                    </div>
                    <span class="badge bg-primary fs-6">#{{ $touristService->id }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-tags text-primary me-2"></i>
                        <small class="text-muted">نوع الخدمة</small>
                    </div>
                    @if($touristService->serviceType)
                        <span class="fw-medium">{{ $touristService->serviceType->name_ar }}</span>
                    @else
                        <span class="text-muted">غير محدد</span>
                    @endif
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <small class="text-muted">تاريخ الإنشاء</small>
                    </div>
                    <span class="fw-medium">{{ $touristService->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-edit text-primary me-2"></i>
                        <small class="text-muted">آخر تحديث</small>
                    </div>
                    <span class="fw-medium">{{ $touristService->updated_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <small class="text-muted">الموقع الإلكتروني</small>
                    </div>
                    @if($touristService->website_url)
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
                    @if($touristService->image_url)
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
