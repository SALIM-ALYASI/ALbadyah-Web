@extends('layouts.tourism')

@section('title', $wilayat->name_ar . ' - البادية')
@section('description', 'اكتشف ' . $wilayat->name_ar . ' وجميع المواقع السياحية والخدمات المتاحة')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1 class="wilayat-title">{{ $wilayat->name_ar }}</h1>
                    <h2 class="wilayat-subtitle">{{ $wilayat->name_en }}</h2>
                    <p class="wilayat-description">{{ $wilayat->description ?? 'ولاية جميلة في سلطنة عُمان' }}</p>
                    
                    <div class="wilayat-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">المحافظة</span>
                                <span class="stat-value">{{ $wilayat->governorate->name_ar ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">المواقع السياحية</span>
                                <span class="stat-value">{{ $wilayat->touristSites->count() }} موقع</span>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="stat-text">
                                <span class="stat-label">الخدمات السياحية</span>
                                <span class="stat-value">{{ $wilayat->touristServices->count() }} خدمة</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-actions mt-4">
                        <div class="action-buttons-container">
                            <div class="primary-actions">
                                <a href="{{ route('tourism.governorates') }}" class="btn btn-outline-light btn-lg me-2 mb-2">
                                    <i class="fas fa-arrow-right me-2"></i>العودة للمحافظات
                                </a>
                                <a href="{{ route('tourism.wilayat-details', $wilayat->governorate_id) }}" class="btn btn-light btn-lg me-2 mb-2">
                                    <i class="fas fa-map-marked-alt me-2"></i>تفاصيل المحافظة
                                </a>
                                <a href="{{ route('tourism.wilayats') }}" class="btn btn-outline-light btn-lg me-2 mb-2">
                                    <i class="fas fa-list me-2"></i>جميع الولايات
                                </a>
                            </div>
                            
                            <div class="map-action-container">
                                <a href="https://www.google.com/maps/search/{{ urlencode($wilayat->name_ar . ' ' . $wilayat->governorate->name_ar . ' سلطنة عمان') }}" target="_blank" class="btn map-btn btn-lg">
                                    <div class="map-btn-content">
                                        <div class="map-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="map-text">
                                            <span class="map-label">موقع على الخريطة</span>
                                            <span class="map-subtitle">جوجل ماب</span>
                                        </div>
                                        <div class="map-arrow">
                                            <i class="fas fa-external-link-alt"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tourist Sites Section -->
@if($wilayat->touristSites->count() > 0)
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">المواقع السياحية في {{ $wilayat->name_ar }}</h2>
                <p class="section-subtitle">اكتشف أجمل المواقع السياحية في {{ $wilayat->name_ar }}</p>
            </div>
        </div>

        <div class="row">
            @foreach($wilayat->touristSites as $site)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="site-card">
                    <div class="site-image">
                        @if($site->images && count($site->images) > 0)
                            @php $firstSiteImage = $site->images->first(); @endphp
                            @if($firstSiteImage->image_path)
                                <img src="{{ asset('storage/' . $firstSiteImage->image_path) }}" alt="{{ $site->name_ar }}" class="img-fluid">
                            @elseif($firstSiteImage->image_url)
                                <img src="{{ $firstSiteImage->image_url }}" alt="{{ $site->name_ar }}" class="img-fluid">
                            @else
                                <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $site->name_ar }}" class="img-fluid">
                            @endif
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $site->name_ar }}" class="img-fluid">
                        @endif
                    </div>
                    <div class="site-content">
                        <h5 class="site-title">{{ $site->name_ar }}</h5>
                        <p class="site-name-en">{{ $site->name_en }}</p>
                        <p class="site-description">{{ Str::limit($site->description_ar, 100) }}</p>
                        <div class="site-info">
                            <div class="info-item">
                                <i class="fas fa-images"></i>
                                <span>{{ count($site->images ?? []) }} صورة</span>
                            </div>
                        </div>
                        <a href="{{ route('tourism.tourist-site', $site->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye me-2"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Tourist Services Section -->
@if($wilayat->touristServices->count() > 0)
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">الخدمات السياحية في {{ $wilayat->name_ar }}</h2>
                <p class="section-subtitle">اكتشف أفضل الخدمات السياحية في {{ $wilayat->name_ar }}</p>
            </div>
        </div>

        <div class="row">
            @foreach($wilayat->touristServices as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card">
                    <div class="service-image">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->name_ar }}" class="img-fluid">
                        @elseif($service->image_url)
                            <img src="{{ $service->image_url }}" alt="{{ $service->name_ar }}" class="img-fluid">
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $service->name_ar }}" class="img-fluid">
                        @endif
                        <div class="service-type">
                            <span class="type-badge">{{ $service->type ?? 'خدمة سياحية' }}</span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h5 class="service-title">{{ $service->name_ar }}</h5>
                        <p class="service-name-en">{{ $service->name_en }}</p>
                        <p class="service-description">{{ Str::limit($service->description_ar, 100) }}</p>
                        <div class="service-info">
                            <div class="info-item">
                                <i class="fas fa-images"></i>
                                <span>{{ count($service->images ?? []) }} صورة</span>
                            </div>
                        </div>
                        <a href="{{ route('tourism.tourist-service', $service->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye me-2"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($wilayat->touristSites->count() == 0 && $wilayat->touristServices->count() == 0)
<!-- Empty State -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="empty-state text-center py-5">
                    <i class="fas fa-map-marked-alt fa-4x mb-4"></i>
                    <h4>اكتشف جمال {{ $wilayat->name_ar }}</h4>
                    <p>نعمل على إضافة المحتوى السياحي المميز لهذه المنطقة الجميلة</p>
                    <div class="mt-4">
                        <a href="{{ route('tourism.governorates') }}" class="btn btn-primary me-3">
                            <i class="fas fa-building me-2"></i>استكشف المحافظات
                        </a>
                        <a href="{{ route('tourism.wilayats') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>جميع الولايات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>استكشف المزيد</h3>
                <p>اكتشف جمال سلطنة عُمان في المحافظات والولايات الأخرى</p>
                <div class="cta-buttons">
                    <a href="{{ route('tourism.governorates') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-building me-2"></i>تصفح المحافظات
                    </a>
                    <a href="{{ route('tourism.wilayats') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list me-2"></i>عرض جميع الولايات
                    </a>
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
    
    /* Wilayat Title */
    .wilayat-title {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .wilayat-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1rem;
        font-weight: 400;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .wilayat-description {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Wilayat Stats */
    .wilayat-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        backdrop-filter: blur(15px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border: 2px solid rgba(161, 129, 90, 0.2);
        position: relative;
        overflow: hidden;
    }

    .wilayat-stats::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #a1815a, #8b6f47, #a1815a);
        background-size: 200% 100%;
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .wilayat-stats .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-align: center;
    }

    .wilayat-stats .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #a1815a, #8b6f47);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(15px);
        border: 3px solid rgba(161, 129, 90, 0.3);
        box-shadow: 0 8px 20px rgba(161, 129, 90, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .wilayat-stats .stat-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 25px rgba(161, 129, 90, 0.4);
    }

    .wilayat-stats .stat-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
        border-radius: 50%;
    }

    .wilayat-stats .stat-icon i {
        color: white;
        font-size: 1.5rem;
        filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.4));
        position: relative;
        z-index: 2;
    }

    .wilayat-stats .stat-text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .wilayat-stats .stat-label {
        font-size: 1rem;
        color: #a1815a;
        margin-bottom: 0.25rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wilayat-stats .stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #8b6f47;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #a1815a, #8b6f47);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Site and Service Cards */
    .site-card,
    .service-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .site-card:hover,
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .site-image,
    .service-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .site-image img,
    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .site-card:hover .site-image img,
    .service-card:hover .service-image img {
        transform: scale(1.05);
    }

    .no-image {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        font-size: 2rem;
    }

    .service-type {
        position: absolute;
        top: 1rem;
        left: 1rem;
    }

    .type-badge {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .site-content,
    .service-content {
        padding: 1.5rem;
    }

    .site-title,
    .service-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .site-name-en,
    .service-name-en {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.75rem;
        font-style: italic;
    }

    .site-description,
    .service-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .site-info,
    .service-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #666;
    }

    .info-item i {
        font-size: 0.7rem;
        color: var(--accent-color);
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, rgba(161, 129, 90, 0.05), rgba(139, 111, 71, 0.08));
        border-radius: 25px;
        border: 2px solid rgba(161, 129, 90, 0.15);
        position: relative;
        overflow: hidden;
        padding: 3rem 2rem;
        box-shadow: 0 10px 30px rgba(161, 129, 90, 0.1);
    }

    .empty-state::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(161, 129, 90, 0.03) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .empty-state i {
        color: #a1815a;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .empty-state h4 {
        color: #8b6f47;
        font-weight: 600;
        position: relative;
        z-index: 2;
    }

    .empty-state p {
        color: #a1815a;
        position: relative;
        z-index: 2;
    }

    .empty-state .btn {
        background: linear-gradient(135deg, #a1815a, #8b6f47);
        border: none;
        border-radius: 25px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .empty-state .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(161, 129, 90, 0.3);
    }

    /* Hero Actions Styling */
    .action-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }

    .primary-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }

    .map-action-container {
        width: 100%;
        max-width: 350px;
    }

    .map-btn {
        background: linear-gradient(135deg, #a1815a, #8b6f47) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 0 !important;
        width: 100%;
        height: 80px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(161, 129, 90, 0.3);
        transition: all 0.3s ease;
    }

    .map-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(161, 129, 90, 0.4);
        background: linear-gradient(135deg, #8b6f47, #a1815a) !important;
    }

    .map-btn-content {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        height: 100%;
        position: relative;
        z-index: 2;
    }

    .map-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .map-icon i {
        font-size: 1.5rem;
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .map-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .map-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.25rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        display: block;
    }

    .map-subtitle {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        display: block;
    }

    .map-arrow {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .map-arrow i {
        font-size: 1rem;
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .map-btn:hover .map-arrow {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .map-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
        z-index: 1;
    }

    .map-btn:hover::before {
        left: 100%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .wilayat-title {
            font-size: 2.2rem;
        }

        .wilayat-subtitle {
            font-size: 1.2rem;
        }

        .wilayat-description {
            font-size: 1rem;
        }

        .wilayat-stats {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .wilayat-stats .stat-item {
            flex-direction: row;
            justify-content: center;
        }

        .hero-actions .btn {
            margin-bottom: 0.5rem;
        }

        .action-buttons-container {
            gap: 1rem;
        }

        .primary-actions {
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .primary-actions .btn {
            width: 100%;
            max-width: 280px;
        }

        .map-action-container {
            max-width: 300px;
        }

        .map-btn {
            height: 70px;
        }

        .map-btn-content {
            padding: 0.8rem 1rem;
        }

        .map-icon {
            width: 45px;
            height: 45px;
            margin-left: 0.8rem;
        }

        .map-icon i {
            font-size: 1.3rem;
        }

        .map-label {
            font-size: 1rem;
        }

        .map-subtitle {
            font-size: 0.8rem;
        }

        .map-arrow {
            width: 35px;
            height: 35px;
        }

        .map-arrow i {
            font-size: 0.9rem;
        }

        .site-content,
        .service-content {
            padding: 1rem;
        }

        .site-info,
        .service-info {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .wilayat-title {
            font-size: 1.8rem;
        }

        .wilayat-subtitle {
            font-size: 1rem;
        }

        .wilayat-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .wilayat-stats .stat-item {
            flex-direction: column;
            text-align: center;
        }

        .site-image,
        .service-image {
            height: 180px;
        }

        .action-buttons-container {
            gap: 0.8rem;
        }

        .map-action-container {
            max-width: 280px;
        }

        .map-btn {
            height: 65px;
        }

        .map-btn-content {
            padding: 0.7rem 0.8rem;
        }

        .map-icon {
            width: 40px;
            height: 40px;
            margin-left: 0.6rem;
        }

        .map-icon i {
            font-size: 1.2rem;
        }

        .map-label {
            font-size: 0.95rem;
        }

        .map-subtitle {
            font-size: 0.75rem;
        }

        .map-arrow {
            width: 32px;
            height: 32px;
        }

        .map-arrow i {
            font-size: 0.8rem;
        }
    }
</style>
@endpush
