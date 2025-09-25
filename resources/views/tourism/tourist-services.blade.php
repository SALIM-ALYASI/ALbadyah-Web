@extends('layouts.tourism')

@section('title', 'الخدمات السياحية - البادية')
@section('description', 'اكتشف أفضل الخدمات السياحية في سلطنة عُمان من فنادق ومطاعم وشركات نقل')

@section('content')
<!-- Page Header -->
<section class="hero-section" style="min-height: 40vh;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1>الخدمات السياحية</h1>
                    <p>من الفنادق الفاخرة إلى المطاعم التقليدية، اكتشف أفضل الخدمات السياحية في سلطنة عُمان</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="search-filter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-filter-card">
                    <div class="search-header">
                        <h3 class="search-title">
                            <i class="fas fa-search me-2"></i>البحث والفلترة
                        </h3>
                        <p class="search-subtitle">ابحث عن الخدمات السياحية المفضلة لديك</p>
                    </div>
                    
                    <form method="GET" action="{{ route('tourism.tourist-services') }}" class="search-form">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-concierge-bell me-1"></i>البحث
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control modern-input" 
                                               placeholder="ابحث عن خدمة سياحية..." 
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag me-1"></i>نوع الخدمة
                                    </label>
                                    <div class="select-wrapper">
                                        <select name="service_type_id" class="form-select modern-select">
                                            <option value="">جميع أنواع الخدمات</option>
                                            @foreach($serviceTypes as $type)
                                                <option value="{{ $type->id }}" {{ request('service_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-building me-1"></i>المحافظة
                                    </label>
                                    <div class="select-wrapper">
                                        <select name="governorate_id" class="form-select modern-select">
                                            <option value="">جميع المحافظات</option>
                                            @foreach($governorates as $governorate)
                                                <option value="{{ $governorate->id }}" {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                    {{ $governorate->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn search-btn w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if(request('search') || request('service_type_id') || request('governorate_id'))
                        <div class="search-results-info">
                            <div class="active-filters">
                                <span class="filter-label">الفلترة النشطة:</span>
                                @if(request('search'))
                                    <span class="filter-tag">
                                        <i class="fas fa-search me-1"></i>{{ request('search') }}
                                        <a href="{{ route('tourism.tourist-services', array_filter(request()->except('search'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if(request('service_type_id'))
                                    <span class="filter-tag">
                                        <i class="fas fa-tag me-1"></i>{{ $serviceTypes->where('id', request('service_type_id'))->first()->name_ar ?? '' }}
                                        <a href="{{ route('tourism.tourist-services', array_filter(request()->except('service_type_id'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if(request('governorate_id'))
                                    <span class="filter-tag">
                                        <i class="fas fa-building me-1"></i>{{ $governorates->where('id', request('governorate_id'))->first()->name_ar ?? '' }}
                                        <a href="{{ route('tourism.tourist-services', array_filter(request()->except('governorate_id'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                <a href="{{ route('tourism.tourist-services') }}" class="clear-all-btn">
                                    <i class="fas fa-trash me-1"></i>مسح الكل
                                </a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Types Overview -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title">أنواع الخدمات المتاحة</h3>
            </div>
        </div>
        
        <div class="row">
            @foreach($serviceTypes->take(8) as $type)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="service-type-card text-center">
                    <div class="service-icon">
                        @switch($type->name_ar)
                            @case('الفنادق')
                                <i class="fas fa-hotel"></i>
                                @break
                            @case('المطاعم')
                                <i class="fas fa-utensils"></i>
                                @break
                            @case('النقل')
                                <i class="fas fa-bus"></i>
                                @break
                            @case('المرشدين السياحيين')
                                <i class="fas fa-user-tie"></i>
                                @break
                            @case('التسوق')
                                <i class="fas fa-shopping-bag"></i>
                                @break
                            @case('الترفيه')
                                <i class="fas fa-gamepad"></i>
                                @break
                            @default
                                <i class="fas fa-concierge-bell"></i>
                        @endswitch
                    </div>
                    <h5>{{ $type->name_ar }}</h5>
                    <p class="text-muted">{{ $type->name_en }}</p>
                    <a href="{{ route('tourism.tourist-services', ['service_type_id' => $type->id]) }}" class="btn btn-outline-primary btn-sm">
                        عرض الخدمات
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
  </section>

<!-- Tourist Services Grid -->
<section class="section bg-light">
    <div class="container">
        @if($touristServices->count() > 0)
            <div class="row">
                @foreach($touristServices as $service)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 modern-card">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 220px; object-fit: cover;">
                        @elseif($service->image_url)
                            <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 220px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 220px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="service-header mb-3">
                                @if($service->serviceType)
                                    <span class="badge service-type-badge mb-2">{{ $service->serviceType->name_ar }}</span>
                                @endif
                                <h5 class="card-title">{{ $service->name_ar }}</h5>
                                <p class="card-text text-muted">{{ $service->name_en }}</p>
                            </div>
                            
                            <div class="service-info mb-3">
                                @if($service->governorate)
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="info-text">
                                            <span class="info-label">المحافظة</span>
                                            <span class="info-value">{{ $service->governorate->name_ar }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($service->wilayat)
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="info-text">
                                            <span class="info-label">الولاية</span>
                                            <span class="info-value">{{ $service->wilayat->name_ar }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-auto">
                                <a href="{{ route('tourism.tourist-service', $service->id) }}" class="btn btn-primary w-100 modern-btn">
                                    <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($touristServices->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <div class="pagination-wrapper">
                        <nav aria-label="Page navigation" class="modern-pagination">
                            {{ $touristServices->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-search fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">لم يتم العثور على خدمات سياحية</h4>
                            <p class="text-muted mb-4">جرب البحث بكلمات مختلفة أو تصفح جميع الخدمات</p>
                            <a href="{{ route('tourism.tourist-services') }}" class="btn btn-primary modern-btn">
                                <i class="fas fa-list me-2"></i>عرض جميع الخدمات
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>هل تخطط لرحلة سياحية؟</h3>
                <p>اكتشف المواقع السياحية الرائعة في سلطنة عُمان</p>
                <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-camera me-2"></i>عرض المواقع السياحية
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* التصميم العصري الجديد */
    .section {
        padding: 80px 0;
    }
    
    .section.bg-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .modern-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        background: white;
    }
    
    .modern-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    
    .modern-card .card-img-top {
        border-radius: 20px 20px 0 0;
        transition: all 0.4s ease;
    }
    
    .modern-card:hover .card-img-top {
        transform: scale(1.1);
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
    }
    
    .service-type-badge {
        background: linear-gradient(135deg, #6b8b8a 0%, #614c39 100%);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .card-title {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }
    
    .card-text {
        color: #6c757d;
        line-height: 1.6;
    }
    
    .service-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.05) 0%, rgba(161, 129, 90, 0.05) 100%);
        border-radius: 15px;
        border: 1px solid rgba(97, 76, 57, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .info-item:hover::before {
        left: 100%;
    }
    
    .info-item:hover {
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.1) 0%, rgba(161, 129, 90, 0.1) 100%);
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(97, 76, 57, 0.2);
    }
    
    .info-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        margin-left: 1rem;
        flex-shrink: 0;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-icon::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transition: all 0.3s ease;
        transform: translate(-50%, -50%);
    }
    
    .info-icon:hover::before {
        width: 100%;
        height: 100%;
    }
    
    .info-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-3px) scale(1.1) rotate(5deg);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2), 
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .info-icon i {
        color: white;
        font-size: 1.3rem;
        z-index: 1;
        position: relative;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .info-text {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: var(--primary-color);
        font-weight: 700;
        line-height: 1.2;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(97, 76, 57, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.6s ease;
    }
    
    .modern-btn:hover::before {
        left: 100%;
    }
    
    .modern-btn:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(97, 76, 57, 0.4);
    }
    
    .empty-state {
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 4rem;
        padding: 2rem 0;
    }
    
    .modern-pagination {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 1rem 2rem;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }
    
    .pagination {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        color: var(--primary-color);
        border: 2px solid transparent;
        border-radius: 15px;
        margin: 0;
        padding: 12px 16px;
        min-width: 45px;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-weight: 600;
        font-size: 0.95rem;
        background: rgba(97, 76, 57, 0.05);
        position: relative;
        overflow: hidden;
    }
    
    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .pagination .page-link:hover::before {
        left: 100%;
    }
    
    .pagination .page-link:hover {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.4);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-color: var(--primary-color);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.4);
        font-weight: 700;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        background: rgba(0,0,0,0.05);
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
        background: rgba(0,0,0,0.05);
        color: #ccc;
    }
    
    /* تخصيص أسهم التنقل */
    .pagination .page-link[rel="prev"],
    .pagination .page-link[rel="next"] {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: white;
        border: none;
        font-weight: 700;
        padding: 12px 20px;
        min-width: 60px;
    }
    
    .pagination .page-link[rel="prev"]:hover,
    .pagination .page-link[rel="next"]:hover {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(97, 76, 57, 0.4);
    }
    
    .pagination .page-link[rel="prev"]::after {
        content: ' ←';
        margin-right: 5px;
    }
    
    .pagination .page-link[rel="next"]::after {
        content: ' →';
        margin-left: 5px;
    }
    
    @media (max-width: 768px) {
        .modern-card {
            margin-bottom: 2rem;
        }
        
        .info-item {
            padding: 0.75rem;
        }
        
        .info-icon {
            width: 45px;
            height: 45px;
            margin-left: 0.75rem;
        }
        
        .info-icon i {
            font-size: 1.1rem;
        }
        
        .info-label {
            font-size: 0.7rem;
        }
        
        .info-value {
            font-size: 0.9rem;
        }
        
        .modern-btn {
            padding: 12px 25px;
            font-size: 0.9rem;
        }
        
        /* تحسينات الأسهم للهواتف */
        .pagination-wrapper {
            margin-top: 2rem;
            padding: 1rem 0;
        }
        
        .modern-pagination {
            padding: 0.75rem 1rem;
            border-radius: 20px;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            padding: 8px 12px;
            min-width: 35px;
            font-size: 0.85rem;
            border-radius: 12px;
        }
        
        .pagination .page-link[rel="prev"],
        .pagination .page-link[rel="next"] {
            padding: 8px 15px;
            min-width: 50px;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 480px) {
        .modern-pagination {
            padding: 0.5rem;
            gap: 0.2rem;
        }
        
        .pagination .page-link {
            padding: 6px 10px;
            min-width: 30px;
            font-size: 0.8rem;
        }
        
        .pagination .page-link[rel="prev"],
        .pagination .page-link[rel="next"] {
            padding: 6px 12px;
            min-width: 45px;
            font-size: 0.75rem;
        }
        
        .pagination .page-link[rel="prev"]::after,
        .pagination .page-link[rel="next"]::after {
            display: none;
        }
    }
    .service-type-card {
        background: white;
        border-radius: 15px;
        padding: 2rem 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .service-type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 2rem;
    }
    
    .service-header .badge {
        font-size: 0.8rem;
        padding: 6px 10px;
    }
    
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: visible;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .card-img-top {
        border-radius: 15px 15px 0 0;
        transition: all 0.3s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .card-title {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .card-text {
        color: #666;
        line-height: 1.6;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    @media (min-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.05) 0%, rgba(161, 129, 90, 0.05) 100%);
        border-radius: 10px;
        border: 1px solid rgba(97, 76, 57, 0.1);
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.1) 0%, rgba(161, 129, 90, 0.1) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(97, 76, 57, 0.15);
    }
    
    .info-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        margin-left: 0.75rem;
        flex-shrink: 0;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .info-icon i {
        color: white;
        font-size: 1.1rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .info-item:hover .info-icon {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .info-text {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: #888;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 0.9rem;
        color: var(--primary-color);
        font-weight: 600;
        line-height: 1.2;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(97, 76, 57, 0.2);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(97, 76, 57, 0.3);
    }
    
    .pagination {
        justify-content: center;
        margin-top: 3rem;
    }
    
    .pagination .page-link {
        color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 8px;
        margin: 0 2px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(97, 76, 57, 0.3);
    }
    
    /* Custom Badge for Service Type */
    .custom-badge-service {
        background: linear-gradient(135deg, #6b8b8a 0%, #614c39 100%) !important;
        color: white !important;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .custom-badge-service:hover {
        background: linear-gradient(135deg, #5a7a79 0%, #4a3a2a 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(107, 139, 138, 0.3);
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .info-item {
            padding: 0.5rem;
        }
        
        .info-icon {
            width: 38px;
            height: 38px;
            margin-left: 0.5rem;
        }
        
        .info-icon i {
            font-size: 1rem;
        }
        
        .info-label {
            font-size: 0.7rem;
        }
        
        .info-value {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }
        
        .info-item {
            padding: 0.4rem;
        }
        
        .info-icon {
            width: 35px;
            height: 35px;
            margin-left: 0.4rem;
        }
        
        .info-icon i {
            font-size: 0.9rem;
        }
    }
    
    /* Search and Filter Section */
    .search-filter-section {
        padding: 80px 0 60px;
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.05) 0%, rgba(161, 129, 90, 0.03) 100%);
        position: relative;
    }
    
    .search-filter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23deb47a" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        pointer-events: none;
    }
    
    .search-filter-card {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(97, 76, 57, 0.1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .search-filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
    }
    
    .search-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .search-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    
    .search-title i {
        color: var(--accent-color);
        font-size: 1.8rem;
    }
    
    .search-subtitle {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 400;
    }
    
    .search-form {
        position: relative;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-label i {
        color: var(--accent-color);
        margin-left: 0.5rem;
    }
    
    .input-group {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .input-group:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .input-group-text {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        color: white;
        padding: 1rem 1.25rem;
        font-size: 1.1rem;
        border-radius: 0;
    }
    
    .modern-input {
        border: none !important;
        padding: 1rem 1.25rem !important;
        font-size: 1rem !important;
        background: white !important;
        color: var(--text-dark) !important;
        font-weight: 500 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
    }
    
    .modern-input:focus {
        outline: none !important;
        box-shadow: none !important;
        background: rgba(97, 76, 57, 0.02) !important;
    }
    
    .modern-input::placeholder {
        color: #adb5bd !important;
        font-style: normal !important;
        font-weight: 400 !important;
    }
    
    .select-wrapper {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .select-wrapper:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .modern-select {
        border: none !important;
        padding: 1rem 1.25rem !important;
        font-size: 1rem !important;
        background: white !important;
        color: var(--text-dark) !important;
        font-weight: 500 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        appearance: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .modern-select:focus {
        outline: none !important;
        box-shadow: none !important;
        background: rgba(97, 76, 57, 0.02) !important;
    }
    
    .select-icon {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: var(--accent-color);
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 1;
    }
    
    .search-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
        border: none !important;
        color: white !important;
        padding: 1rem !important;
        font-size: 1.2rem !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.3) !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        overflow: hidden !important;
        height: 60px !important;
    }
    
    .search-btn::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: -100% !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important;
        transition: left 0.6s ease !important;
    }
    
    .search-btn:hover::before {
        left: 100% !important;
    }
    
    .search-btn:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%) !important;
        transform: translateY(-3px) scale(1.05) !important;
        box-shadow: 0 12px 35px rgba(97, 76, 57, 0.4) !important;
    }
    
    .search-btn:active {
        transform: translateY(-1px) scale(1.02) !important;
    }
    
    .search-results-info {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid rgba(97, 76, 57, 0.1);
    }
    
    .active-filters {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-left: 0.5rem;
    }
    
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 3px 10px rgba(222, 180, 122, 0.3);
        transition: all 0.3s ease;
    }
    
    .filter-tag:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(222, 180, 122, 0.4);
    }
    
    .filter-remove {
        color: white;
        text-decoration: none;
        font-size: 0.8rem;
        margin-left: 0.5rem;
        padding: 0.2rem;
        border-radius: 50%;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
    }
    
    .filter-remove:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transform: scale(1.1);
    }
    
    .clear-all-btn {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid rgba(220, 53, 69, 0.2);
    }
    
    .clear-all-btn:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .search-filter-section {
            padding: 60px 0 40px;
        }
        
        .search-filter-card {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        
        .search-title {
            font-size: 1.6rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .search-subtitle {
            font-size: 1rem;
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        
        .input-group-text {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        
        .modern-input,
        .modern-select {
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
        }
        
        .search-btn {
            padding: 0.75rem !important;
            font-size: 1.1rem !important;
            height: 50px !important;
        }
        
        .active-filters {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .filter-tag {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }
    
    @media (max-width: 480px) {
        .search-filter-card {
            padding: 1.5rem 1rem;
            margin: 0 0.5rem;
        }
        
        .search-title {
            font-size: 1.4rem;
        }
        
        .search-subtitle {
            font-size: 0.9rem;
        }
        
        .modern-input,
        .modern-select {
            padding: 0.65rem 0.85rem !important;
            font-size: 0.9rem !important;
        }
        
        .input-group-text {
            padding: 0.65rem 0.85rem;
            font-size: 0.95rem;
        }
        
        .search-btn {
            padding: 0.65rem !important;
            font-size: 1rem !important;
            height: 45px !important;
        }
    }
</style>
@endpush
