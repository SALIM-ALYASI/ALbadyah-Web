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
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">المحافظة</span>
                                    <span class="meta-value">{{ $touristService->governorate->name_ar }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($touristService->wilayat)
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">الولاية</span>
                                    <span class="meta-value">{{ $touristService->wilayat->name_ar }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($touristService->contact_phone)
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">الهاتف</span>
                                    <span class="meta-value">{{ $touristService->contact_phone }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($touristService->contact_email)
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="meta-text">
                                    <span class="meta-label">البريد الإلكتروني</span>
                                    <span class="meta-value">{{ $touristService->contact_email }}</span>
                                </div>
                            </div>
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
                               class="btn btn-primary modern-btn mb-2"
                               onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 8px 25px rgba(66, 133, 244, 0.5)'" 
                               onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 6px 20px rgba(66, 133, 244, 0.4)'">
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
                                 alt="{{ $touristService->name_ar }}" 
                                 class="img-fluid rounded shadow">
                            <div class="service-overlay">
                                <div class="service-badge">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $touristService->name_ar }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="service-placeholder">
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
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)),
        url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
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
    }
    
    .meta-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
    @media (max-width: 768px) {
        .service-title {
            font-size: 2.5rem;
        }
        
        .service-subtitle {
            font-size: 1.2rem;
        }
        
        .service-meta {
            flex-direction: column;
            gap: 1rem;
        }
        
        .meta-item {
            padding: 0.75rem 1rem;
        }
        
        .hero-actions {
            flex-direction: column;
        }
        
        .hero-actions .btn {
            width: 100%;
        }
        
        .service-placeholder {
            height: 300px;
        }
        
        .service-icon {
            font-size: 3rem;
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
    }
</style>
@endpush
