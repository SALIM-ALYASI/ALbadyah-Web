@extends('layouts.tourism')

@section('title', 'المواقع السياحية - البادية')
@section('description', 'اكتشف أجمل المواقع السياحية في سلطنة عُمان')

@section('content')
<!-- Page Header -->
<section class="hero-section" style="min-height: 40vh;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1>المواقع السياحية</h1>
                    <p>من الجبال الشامخة إلى السواحل الذهبية، اكتشف أجمل الأماكن في سلطنة عُمان</p>
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
                        <p class="search-subtitle">ابحث عن المواقع السياحية المفضلة لديك</p>
                    </div>
                    
                    <form method="GET" action="{{ route('tourism.tourist-sites') }}" class="search-form">
                        <div class="row g-3">
                            <div class="col-lg-5 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>البحث
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control modern-input" 
                                               placeholder="ابحث عن موقع سياحي..." 
                                               value="{{ request('search') }}">
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
                            
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-map-marked-alt me-1"></i>الولاية
                                    </label>
                                    <div class="select-wrapper">
                                        <select name="wilayat_id" class="form-select modern-select">
                                            <option value="">جميع الولايات</option>
                                            @foreach($wilayats as $wilayat)
                                                <option value="{{ $wilayat->id }}" {{ request('wilayat_id') == $wilayat->id ? 'selected' : '' }}>
                                                    {{ $wilayat->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-1 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn search-btn w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if(request('search') || request('governorate_id') || request('wilayat_id'))
                        <div class="search-results-info">
                            <div class="active-filters">
                                <span class="filter-label">الفلترة النشطة:</span>
                                @if(request('search'))
                                    <span class="filter-tag">
                                        <i class="fas fa-search me-1"></i>{{ request('search') }}
                                        <a href="{{ route('tourism.tourist-sites', array_filter(request()->except('search'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if(request('governorate_id'))
                                    <span class="filter-tag">
                                        <i class="fas fa-building me-1"></i>{{ $governorates->where('id', request('governorate_id'))->first()->name_ar ?? '' }}
                                        <a href="{{ route('tourism.tourist-sites', array_filter(request()->except('governorate_id'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if(request('wilayat_id'))
                                    <span class="filter-tag">
                                        <i class="fas fa-map-marked-alt me-1"></i>{{ $wilayats->where('id', request('wilayat_id'))->first()->name_ar ?? '' }}
                                        <a href="{{ route('tourism.tourist-sites', array_filter(request()->except('wilayat_id'))) }}" class="filter-remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                <a href="{{ route('tourism.tourist-sites') }}" class="clear-all-btn">
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

<!-- Tourist Sites Grid -->
<section class="section">
    <div class="container">
        @if($touristSites->count() > 0)
            <div class="row">
                @foreach($touristSites as $site)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        @if($site->images->count() > 0)
                            @php $firstImage = $site->images->first(); @endphp
                            @if($firstImage->image_path)
                                <img src="{{ asset('storage/' . $firstImage->image_path) }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 250px; object-fit: cover;">
                            @elseif($firstImage->image_url)
                                <img src="{{ $firstImage->image_url }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 250px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 250px; object-fit: cover;">
                            @endif
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 250px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $site->name_ar }}</h5>
                            <p class="card-text text-muted">{{ $site->name_en }}</p>
                            
                            @if($site->description_ar)
                                <p class="card-text">{{ Str::limit($site->description_ar, 100) }}</p>
                            @endif
                            
                            <div class="site-info mb-3">
                                <div class="info-grid">
                                    @if($site->location)
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="info-text">
                                                <span class="info-label">الموقع</span>
                                                <span class="info-value">{{ $site->location }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($site->governorate)
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div class="info-text">
                                                <span class="info-label">المحافظة</span>
                                                <span class="info-value">{{ $site->governorate->name_ar }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($site->wilayat)
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <div class="info-text">
                                                <span class="info-label">الولاية</span>
                                                <span class="info-value">{{ $site->wilayat->name_ar }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-images"></i>
                                        </div>
                                        <div class="info-text">
                                            <span class="info-label">الصور</span>
                                            <span class="info-value">{{ $site->images->count() }} صورة</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <a href="{{ route('tourism.tourist-site', $site->id) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($touristSites->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        {{ $touristSites->links() }}
                    </nav>
                </div>
            </div>
            @endif
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">لم يتم العثور على مواقع سياحية</h4>
                        <p class="text-muted">جرب البحث بكلمات مختلفة أو تصفح جميع المواقع</p>
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i>عرض جميع المواقع
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>هل تبحث عن خدمات سياحية؟</h3>
                <p>اكتشف خدماتنا المتميزة من فنادق ومطاعم وشركات نقل</p>
                <a href="{{ route('tourism.tourist-services') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-concierge-bell me-2"></i>عرض الخدمات السياحية
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
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
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
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
