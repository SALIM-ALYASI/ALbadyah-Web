@extends('layouts.tourism')

@section('title', 'تفاصيل ولايات ' . $governorate->name_ar . ' - البادية')
@section('description', 'اكتشف جميع الولايات والمواقع السياحية والخدمات في ' . $governorate->name_ar)

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1 class="hero-title">{{ $governorate->name_ar }}</h1>
                    <h2 class="hero-subtitle">{{ $governorate->name_en }}</h2>
                    <p class="hero-description">اكتشف جميع ولايات {{ $governorate->name_ar }} والمواقع السياحية والخدمات المتميزة</p>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorate->wilayats->count() }}</div>
                            <div class="stat-label">ولاية</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorate->wilayats->sum(function($wilayat) { return $wilayat->touristSites->count(); }) }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorate->wilayats->sum(function($wilayat) { return $wilayat->touristServices->count(); }) }}</div>
                            <div class="stat-label">خدمة سياحية</div>
                        </div>
                    </div>
                    
                    <div class="hero-actions mt-4">
                        <a href="{{ route('tourism.governorates') }}" class="btn btn-outline-light btn-lg me-3">
                            <i class="fas fa-arrow-right me-2"></i>العودة للمحافظات
                        </a>
                        <a href="{{ route('tourism.wilayats') }}" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-list me-2"></i>عرض جميع الولايات
                        </a>
                        <a href="https://www.google.com/maps/search/{{ urlencode($governorate->name_ar . ' سلطنة عمان') }}" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-map-marker-alt me-2"></i>جوجل ماب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wilayats Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">ولايات {{ $governorate->name_ar }}</h2>
                <p class="section-subtitle">اكتشف جميع الولايات والمواقع السياحية والخدمات في {{ $governorate->name_ar }}</p>
            </div>
        </div>

        @foreach($governorate->wilayats as $wilayat)
        <div class="wilayat-section mb-5">
            <!-- Wilayat Header -->
            <div class="wilayat-header">
                <div class="row align-items-center">
                    <!-- صورة الولاية -->
                    <div class="col-lg-3 col-md-4">
                        <div class="wilayat-image-container" style="
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
                                top: -40px;
                                right: -40px;
                                width: 80px;
                                height: 80px;
                                background: rgba(97, 76, 57, 0.1);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            <div style="
                                position: absolute;
                                bottom: -25px;
                                left: -25px;
                                width: 50px;
                                height: 50px;
                                background: rgba(97, 76, 57, 0.08);
                                border-radius: 50%;
                                z-index: 1;
                            "></div>
                            
                            @if($wilayat->has_image)
                                <img src="{{ $wilayat->image_url }}" 
                                     alt="{{ $wilayat->name_ar }}" 
                                     class="img-fluid rounded shadow-lg"
                                     style="
                                        width: 100%;
                                        height: 200px;
                                        object-fit: cover;
                                        border-radius: 15px;
                                        position: relative;
                                        z-index: 2;
                                        transition: transform 0.3s ease;
                                     "
                                     onmouseover="this.style.transform='scale(1.02)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="
                                    height: 200px;
                                    background: rgba(255,255,255,0.8);
                                    border-radius: 15px;
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
                    </div>
                    
                    <!-- معلومات الولاية -->
                    <div class="col-lg-5 col-md-8">
                        <h3 class="wilayat-title">
                            <i class="fas fa-map-pin me-2"></i>{{ $wilayat->name_ar }}
                            <span class="wilayat-name-en">{{ $wilayat->name_en }}</span>
                        </h3>
                        <p class="wilayat-description">{{ $wilayat->description ?? 'ولاية جميلة في ' . $governorate->name_ar }}</p>
                        
                        @if($wilayat->website_url)
                        <div class="mt-3">
                            <a href="{{ $wilayat->website_url }}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>
                                زيارة الموقع 
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- إحصائيات الولاية -->
                    <div class="col-lg-4 text-end">
                        <div class="wilayat-stats">
                            <span class="stat-badge">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $wilayat->touristSites->count() }} موقع سياحي
                            </span>
                            <span class="stat-badge">
                                <i class="fas fa-concierge-bell"></i>
                                {{ $wilayat->touristServices->count() }} خدمة سياحية
                            </span>
                            <a href="{{ route('tourism.wilayat', $wilayat->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tourist Sites -->
            @if($wilayat->touristSites->count() > 0)
            <div class="content-section">
                <h4 class="section-subtitle">
                    <i class="fas fa-camera me-2"></i>المواقع السياحية في {{ $wilayat->name_ar }}
                </h4>
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
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $site->governorate->name_ar ?? 'غير محدد' }}</span>
                                    </div>
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
            @endif

            <!-- Tourist Services -->
            @if($wilayat->touristServices->count() > 0)
            <div class="content-section">
                <h4 class="section-subtitle">
                    <i class="fas fa-concierge-bell me-2"></i>الخدمات السياحية في {{ $wilayat->name_ar }}
                </h4>
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
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $service->governorate->name_ar ?? 'غير محدد' }}</span>
                                    </div>
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
            @endif

            @if($wilayat->touristSites->count() == 0 && $wilayat->touristServices->count() == 0)
            <div class="empty-state text-center py-5">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد مواقع أو خدمات سياحية متاحة حالياً</h5>
                <p class="text-muted">سيتم إضافة المحتوى قريباً</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>استكشف المزيد من المحافظات</h3>
                <p>اكتشف جمال سلطنة عُمان في جميع المحافظات</p>
                <a href="{{ route('tourism.governorates') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-building me-2"></i>تصفح جميع المحافظات
                </a>
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
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1rem;
        font-weight: 400;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-description {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Hero Stats */
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin: 2rem 0;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .hero-stats .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
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

    /* Wilayat Section */
    .wilayat-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 3rem;
    }

    .wilayat-header {
        border-bottom: 2px solid rgba(97, 76, 57, 0.1);
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }

    .wilayat-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .wilayat-name-en {
        font-size: 1.2rem;
        font-weight: 400;
        color: #666;
        margin-right: 1rem;
    }

    .wilayat-description {
        font-size: 1.1rem;
        color: #666;
        margin: 0;
        line-height: 1.6;
    }

    .wilayat-stats {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(97, 76, 57, 0.1);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .stat-badge i {
        font-size: 0.8rem;
    }

    /* Content Sections */
    .content-section {
        margin-bottom: 2rem;
    }

    .content-section:last-child {
        margin-bottom: 0;
    }

    .section-subtitle {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(97, 76, 57, 0.1);
    }

    /* Site Cards */
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
        background: rgba(97, 76, 57, 0.05);
        border-radius: 15px;
        border: 2px dashed rgba(97, 76, 57, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .hero-description {
            font-size: 1rem;
        }

        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
        }

        .hero-stats .stat-number {
            font-size: 2rem;
        }

        .wilayat-section {
            padding: 1.5rem;
        }

        .wilayat-title {
            font-size: 1.6rem;
        }

        .wilayat-name-en {
            font-size: 1rem;
            margin-right: 0.5rem;
        }

        .wilayat-stats {
            margin-top: 1rem;
        }

        .stat-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }

        .wilayat-image-container {
            padding: 15px;
        }

        .wilayat-image-container img {
            height: 150px;
        }

        .section-subtitle {
            font-size: 1.2rem;
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
        .hero-title {
            font-size: 1.8rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .hero-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .hero-stats .stat-number {
            font-size: 1.8rem;
        }

        .wilayat-section {
            padding: 1rem;
        }

        .wilayat-title {
            font-size: 1.4rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .wilayat-name-en {
            margin-right: 0;
            margin-top: 0.25rem;
        }

        .wilayat-image-container {
            padding: 10px;
            margin-bottom: 1rem;
        }

        .wilayat-image-container img {
            height: 120px;
        }

        .site-image,
        .service-image {
            height: 180px;
        }
    }
</style>
@endpush
