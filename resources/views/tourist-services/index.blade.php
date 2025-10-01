@extends('layouts.app')

@section('title', 'الخدمات السياحية')
@section('page-title', 'إدارة الخدمات السياحية')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">الخدمات السياحية</h1>
        <p class="text-muted mb-0">إدارة وعرض جميع الخدمات السياحية في النظام</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.tourist-services.create-location') }}" class="btn btn-primary">
            <i class="fas fa-map-marker-alt"></i>
            إضافة موقع خدمة جديد
        </a>
        <a href="{{ route('dashboard.tourist-services.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i>
            إضافة خدمة سريعة
        </a>
    </div>
</div>

@if($touristServices->count() > 0)
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristServices->count() }}</h3>
                <p>إجمالي الخدمات السياحية</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristServices->where('website_url', '!=', null)->count() }}</h3>
                <p>خدمات لها موقع إلكتروني</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristServices->where('image_url', '!=', null)->count() }}</h3>
                <p>خدمات لها صور</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristServices->filter(function($item) { return $item->created_at && $item->created_at->isToday(); })->count() }}</h3>
                <p>خدمات أضيفت اليوم</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>
                البحث والفلترة
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.tourist-services.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="q" class="form-label">البحث</label>
                        <input type="text" 
                               class="form-control" 
                               id="q" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="ابحث في اسم الخدمة">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="governorate_id" class="form-label">المحافظة</label>
                        <select class="form-control" id="governorate_id" name="governorate_id">
                            <option value="">جميع المحافظات</option>
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate->id }}" 
                                        {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                    {{ $governorate->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="wilayat_id" class="form-label">الولاية</label>
                        <select class="form-control" id="wilayat_id" name="wilayat_id">
                            <option value="">جميع الولايات</option>
                            @foreach($wilayats as $wilayat)
                                <option value="{{ $wilayat->id }}" 
                                        {{ request('wilayat_id') == $wilayat->id ? 'selected' : '' }}>
                                    {{ $wilayat->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="service_type_id" class="form-label">نوع الخدمة</label>
                        <select class="form-control" id="service_type_id" name="service_type_id">
                            <option value="">جميع الأنواع</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}" 
                                        {{ request('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                    {{ $serviceType->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                    <a href="{{ route('dashboard.tourist-services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        مسح الفلاتر
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="row">
        @foreach($touristServices as $service)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <!-- Service Image -->
                @if($service->has_image)
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="{{ $service->image_url }}" 
                             alt="{{ $service->name_ar }}" 
                             class="card-img-top" 
                             style="height: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-concierge-bell text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $service->name_ar }}</h5>
                    <p class="card-text text-muted">{{ $service->name_en }}</p>
                    
                    <div class="mb-3">
                        @if($service->serviceType)
                            <span class="badge bg-primary me-1">{{ $service->serviceType->name_ar }}</span>
                        @endif
                        @if($service->governorate)
                            <span class="badge bg-info me-1">{{ $service->governorate->name_ar }}</span>
                        @endif
                        @if($service->wilayat)
                            <span class="badge bg-secondary">{{ $service->wilayat->name_ar }}</span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $service->created_at->format('Y-m-d') }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dashboard.tourist-services.show', $service->id) }}" 
                                   class="btn btn-success" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.tourist-services.edit', $service->id) }}" 
                                   class="btn btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dashboard.tourist-services.destroy', $service->id) }}" 
                                      method="POST" style="display: inline;" 
                                      onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-concierge-bell"></i>
                <h4>لا توجد خدمات سياحية</h4>
                <p class="mb-4">لم يتم إضافة أي خدمات سياحية بعد. ابدأ بإضافة أول خدمة سياحية في النظام.</p>
                <a href="{{ route('dashboard.tourist-services.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    إضافة أول خدمة سياحية
                </a>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
        color: #fff;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.3);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
    }
    
    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stats-card p {
        opacity: 0.9;
        margin: 0;
        font-size: 0.9rem;
    }
</style>
@endpush
@endsection
