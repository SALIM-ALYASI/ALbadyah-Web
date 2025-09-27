@extends('layouts.tourism')

@section('title', $touristService->name_ar . ' - البادية')
@section('description', $touristService->description_ar ? Str::limit($touristService->description_ar, 160) : 'اكتشف ' . $touristService->name_ar)

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <div class="service-header">
                        @if($touristService->serviceType)
                            <span class="service-type-badge">{{ $touristService->serviceType->name_ar }}</span>
                        @endif
                        <h1 class="service-title">{{ $touristService->name_ar }}</h1>
                        <h2 class="service-subtitle">{{ $touristService->name_en }}</h2>
                    </div>
                    
                    <div class="service-meta">
                        @if($touristService->governorate)
                            <a href="{{ route('tourism.governorate', $touristService->governorate->id) }}" class="meta-item meta-link">
                                <div class="meta-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">المحافظة</span>
                                    <span class="meta-value">{{ $touristService->governorate->name_ar }}</span>
                                </div>
                                <div class="meta-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif
                        
                        @if($touristService->wilayat)
                            <a href="{{ route('tourism.wilayat-details', $touristService->wilayat->id) }}" class="meta-item meta-link">
                                <div class="meta-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">الولاية</span>
                                    <span class="meta-value">{{ $touristService->wilayat->name_ar }}</span>
                                </div>
                                <div class="meta-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </a>
                        @endif
                        
                        @if($touristService->contact_phone)
                            <a href="tel:{{ $touristService->contact_phone }}" class="meta-item meta-link">
                                <div class="meta-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">الهاتف</span>
                                    <span class="meta-value">اتصل الآن</span>
                                </div>
                                <div class="meta-arrow">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                            </a>
                        @endif
                        
                        @if($touristService->contact_email)
                            <a href="mailto:{{ $touristService->contact_email }}" class="meta-item meta-link">
                                <div class="meta-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">البريد الإلكتروني</span>
                                    <span class="meta-value">إرسال رسالة</span>
                                </div>
                                <div class="meta-arrow">
                                    <i class="fas fa-envelope-open"></i>
                                </div>
                            </a>
                        @endif
                    </div>
                    
                    <div class="hero-actions mt-4">
                        <!-- Location Actions -->
                        <div class="location-actions-row mb-3">
                            @if($touristService->governorate)
                                <a href="{{ route('tourism.governorate', $touristService->governorate->id) }}" 
                                   class="btn btn-outline-primary modern-btn me-2 mb-2">
                                    <i class="fas fa-building me-2"></i>{{ $touristService->governorate->name_ar }}
                                </a>
                            @endif
                            
                            @if($touristService->wilayat)
                                <a href="{{ route('tourism.wilayat', $touristService->wilayat->id) }}" 
                                   class="btn btn-outline-primary modern-btn me-2 mb-2">
                                    <i class="fas fa-map-pin me-2"></i>{{ $touristService->wilayat->name_ar }}
                                </a>
                            @endif
                        </div>
                        
                        <!-- Service Actions -->
                        <div class="service-actions-row">
                            @if($touristService->contact_phone)
                                <a href="tel:{{ $touristService->contact_phone }}" class="btn btn-outline-primary modern-btn me-2 mb-2">
                                    <i class="fas fa-phone me-2"></i>اتصل بنا
                                </a>
                            @endif
                            
                            
                            
                            @php
                                $locationQuery = '';
                                if($touristService->governorate && $touristService->wilayat) {
                                    $locationQuery = $touristService->wilayat->name_ar . ' ' . $touristService->governorate->name_ar . ' سلطنة عمان';
                                } elseif($touristService->governorate) {
                                    $locationQuery = $touristService->governorate->name_ar . ' سلطنة عمان';
                                } else {
                                    $locationQuery = $touristService->name_ar . ' سلطنة عمان';
                                }
                            @endphp
                            
                            <a href="https://www.google.com/maps/search/{{ urlencode($locationQuery) }}" 
                               target="_blank" 
                               class="btn btn-primary modern-btn google-maps-btn mb-2">
                                <i class="fab fa-google me-2"></i>جوجل ماب
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="service-image-container">
                    @if($touristService->has_image)
                        <div class="service-image">
                            <img src="{{ $touristService->image_url }}" 
                                 alt="{{ $touristService->name_ar }} - {{ $touristService->serviceType->name_ar ?? 'خدمة سياحية' }}" 
                                 class="img-fluid rounded shadow"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="service-overlay">
                                <div class="service-badge">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $touristService->name_ar }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(!$touristService->has_image)
                        <div class="service-placeholder" style="display: none;">
                            <div class="service-icon">
                                @if($touristService->serviceType)
                                    @switch($touristService->serviceType->name_en)
                                        @case('Hotel')
                                            <i class="fas fa-hotel"></i>
                                            @break
                                        @case('Restaurant')
                                            <i class="fas fa-utensils"></i>
                                            @break
                                        @case('Transportation')
                                            <i class="fas fa-car"></i>
                                            @break
                                        @case('Tour Guide')
                                            <i class="fas fa-map-marked-alt"></i>
                                            @break
                                        @case('Shopping')
                                            <i class="fas fa-shopping-bag"></i>
                                            @break
                                        @default
                                            <i class="fas fa-concierge-bell"></i>
                                    @endswitch
                                @else
                                    <i class="fas fa-concierge-bell"></i>
                                @endif
                            </div>
                            <div class="service-overlay">
                                <div class="service-badge">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $touristService->name_ar }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Fallback placeholder for broken images -->
                    <div class="service-placeholder fallback-placeholder" style="display: none;">
                        <div class="service-icon">
                            @if($touristService->serviceType)
                                @switch($touristService->serviceType->name_en)
                                    @case('Hotel')
                                        <i class="fas fa-hotel"></i>
                                        @break
                                    @case('Restaurant')
                                        <i class="fas fa-utensils"></i>
                                        @break
                                    @case('Transportation')
                                        <i class="fas fa-car"></i>
                                        @break
                                    @case('Tour Guide')
                                        <i class="fas fa-map-marked-alt"></i>
                                        @break
                                    @case('Shopping')
                                        <i class="fas fa-shopping-bag"></i>
                                        @break
                                    @default
                                        <i class="fas fa-concierge-bell"></i>
                                @endswitch
                            @else
                                <i class="fas fa-concierge-bell"></i>
                            @endif
                        </div>
                        <div class="service-overlay">
                            <div class="service-badge">
                                <i class="fas fa-image"></i>
                                <span>صورة غير متاحة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Description Section -->
@if($touristService->description_ar)
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>وصف الخدمة
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="description-section">
                            <div class="description-content">
                                {{ $touristService->description_ar }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Contact Information Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="contact-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-address-book me-2"></i>معلومات الاتصال
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($touristService->contact_phone)
                            <div class="col-md-6 mb-3">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-text">
                                        <span class="contact-label">الهاتف</span>
                                        <a href="tel:{{ $touristService->contact_phone }}" class="contact-link">
                                            {{ $touristService->contact_phone }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($touristService->contact_email)
                            <div class="col-md-6 mb-3">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-text">
                                        <span class="contact-label">البريد الإلكتروني</span>
                                        <a href="mailto:{{ $touristService->contact_email }}" class="contact-link">
                                            {{ $touristService->contact_email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($touristService->website)
                            <div class="col-md-6 mb-3">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="contact-text">
                                        <span class="contact-label">الموقع الإلكتروني</span>
                                        <a href="{{ $touristService->website }}" target="_blank" class="contact-link">
                                            زيارة الموقع
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Location Information Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="location-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt me-2"></i>معلومات الموقع
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($touristService->governorate)
                            <div class="col-md-6 mb-3">
                                <div class="location-item">
                                    <div class="location-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="location-text">
                                        <span class="location-label">المحافظة</span>
                                        <span class="location-value">
                                            <a href="{{ route('tourism.governorate', $touristService->governorate->id) }}" class="info-link">
                                                {{ $touristService->governorate->name_ar }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($touristService->wilayat)
                            <div class="col-md-6 mb-3">
                                <div class="location-item">
                                    <div class="location-icon">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div class="location-text">
                                        <span class="location-label">الولاية</span>
                                        <span class="location-value">
                                            <a href="{{ route('tourism.wilayat', $touristService->wilayat->id) }}" class="info-link">
                                                {{ $touristService->wilayat->name_ar }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services -->
@if($relatedServices->count() > 0)
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center mb-5">
                    <h3 class="section-title">خدمات مماثلة</h3>
                    <p class="section-subtitle">اكتشف المزيد من الخدمات السياحية المشابهة</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            @foreach($relatedServices as $service)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="related-card">
                    <div class="related-header">
                        @if($service->serviceType)
                            <span class="related-badge">{{ $service->serviceType->name_ar }}</span>
                        @endif
                        <h6 class="related-title">{{ $service->name_ar }}</h6>
                        <p class="related-subtitle">{{ $service->name_en }}</p>
                    </div>
                    
                    <div class="related-content">
                        @if($service->description_ar)
                            <p class="related-description">{{ Str::limit($service->description_ar, 80) }}</p>
                        @endif
                        
                        <div class="related-meta">
                            @if($service->governorate)
                                <div class="related-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $service->governorate->name_ar }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('tourism.tourist-service', $service->id) }}" class="related-link">
                            عرض التفاصيل <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('tourism.tourist-services') }}" class="btn btn-primary modern-btn">
                    <i class="fas fa-list me-2"></i>عرض جميع الخدمات السياحية
                </a>
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
                <div class="cta-card">
                    <h3 class="cta-title">اكتشف المزيد من الخدمات السياحية</h3>
                    <p class="cta-subtitle">استكشف جميع الخدمات السياحية المتاحة في سلطنة عُمان</p>
                    <div class="cta-buttons">
                        <a href="{{ route('tourism.tourist-services') }}" class="btn btn-primary modern-btn">
                            <i class="fas fa-concierge-bell me-2"></i>جميع الخدمات
                        </a>
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-outline-primary modern-btn">
                            <i class="fas fa-map-marker-alt me-2"></i>المواقع السياحية
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
    /* CSS Variables */
    :root {
        --primary-color: #614c39;
        --secondary-color: #a1815a;
        --accent-color: #deb47a;
        --highlight-color: #c19b6c;
        --neutral-color: #236b8b;
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --bg-light: #f8f9fa;
        --white: #ffffff;
        --shadow-color: rgba(97, 76, 57, 0.3);
        --shadow-light: rgba(0, 0, 0, 0.1);
    }
    
    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Cairo', sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
        background-color: var(--white);
    }
    
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)),
        url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(97, 76, 57, 0.1);
        z-index: 1;
    }
    
    .hero-section .container {
        position: relative;
        z-index: 2;
    }
    
    /* Hero Section */
    .service-header {
        margin-bottom: 2rem;
    }
    
    .service-type-badge {
        background: linear-gradient(135deg, var(--neutral-color) 0%, var(--primary-color) 100%);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(107, 139, 138, 0.3);
    }
    
    .service-title {
        font-size: 4rem;
        font-weight: 700;
        color: white;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .service-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 2rem;
        font-weight: 400;
    }
    
    .service-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .meta-link {
        text-decoration: none !important;
        color: inherit !important;
        cursor: pointer;
    }

    .meta-link:visited,
    .meta-link:focus,
    .meta-link:hover,
    .meta-link:active {
        color: inherit !important;
        text-decoration: none !important;
    }

    .meta-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .meta-item:hover::before {
        left: 100%;
    }
    
    .meta-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        border-color: rgba(255, 255, 255, 0.4);
    }
    
    .meta-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        border-radius: 50%;
        margin-left: 1rem;
        flex-shrink: 0;
    }
    
    .meta-icon i {
        color: white;
        font-size: 1.2rem;
    }
    
    .meta-text {
        display: flex;
        flex-direction: column;
    }
    
    .meta-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .meta-value {
        font-size: 1rem;
        color: white;
        font-weight: 600;
        line-height: 1.2;
    }

    .meta-arrow {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        margin-right: 0.5rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .meta-arrow i {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .meta-item:hover .meta-arrow {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .meta-item:hover .meta-arrow i {
        color: white;
        transform: translateX(-2px);
    }
    
    .hero-actions {
        margin-top: 2rem;
    }
    
    .location-actions-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .service-actions-row {
        display: flex;
        gap: 0.75rem;
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
        box-shadow: 0 12px 35px var(--shadow-color);
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
    
    /* Service Image Container */
    .service-image-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }
    
    /* Service Image */
    .service-image {
        position: relative;
        height: 400px;
        overflow: hidden;
    }
    
    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .service-image:hover img {
        transform: scale(1.05);
    }
    
    .service-placeholder {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .service-icon {
        font-size: 4rem;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    .service-overlay {
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
    
    .service-badge {
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
    
    .service-badge i {
        font-size: 1.2rem;
    }
    
    .fallback-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
        border: 2px dashed var(--primary-color);
        opacity: 0.8;
    }
    
    .fallback-placeholder .service-icon {
        color: var(--primary-color);
    }
    
    .fallback-placeholder .service-badge {
        background: rgba(97, 76, 57, 0.9);
        color: white;
    }
    
    /* Content Cards */
    .content-card, .info-card, .contact-card, .location-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .content-card:hover, .info-card:hover, .contact-card:hover {
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

    .card-title i {
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        font-size: 1.2em;
    }

    .card-header .fas,
    .card-header .fa {
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .description-section {
        margin-bottom: 2rem;
    }
    
    .description-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .description-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: justify;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(97, 76, 57, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(97, 76, 57, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
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
    
    .info-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .info-link:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }
    
    /* Contact Items */
    .contact-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    
    .contact-item:last-child {
        border-bottom: none;
    }
    
    .contact-item:hover {
        background: rgba(97, 76, 57, 0.05);
        border-radius: 10px;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .contact-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 50%;
        margin-left: 1rem;
        flex-shrink: 0;
    }
    
    .contact-icon i {
        color: white;
        font-size: 0.9rem;
    }
    
    .contact-text {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .contact-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .contact-link:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }
    
    /* Location Items */
    .location-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    
    .location-item:last-child {
        border-bottom: none;
    }
    
    .location-item:hover {
        background: rgba(97, 76, 57, 0.05);
        border-radius: 10px;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .location-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        box-shadow: 0 4px 15px rgba(97, 76, 57, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .location-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.2) 0%, 
                    rgba(255, 255, 255, 0.1) 50%, 
                    rgba(255, 255, 255, 0.2) 100%);
        border-radius: 12px;
        box-shadow: 
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .location-icon i {
        color: white;
        font-size: 1.1rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .location-text {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .location-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .location-value {
        font-size: 1rem;
        color: var(--primary-color);
        font-weight: 600;
        line-height: 1.2;
    }
    
    .location-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .location-actions .btn {
        border-radius: 8px;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .location-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* Related Services */
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
    
    .related-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    
    .related-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }
    
    .related-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.75rem;
    }
    
    .related-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .related-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .related-content {
        padding: 1.5rem;
    }
    
    .related-description {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .related-meta {
        margin-bottom: 1rem;
    }
    
    .related-location {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .related-location i {
        margin-left: 0.5rem;
        color: var(--secondary-color);
    }
    
    .related-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }
    
    .related-link:hover {
        color: var(--primary-color);
        transform: translateX(-5px);
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
        .hero-section {
            min-height: 50vh;
        }
        
        .service-title {
            font-size: 3rem;
        }
        
        .service-subtitle {
            font-size: 1.3rem;
        }
        
        .service-meta {
            gap: 1rem;
        }
        
        .meta-item {
            padding: 0.75rem 1rem;
        }
        
        .meta-arrow {
            width: 30px;
            height: 30px;
        }
        
        .meta-arrow i {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-section {
            min-height: 40vh;
            background-attachment: scroll;
        }
        
        .service-title {
            font-size: 2.2rem;
            line-height: 1.1;
        }
        
        .service-subtitle {
            font-size: 1.1rem;
        }
        
        .service-meta {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .meta-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
        }
        
        .meta-icon {
            width: 40px;
            height: 40px;
            margin-left: 0.75rem;
        }
        
        .meta-icon i {
            font-size: 1rem;
        }
        
        .hero-actions {
            margin-top: 1.5rem;
        }
        
        .location-actions-row,
        .service-actions-row {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .modern-btn {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .service-placeholder {
            height: 250px;
        }
        
        .service-icon {
            font-size: 2.5rem;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }
        
        .cta-buttons .btn {
            width: 100%;
            max-width: 280px;
        }
        
        .cta-title {
            font-size: 1.8rem;
        }
        
        .cta-subtitle {
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .related-card {
            margin-bottom: 1rem;
        }
        
        .contact-item,
        .location-item {
            padding: 0.75rem 0;
        }
        
        .contact-icon,
        .location-icon {
            width: 35px;
            height: 35px;
            margin-left: 0.75rem;
        }
        
        .location-icon {
            margin-right: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .service-title {
            font-size: 2rem;
        }
        
        .service-placeholder {
            height: 250px;
        }
        
        .service-icon {
            font-size: 2.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .cta-card {
            padding: 2rem 1rem;
        }
        
        .meta-arrow {
            width: 25px;
            height: 25px;
        }
        
        .meta-arrow i {
            font-size: 0.7rem;
        }
        
        .fallback-placeholder {
            height: 200px;
        }
        
        .fallback-placeholder .service-icon {
            font-size: 2rem;
        }
    }
    
    /* Performance Optimizations */
    .hero-section {
        will-change: transform;
    }
    
    .modern-btn {
        will-change: transform, box-shadow;
    }
    
    .service-image img {
        will-change: transform;
    }
    
    /* Accessibility Improvements */
    .modern-btn:focus,
    .btn-outline-primary:focus {
        outline: 3px solid rgba(255, 255, 255, 0.5);
        outline-offset: 2px;
    }
    
    .contact-link:focus,
    .info-link:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
        border-radius: 4px;
    }
    
    /* Loading States */
    .service-image img[loading="lazy"] {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .service-image img[loading="lazy"].loaded {
        opacity: 1;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading image enhancement
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
        
        img.addEventListener('error', function() {
            const fallback = this.parentElement.querySelector('.fallback-placeholder');
            if (fallback) {
                this.style.display = 'none';
                fallback.style.display = 'flex';
            }
        });
    });
    
    // Smooth scroll for anchor links
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
    
    // Performance: Preload critical images
    const criticalImages = document.querySelectorAll('.hero-section img, .service-image img');
    criticalImages.forEach(img => {
        if (img.src && !img.complete) {
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
