@extends('layouts.app')

@section('title', 'تفاصيل الولاية - ' . $wilayat->name_ar)
@section('page-title', 'تفاصيل الولاية')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تفاصيل الولاية</h1>
        <p class="text-muted mb-0">عرض جميع معلومات الولاية</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('wilayats.edit', $wilayat->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
        <a href="{{ route('wilayats.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-map-marked-alt me-2"></i>
                    {{ $wilayat->name_ar ?? 'غير محدد' }}
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
                            <p class="fs-5 fw-bold mb-0">{{ $wilayat->name_ar ?? 'غير محدد' }}</p>
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالإنجليزية</h6>
                            </div>
                            <p class="fs-5 mb-0">{{ $wilayat->name_en ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">المحافظة</h6>
                            </div>
                            @if($wilayat->governorate)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info fs-6 me-2">{{ $wilayat->governorate->name_ar }}</span>
                                    <a href="{{ route('governorates.show', $wilayat->governorate->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض المحافظة
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-globe text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الموقع الرسمي</h6>
                            </div>
                            @if($wilayat->website_url)
                                <a href="{{ $wilayat->website_url }}" 
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
                
                @if($wilayat->image_url)
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="info-icon me-3">
                            <i class="fas fa-image text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">صورة الولاية</h6>
                    </div>
                    <div class="text-center">
                        <img src="{{ $wilayat->image_url }}" 
                             alt="{{ $wilayat->name_ar }}" 
                             class="img-fluid rounded shadow"
                             style="max-height: 400px; max-width: 100%; object-fit: cover;">
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            آخر تحديث: {{ $wilayat->updated_at->format('Y-m-d H:i:s') }}
                        </small>
                    </div>
                    <form action="{{ route('wilayats.destroy', $wilayat->id) }}" 
                          method="POST" 
                          style="display: inline;" 
                          onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            حذف الولاية
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
                        <small class="text-muted">معرف الولاية</small>
                    </div>
                    <span class="badge bg-primary fs-6">#{{ $wilayat->id }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-building text-primary me-2"></i>
                        <small class="text-muted">المحافظة</small>
                    </div>
                    @if($wilayat->governorate)
                        <span class="fw-medium">{{ $wilayat->governorate->name_ar }}</span>
                    @else
                        <span class="text-muted">غير محدد</span>
                    @endif
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <small class="text-muted">تاريخ الإنشاء</small>
                    </div>
                    <span class="fw-medium">{{ $wilayat->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-edit text-primary me-2"></i>
                        <small class="text-muted">آخر تحديث</small>
                    </div>
                    <span class="fw-medium">{{ $wilayat->updated_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <small class="text-muted">الموقع الإلكتروني</small>
                    </div>
                    @if($wilayat->website_url)
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
                    @if($wilayat->image_url)
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
