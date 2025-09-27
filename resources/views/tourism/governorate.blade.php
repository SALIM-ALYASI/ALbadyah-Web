@extends('layouts.tourism')

@section('title', $governorate->name_ar . ' - البادية')
@section('description', 'اكتشف جمال ' . $governorate->name_ar . ' والمواقع السياحية الرائعة فيها')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1 class="governorate-title">{{ $governorate->name_ar }}</h1>
                    <h2 class="governorate-subtitle">{{ $governorate->name_en }}</h2>
                    
                    <div class="governorate-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">الولايات</span>
                                <span class="stat-value">{{ $governorate->wilayats_count ?? 0 }} ولاية</span>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">المواقع السياحية</span>
                                <span class="stat-value">{{ $governorate->tourist_sites_count ?? 0 }} موقع</span>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">الخدمات السياحية</span>
                                <span class="stat-value">{{ $governorate->tourist_services_count ?? 0 }} خدمة</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-actions mt-4">
                        <a href="{{ route('tourism.wilayats') }}" class="btn btn-primary modern-btn">
                            <i class="fas fa-list me-2"></i>عرض جميع الولايات
                        </a>
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-outline-primary modern-btn">
                            <i class="fas fa-map-marker-alt me-2"></i>عرض المواقع السياحية
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="governorate-image-container">
                    <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" class="governorate-image">
                    <div class="image-overlay">
                        <div class="image-badge">
                            <i class="fas fa-crown"></i>
                            <span>{{ $governorate->name_ar }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tourist Sites -->
@if($featuredSites->count() > 0)
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center mb-5">
                    <h3 class="section-title">أبرز المواقع السياحية في {{ $governorate->name_ar }}</h3>
                    <p class="section-subtitle">اكتشف أجمل الأماكن السياحية في هذه المحافظة</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            @foreach($featuredSites as $site)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="featured-card">
                    <div class="featured-image-container">
                        @if($site->images->count() > 0)
                            @php $firstImage = $site->images->first(); @endphp
                            @if($firstImage->image_path)
                                <img src="{{ asset('storage/' . $firstImage->image_path) }}" class="featured-image" alt="{{ $site->name_ar }}">
                            @elseif($firstImage->image_url)
                                <img src="{{ $firstImage->image_url }}" class="featured-image" alt="{{ $site->name_ar }}">
                            @else
                                <img src="{{ asset('images/albadyah.jpg') }}" class="featured-image" alt="{{ $site->name_ar }}">
                            @endif
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" class="featured-image" alt="{{ $site->name_ar }}">
                        @endif
                        <div class="featured-overlay">
                            <a href="{{ route('tourism.tourist-site', $site->id) }}" class="featured-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="featured-content">
                        <h6 class="featured-title">{{ $site->name_ar }}</h6>
                        <p class="featured-subtitle">{{ $site->name_en }}</p>
                        
                        @if($site->location)
                            <div class="featured-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $site->location }}</span>
                            </div>
                        @endif
                        
                        <a href="{{ route('tourism.tourist-site', $site->id) }}" class="featured-link">
                            عرض التفاصيل <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($featuredSites->count() >= 4)
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary modern-btn">
                    <i class="fas fa-list me-2"></i>عرض جميع المواقع السياحية
                </a>
            </div>
        </div>
        @endif
    </div>
</section>
@endif

<!-- Governorate Information -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>معلومات عن {{ $governorate->name_ar }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="governorate-info">
                            <p class="lead">
                                {{ $governorate->name_ar }} هي واحدة من المحافظات الرائعة في سلطنة عُمان، 
                                وتتميز بتنوع جغرافي وثقافي مذهل يجعلها وجهة سياحية مثالية.
                            </p>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div class="info-text">
                                        <span class="info-label">عدد الولايات</span>
                                        <span class="info-value">{{ $governorate->wilayats_count ?? 0 }} ولاية</span>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-text">
                                        <span class="info-label">المواقع السياحية</span>
                                        <span class="info-value">{{ $governorate->tourist_sites_count ?? 0 }} موقع</span>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div class="info-text">
                                        <span class="info-label">الخدمات السياحية</span>
                                        <span class="info-value">{{ $governorate->tourist_services_count ?? 0 }} خدمة</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-map me-2"></i>الولايات في {{ $governorate->name_ar }}
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        @if($governorate->wilayats->count() > 0)
                            <div class="wilayats-list">
                                @foreach($governorate->wilayats as $wilayat)
                                <div class="wilayat-item">
                                    <div class="wilayat-icon">
                                        <i class="fas fa-map-pin"></i>
                                    </div>
                                    <div class="wilayat-text">
                                        <span class="wilayat-name">{{ $wilayat->name_ar }}</span>
                                        <small class="wilayat-count">{{ $wilayat->tourist_sites_count ?? 0 }} موقع سياحي</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('tourism.wilayats') }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-list me-2"></i>عرض جميع الولايات
                                </a>
                                <a href="{{ route('tourism.wilayat-details', $governorate->id) }}" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-map-marked-alt me-2"></i>عرض تفاصيل الولاية
                                </a>
                                <a href="https://www.google.com/maps/search/{{ urlencode($governorate->name_ar . ' سلطنة عمان') }}" target="_blank" class="btn btn-primary google-maps-btn w-100 mb-2">
                                    <i class="fab fa-google me-2"></i>جوجل ماب
                                </a>
                            </div>
                        @else
                            <p class="text-muted text-center">لا توجد ولايات متاحة حالياً</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="cta-card">
                    <h3 class="cta-title">اكتشف المزيد من {{ $governorate->name_ar }}</h3>
                    <p class="cta-subtitle">استكشف المواقع السياحية والخدمات المتاحة في هذه المحافظة الرائعة</p>
                    <div class="cta-buttons">
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary modern-btn">
                            <i class="fas fa-map-marker-alt me-2"></i>المواقع السياحية
                        </a>
                        <a href="{{ route('tourism.tourist-services') }}" class="btn btn-outline-primary modern-btn">
                            <i class="fas fa-concierge-bell me-2"></i>الخدمات السياحية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)),
        url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    /* Hero Section */
    .governorate-title {
        font-size: 4rem;
        font-weight: 700;
        color: white;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .governorate-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 2rem;
        font-weight: 400;
    }
    
    .governorate-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
        justify-content: center;
        align-items: stretch;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        flex: 1;
        min-width: 200px;
        max-width: 250px;
    }
    
    .stat-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
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
    }
    
    .stat-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2), 
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .stat-icon i {
        color: white;
        font-size: 1.3rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .stat-text {
        display: flex;
        flex-direction: column;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 1rem;
        color: white;
        font-weight: 600;
        line-height: 1.2;
    }
    
    .hero-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        border-radius: 25px;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.3);
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
        box-shadow: 0 12px 35px rgba(97, 76, 57, 0.4);
    }
    
    .btn-outline-primary {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .btn-outline-primary:hover {
        background: white;
        color: var(--primary-color);
        border-color: white;
    }
    
    .google-maps-btn {
        background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
        box-shadow: 0 8px 25px rgba(66, 133, 244, 0.4);
    }
    
    .google-maps-btn:hover {
        background: linear-gradient(135deg, #34a853 0%, #4285f4 100%);
        box-shadow: 0 12px 35px rgba(66, 133, 244, 0.5);
        transform: translateY(-3px) scale(1.02);
    }
    
    .google-maps-btn:active {
        transform: translateY(-1px) scale(1.01);
    }
    
    .governorate-image-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        margin: 2rem 0;
    }
    
    .governorate-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: all 0.3s ease;
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.3) 100%);
        display: flex;
        align-items: flex-end;
        padding: 1.5rem;
    }
    
    .image-badge {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-weight: 700;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .image-badge i {
        font-size: 1.2rem;
    }
    
    /* Featured Sites */
    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .section-subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .featured-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .featured-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    
    .featured-image-container {
        position: relative;
        overflow: hidden;
    }
    
    .featured-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: all 0.3s ease;
    }
    
    .featured-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .featured-card:hover .featured-overlay {
        opacity: 1;
    }
    
    .featured-card:hover .featured-image {
        transform: scale(1.1);
    }
    
    .featured-btn {
        background: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.2rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .featured-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }
    
    .featured-content {
        padding: 1.5rem;
    }
    
    .featured-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .featured-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .featured-location {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .featured-location i {
        margin-left: 0.5rem;
        color: var(--secondary-color);
    }
    
    .featured-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }
    
    .featured-link:hover {
        color: var(--primary-color);
        transform: translateX(-5px);
    }
    
    /* Content Cards */
    .content-card, .info-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .content-card:hover, .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .governorate-info .lead {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(97, 76, 57, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(97, 76, 57, 0.1);
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: rgba(97, 76, 57, 0.1);
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(97, 76, 57, 0.2);
    }
    
    .info-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        margin-left: 1rem;
        flex-shrink: 0;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .info-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .info-icon i {
        color: white;
        font-size: 1.1rem;
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
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: var(--primary-color);
        font-weight: 600;
        line-height: 1.2;
    }
    
    /* Wilayats List */
    .wilayats-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .wilayat-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    
    .wilayat-item:last-child {
        border-bottom: none;
    }
    
    .wilayat-item:hover {
        background: rgba(97, 76, 57, 0.05);
        border-radius: 10px;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .wilayat-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        margin-left: 1rem;
        flex-shrink: 0;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .wilayat-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2), 
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .wilayat-icon i {
        color: white;
        font-size: 1rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .wilayat-text {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* أيقونات العناوين */
    .card-title i {
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        font-size: 1.1em;
    }

    .card-header .fas,
    .card-header .fa {
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .wilayat-name {
        font-size: 0.95rem;
        color: var(--primary-color);
        font-weight: 600;
        line-height: 1.2;
    }
    
    .wilayat-count {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    /* CTA Section */
    .cta-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 25px;
        padding: 3rem 2rem;
        color: white;
        text-align: center;
        box-shadow: 0 20px 50px rgba(97, 76, 57, 0.3);
    }
    
    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .cta-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .governorate-stats {
            gap: 1.25rem;
        }
        
        .stat-item {
            min-width: 180px;
            max-width: 220px;
        }
        
        .governorate-title {
            font-size: 3.5rem;
        }
        
        .governorate-subtitle {
            font-size: 1.3rem;
        }
        
        .governorate-image-container {
            margin: 1.5rem 0;
        }
    }

    @media (max-width: 768px) {
        .governorate-title {
            font-size: 2.5rem;
        }
        
        .governorate-subtitle {
            font-size: 1.2rem;
        }
        
        .governorate-stats {
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }
        
        .stat-item {
            padding: 0.75rem 1rem;
            min-width: 250px;
            max-width: 300px;
        }
        
        .hero-actions {
            flex-direction: column;
        }
        
        .hero-actions .btn {
            width: 100%;
        }
        
        .governorate-image {
            height: 300px;
        }
        
        .governorate-image-container {
            margin: 1rem 0;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cta-buttons .btn {
            width: 100%;
            max-width: 300px;
        }
        
        .cta-title {
            font-size: 2rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .governorate-stats {
            gap: 0.75rem;
        }
        
        .stat-item {
            min-width: 200px;
            max-width: 250px;
            padding: 0.5rem 0.75rem;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            margin-left: 0.75rem;
        }
        
        .stat-icon i {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .governorate-title {
            font-size: 2rem;
        }
        
        .governorate-image {
            height: 250px;
        }
        
        .governorate-image-container {
            margin: 0.75rem 0;
        }
        
        .stat-item {
            min-width: 180px;
            max-width: 220px;
            padding: 0.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .cta-card {
            padding: 2rem 1rem;
        }
    }
</style>
@endpush
