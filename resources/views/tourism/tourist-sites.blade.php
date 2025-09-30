@extends('layouts.tourism')

@section('title', 'المواقع السياحية - البادية')
@section('description', 'اكتشف أجمل المواقع السياحية في سلطنة عُمان')

@section('content')
<!-- Page Header -->
<section class="hero-section" style="min-height: calc(var(--vh, 1vh) * 70);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <div class="hero-content fade-in-up">
                    <h1 class="hero-title">المواقع السياحية</h1>
                    <p class="hero-subtitle">من الجبال الشامخة إلى السواحل الذهبية، اكتشف أجمل الأماكن في سلطنة عُمان</p>
                    
                    <!-- Hero Stats -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $touristSites->total() }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorates->count() }}</div>
                            <div class="stat-label">محافظة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $wilayats->count() }}</div>
                            <div class="stat-label">ولاية</div>
                        </a>
                    </div>
                    
                    <!-- Hero Actions -->
                    <div class="hero-actions">
                        <div class="actions-row">
                            <a href="#search-section" class="btn btn-outline-light modern-btn me-2 mb-2">
                                <i class="fas fa-search me-2"></i>البحث والفلترة
                            </a>
                            <a href="{{ route('tourism.governorates') }}" class="btn btn-light modern-btn me-2 mb-2">
                                <i class="fas fa-building me-2"></i>المحافظات
                            </a>
                            <a href="{{ route('tourism.tourist-services') }}" class="btn btn-outline-light modern-btn mb-2">
                                <i class="fas fa-concierge-bell me-2"></i>الخدمات السياحية
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section id="search-section" class="search-filter-section">
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
        <div class="row justify-content-center">
            @foreach($touristSites as $site)
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="site-card">
                    <div class="site-image">
                        @if($site->images->count() > 0)
                        @php $firstImage = $site->images->first(); @endphp
                        @if($firstImage->image_path)
                        <img src="{{ asset($firstImage->image_path) }}" alt="{{ $site->name_ar }}" class="img-fluid">
                        @elseif($firstImage->image_url)
                        <img src="{{ $firstImage->image_url }}" alt="{{ $site->name_ar }}" class="img-fluid">
                        @else
                        <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $site->name_ar }}" class="img-fluid">
                        @endif
                        @else
                        <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $site->name_ar }}" class="img-fluid">
                        @endif
                        
                        <!-- Image Overlay -->
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <div class="site-badges">
                                    @if($site->governorate)
                                    <span class="badge badge-governorate">{{ $site->governorate->name_ar }}</span>
                                    @endif
                                    @if($site->wilayat)
                                    <span class="badge badge-wilayat">{{ $site->wilayat->name_ar }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="site-content">
                        <div class="site-header">
                            <h5 class="site-title">{{ $site->name_ar }}</h5>
                            <p class="site-name-en">{{ $site->name_en }}</p>
                        </div>

                        @if($site->description_ar)
                        <p class="site-description">{{ Str::limit($site->description_ar, 120) }}</p>
                        @endif

                        <div class="site-info">
                            <div class="info-grid">
                                @if($site->location)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-text">
                                        <span class="info-label">الموقع</span>
                                        <span class="info-value">{{ Str::limit($site->location, 25) }}</span>
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

                        <div class="site-actions">
                            <a href="{{ route('tourism.tourist-site', $site->slug) }}" class="btn btn-primary site-btn">
                                <i class="fas fa-eye me-2"></i>عرض التفاصيل
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
    :root {
        --vh: 1vh;
    }
    
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.8), rgba(161, 129, 90, 0.7), rgba(222, 180, 122, 0.6)),
        url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: scroll;
        position: relative;
        overflow: hidden;
    }
    
    /* Hero Content */
    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.4rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Hero Stats */
    .hero-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin: 2rem auto;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 800px;
        flex-wrap: wrap;
    }

    .hero-stats .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        min-width: 120px;
        max-width: 150px;
    }

    .hero-stats .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        line-height: 1;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-stats .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Remove default link styles for stat-item */
    .hero-stats .stat-item {
        color: inherit !important;
        text-decoration: none !important;
        border: none !important;
        outline: none !important;
    }

    .hero-stats .stat-item:visited {
        color: inherit !important;
        text-decoration: none !important;
    }

    .hero-stats .stat-item:focus {
        color: inherit !important;
        text-decoration: none !important;
        outline: none !important;
    }

    .hero-stats .stat-item:hover {
        color: inherit !important;
        text-decoration: none !important;
    }

    .hero-stats .stat-item:active {
        color: inherit !important;
        text-decoration: none !important;
    }
    
    /* Hero Actions */
    .hero-actions {
        margin-top: 2rem;
    }
    
    .actions-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
        align-items: center;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.2) 100%);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        color: white;
        text-decoration: none;
    }
    
    .modern-btn:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.3) 100%);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Performance Optimizations */
    .site-card,
    .hero-stats .stat-item,
    .modern-btn {
        will-change: transform;
        backface-visibility: hidden;
        perspective: 1000px;
    }
    
    /* Fix for mobile scrolling issues */
    .hero-section,
    .search-filter-section,
    .section {
        -webkit-overflow-scrolling: touch;
        transform: translateZ(0);
    }

    /* Site Cards */
    .site-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .site-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }

    .site-image {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .site-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .site-card:hover .site-image img {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .site-card:hover .image-overlay {
        opacity: 1;
    }

    .overlay-content {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .site-badges {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .badge {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .badge-governorate {
        background: linear-gradient(135deg, rgba(97, 76, 57, 0.9) 0%, rgba(161, 129, 90, 0.9) 100%);
        color: white;
    }

    .badge-wilayat {
        background: linear-gradient(135deg, rgba(222, 180, 122, 0.9) 0%, rgba(161, 129, 90, 0.9) 100%);
        color: white;
    }

    .site-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: calc(100% - 250px);
    }

    .site-header {
        margin-bottom: 1rem;
    }

    .site-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .site-name-en {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0;
        font-style: italic;
    }

    .site-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
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

    .site-info {
        margin-bottom: 1.5rem;
    }

    .site-actions {
        margin-top: auto;
    }

    .site-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(97, 76, 57, 0.2);
        width: 100%;
    }

    .site-btn:hover {
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

    /* Responsive Design */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
        }
        
        .hero-stats {
            max-width: 700px;
            gap: 1.75rem;
        }
        
        .hero-stats .stat-item {
            min-width: 110px;
            max-width: 140px;
        }
        
        .actions-row {
            gap: 1rem;
        }
        
        .modern-btn {
            padding: 10px 20px;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            background-attachment: scroll;
            min-height: calc(var(--vh, 1vh) * 60);
        }
        
        .hero-title {
            font-size: 2.8rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
            max-width: 600px;
        }
        
        .hero-stats .stat-item {
            min-width: 100px;
            max-width: 130px;
        }
        
        .hero-stats .stat-number {
            font-size: 2rem;
        }
        
        .actions-row {
            flex-direction: column;
            align-items: center;
        }
        
        .modern-btn {
            width: 100%;
            max-width: 280px;
            margin: 0.25rem 0;
            touch-action: manipulation;
        }
        
        .site-image {
            height: 220px;
        }
        
        .site-content {
            height: calc(100% - 220px);
            padding: 1.25rem;
        }
        
        /* Mobile touch optimizations */
        .site-card {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

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

    @media (max-width: 576px) {
        .hero-section {
            min-height: calc(var(--vh, 1vh) * 50);
        }
        
        .hero-title {
            font-size: 2.2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .hero-stats {
            flex-direction: column;
            gap: 1rem;
            max-width: 300px;
            padding: 1rem 0.5rem;
        }
        
        .hero-stats .stat-item {
            min-width: 80px;
            max-width: 120px;
            padding: 0.5rem;
        }
        
        .hero-stats .stat-number {
            font-size: 1.8rem;
        }
        
        .hero-stats .stat-label {
            font-size: 0.9rem;
        }
        
        .modern-btn {
            padding: 8px 16px;
            font-size: 0.9rem;
            touch-action: manipulation;
        }
        
        .site-image {
            height: 200px;
        }
        
        .site-content {
            height: calc(100% - 200px);
            padding: 1rem;
        }
        
        .site-title {
            font-size: 1.1rem;
        }
        
        .site-description {
            font-size: 0.85rem;
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

    @media (max-width: 480px) {
        .hero-section {
            min-height: calc(var(--vh, 1vh) * 45);
        }
        
        .hero-title {
            font-size: 1.8rem;
        }
        
        .hero-subtitle {
            font-size: 0.9rem;
        }
        
        .hero-stats {
            max-width: 280px;
            padding: 0.75rem 0.25rem;
        }
        
        .hero-stats .stat-item {
            min-width: 70px;
            max-width: 100px;
            padding: 0.25rem;
        }
        
        .hero-stats .stat-number {
            font-size: 1.5rem;
        }
        
        .hero-stats .stat-label {
            font-size: 0.8rem;
        }
        
        .modern-btn {
            padding: 6px 12px;
            font-size: 0.85rem;
            max-width: 250px;
            touch-action: manipulation;
        }
        
        .site-image {
            height: 180px;
        }
        
        .site-content {
            height: calc(100% - 180px);
            padding: 0.75rem;
        }
        
        .site-title {
            font-size: 1rem;
        }
        
        .site-description {
            font-size: 0.8rem;
        }

        .info-item {
            padding: 0.3rem;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            margin-left: 0.3rem;
        }

        .info-icon i {
            font-size: 0.8rem;
        }
        
        .info-label {
            font-size: 0.65rem;
        }
        
        .info-value {
            font-size: 0.8rem;
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent) !important;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Optimize scroll performance
    let ticking = false;
    
    function updateScrollPosition() {
        // Add any scroll-based animations here
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateScrollPosition);
            ticking = true;
        }
    }
    
    // Throttle scroll events for better performance
    window.addEventListener('scroll', requestTick, { passive: true });
    
    // Fix iOS viewport issues
    function fixViewport() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    fixViewport();
    window.addEventListener('resize', fixViewport);
    window.addEventListener('orientationchange', fixViewport);
    
    // Preload critical images
    const criticalImages = document.querySelectorAll('.site-image img');
    criticalImages.forEach(img => {
        if (img.src) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src;
            document.head.appendChild(link);
        }
    });
});
</script>
@endpush