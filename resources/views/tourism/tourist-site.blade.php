@extends('layouts.tourism')

@section('title', $touristSite->name_ar . ' - البادية')
@section('description', $touristSite->description_ar ? Str::limit($touristSite->description_ar, 160) : 'اكتشف ' . $touristSite->name_ar)

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">

                    <h1 class="site-title">{{ $touristSite->name_ar }}</h1>
                    <h2 class="site-subtitle">{{ $touristSite->name_en }}</h2>

                    <div class="site-meta">
                        @if($touristSite->location)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="meta-text">
                                <span class="meta-label">الموقع</span>
                                <span class="meta-value">{{ $touristSite->location }}</span>
                            </div>
                        </div>
                        @endif

                        @if($touristSite->governorate)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="meta-text">
                                <span class="meta-label">المحافظة</span>
                                <span class="meta-value">{{ $touristSite->governorate->name_ar }}</span>
                            </div>
                        </div>
                        @endif

                        @if($touristSite->wilayat)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="meta-text">
                                <span class="meta-label">الولاية</span>
                                <span class="meta-value">{{ $touristSite->wilayat->name_ar }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                @if($touristSite->images->count() > 0)
                @php $firstImage = $touristSite->images->first(); @endphp
                <div class="hero-image-container">
                    @if($firstImage->image_path)
                    <img src="{{ asset($firstImage->image_path) }}" alt="{{ $touristSite->name_ar }}" class="hero-image">
                    @elseif($firstImage->image_url)
                    <img src="{{ $firstImage->image_url }}" alt="{{ $touristSite->name_ar }}" class="hero-image">
                    @else
                    <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $touristSite->name_ar }}" class="hero-image">
                    @endif
                    <div class="image-overlay">
                        <div class="image-counter">
                            <i class="fas fa-images"></i>
                            <span>{{ $touristSite->images->count() }} صورة</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="hero-image-container">
                    <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $touristSite->name_ar }}" class="hero-image">
                    <div class="image-overlay">
                        <div class="image-counter">
                            <i class="fas fa-images"></i>
                            <span>لا توجد صور</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Image Gallery -->
@if($touristSite->images->count() > 0)
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center mb-5">
                    <h3 class="section-title">معرض الصور</h3>
                    <p class="section-subtitle">اكتشف جمال {{ $touristSite->name_ar }} من خلال هذه الصور</p>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($touristSite->images as $image)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="gallery-card">
                    <div class="gallery-image-container">
                        @if($image->image_path)
                        <img src="{{ asset($image->image_path) }}" class="gallery-image" alt="{{ $touristSite->name_ar }}"
                            data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                        @elseif($image->image_url)
                        <img src="{{ $image->image_url }}" class="gallery-image" alt="{{ $touristSite->name_ar }}"
                            data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                        @else
                        <img src="{{ asset('images/albadyah.jpg') }}" class="gallery-image" alt="{{ $touristSite->name_ar }}"
                            data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                        @endif
                        <div class="gallery-overlay">
                            <button class="gallery-btn" data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Modal -->
            <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content beautiful-modal">
                        <div class="modal-header beautiful-header">
                            <h5 class="modal-title beautiful-title">{{ $touristSite->name_ar }}</h5>
                            <button type="button" class="btn-close beautiful-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body beautiful-body p-0">
                            @if($image->image_path)
                            <img src="{{ asset($image->image_path) }}" class="beautiful-modal-image" alt="{{ $touristSite->name_ar }}">
                            @elseif($image->image_url)
                            <img src="{{ $image->image_url }}" class="beautiful-modal-image" alt="{{ $touristSite->name_ar }}">
                            @else
                            <img src="{{ asset('images/albadyah.jpg') }}" class="beautiful-modal-image" alt="{{ $touristSite->name_ar }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Site Description -->
@if($touristSite->description_ar || $touristSite->description_en)
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>وصف الموقع
                        </h3>
                    </div>

                    <div class="card-body">
                        @if($touristSite->description_ar)
                        <div class="description-section">
                            <h5 class="description-title">الوصف بالعربية</h5>
                            <div class="description-content">
                                {{ $touristSite->description_ar }}
                            </div>
                        </div>
                        @endif

                        @if($touristSite->description_en)
                        <div class="description-section">
                            <h5 class="description-title">Description in English</h5>
                            <div class="description-content">
                                {{ $touristSite->description_en }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-info me-2"></i>معلومات الموقع
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="info-text">
                                <span class="info-label">عدد الصور</span>
                                <span class="info-value">{{ $touristSite->images->count() }} صورة</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-text">
                                <span class="info-label">تاريخ الإضافة</span>
                                <span class="info-value">{{ $touristSite->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>


                        <!-- Share Buttons -->
                        <div class="share-section">
                            <h6 class="share-title">شارك هذا الموقع</h6>
                            <div class="social-share">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}&quote={{ urlencode($touristSite->name_ar . ' - ' . $touristSite->description_ar) }}" 
                                   target="_blank" 
                                   class="social-btn facebook"
                                   title="شارك على فيسبوك">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($touristSite->name_ar . ' - ' . $touristSite->description_ar) }}" 
                                   target="_blank" 
                                   class="social-btn x-twitter"
                                   title="شارك على X">
                                    <i class="fab fa-x-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($touristSite->name_ar . ' - ' . $touristSite->description_ar . ' ' . request()->fullUrl()) }}" 
                                   target="_blank" 
                                   class="social-btn whatsapp"
                                   title="شارك على واتساب">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" 
                                   class="social-btn link"
                                   onclick="copyToClipboard('{{ request()->fullUrl() }}')"
                                   title="انسخ الرابط">
                                    <i class="fas fa-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Related Sites -->
@if($relatedSites->count() > 0)
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center mb-5">
                    <h3 class="section-title">مواقع مشابهة في {{ $touristSite->governorate->name_ar ?? 'نفس المنطقة' }}</h3>
                    <p class="section-subtitle">اكتشف المزيد من المواقع السياحية الرائعة</p>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($relatedSites as $relatedSite)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="related-card">
                    <div class="related-image-container">
                        @if($relatedSite->images->count() > 0)
                        @php $firstRelatedImage = $relatedSite->images->first(); @endphp
                        @if($firstRelatedImage->image_path)
                        <img src="{{ asset('storage/' . $firstRelatedImage->image_path) }}" class="related-image" alt="{{ $relatedSite->name_ar }}">
                        @elseif($firstRelatedImage->image_url)
                        <img src="{{ $firstRelatedImage->image_url }}" class="related-image" alt="{{ $relatedSite->name_ar }}">
                        @else
                        <img src="{{ asset('images/albadyah.jpg') }}" class="related-image" alt="{{ $relatedSite->name_ar }}">
                        @endif
                        @else
                        <img src="{{ asset('images/albadyah.jpg') }}" class="related-image" alt="{{ $relatedSite->name_ar }}">
                        @endif
                        <div class="related-overlay">
                            <a href="{{ route('tourism.tourist-site', $relatedSite->id) }}" class="related-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>

                    <div class="related-content">
                        <h6 class="related-title">{{ $relatedSite->name_ar }}</h6>
                        <p class="related-subtitle">{{ $relatedSite->name_en }}</p>

                        @if($relatedSite->location)
                        <div class="related-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $relatedSite->location }}</span>
                        </div>
                        @endif

                        <a href="{{ route('tourism.tourist-site', $relatedSite->id) }}" class="related-link">
                            عرض التفاصيل <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
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
                    <h3 class="cta-title">هل أعجبك هذا الموقع؟</h3>
                    <p class="cta-subtitle">اكتشف المزيد من المواقع السياحية الرائعة في سلطنة عُمان</p>
                    <div class="cta-buttons">
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary modern-btn">
                            <i class="fas fa-list me-2"></i>عرض جميع المواقع
                        </a>
                        <a href="{{ route('tourism.tourist-services') }}" class="btn btn-outline-primary modern-btn">
                            <i class="fas fa-concierge-bell me-2"></i>خدماتنا السياحية
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

    .site-title {
        font-size: 4rem;
        font-weight: 700;
        color: white;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.5);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .site-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        margin-bottom: 2rem;
        font-weight: 400;
    }

    .site-meta {
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
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

    .hero-image-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .hero-image {
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
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
        display: flex;
        align-items: flex-end;
        padding: 1.5rem;
    }

    .image-counter {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .image-counter i {
        margin-left: 0.5rem;
    }

    /* Gallery Section */
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

    .gallery-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .gallery-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .gallery-image-container {
        position: relative;
        overflow: hidden;
    }

    .gallery-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-card:hover .gallery-image {
        transform: scale(1.1);
    }

    .gallery-btn {
        background: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .gallery-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    /* Modal */
    .modern-modal {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .modern-modal .modal-header {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
    }

    .modern-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .modern-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .modal-image {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
    }

    /* Beautiful Modal Design */
    .beautiful-modal {
        border: none;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .beautiful-header {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .beautiful-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .beautiful-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
    }

    .beautiful-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        color: white;
        font-size: 1.3rem;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }

    .beautiful-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .beautiful-body {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 0;
        position: relative;
    }

    .beautiful-body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #6c757d, #495057, #6c757d);
        z-index: 1;
    }

    .beautiful-modal-image {
        width: 100%;
        height: auto;
        max-height: 75vh;
        object-fit: contain;
        border-radius: 0 0 25px 25px;
        transition: all 0.3s ease;
    }

    .beautiful-modal-image:hover {
        transform: scale(1.02);
    }

    /* Content Cards */
    .content-card,
    .info-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .content-card:hover,
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
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
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-color);
    }

    .description-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: justify;
    }

    /* Info Items */
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item:hover {
        background: rgba(97, 76, 57, 0.05);
        border-radius: 10px;
        padding-left: 1rem;
        padding-right: 1rem;
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

    /* Share Section */
    .share-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f0f0;
    }

    .share-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .social-share {
        display: flex;
        gap: 0.5rem;
    }

    .social-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.3rem;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .social-btn.facebook {
        background: rgba(24, 119, 242, 0.8);
    }

    .social-btn.twitter {
        background: rgba(29, 161, 242, 0.8);
    }

    .social-btn.x-twitter {
        background: rgba(0, 0, 0, 0.9);
    }

    .social-btn.whatsapp {
        background: rgba(37, 211, 102, 0.8);
    }

    .social-btn.link {
        background: rgba(97, 76, 57, 0.8);
    }

    .social-btn:hover {
        transform: translateY(-5px) scale(1.15);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2),
            0 0 0 1px rgba(255, 255, 255, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .social-btn i {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Related Sites */
    .related-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .related-image-container {
        position: relative;
        overflow: hidden;
    }

    .related-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .related-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .related-card:hover .related-overlay {
        opacity: 1;
    }

    .related-card:hover .related-image {
        transform: scale(1.1);
    }

    .related-btn {
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .related-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .related-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .related-location {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
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
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
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

    .cta-buttons .btn-outline-primary {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        backdrop-filter: blur(10px);
    }

    .cta-buttons .btn-outline-primary:hover {
        background: white;
        color: var(--primary-color);
        border-color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .site-title {
            font-size: 2.5rem;
        }

        .site-subtitle {
            font-size: 1.2rem;
        }

        .site-meta {
            flex-direction: column;
            gap: 1rem;
        }

        .meta-item {
            padding: 0.75rem 1rem;
        }

        .hero-image {
            height: 300px;
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
        .site-title {
            font-size: 2rem;
        }

        .hero-image {
            height: 250px;
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

@push('scripts')
<script>
    // Function to copy link to clipboard
    function copyToClipboard(text) {
        // Create a temporary textarea element
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        
        // Select and copy the text
        textarea.select();
        textarea.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            
            // Show success message
            showToast('تم نسخ الرابط بنجاح!', 'success');
        } catch (err) {
            // Fallback for modern browsers
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    showToast('تم نسخ الرابط بنجاح!', 'success');
                }).catch(function() {
                    showToast('فشل في نسخ الرابط', 'error');
                });
            } else {
                showToast('فشل في نسخ الرابط', 'error');
            }
        }
        
        // Remove the temporary element
        document.body.removeChild(textarea);
    }
    
    // Function to show toast notification
    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
    
    // Add click tracking for social buttons
    document.addEventListener('DOMContentLoaded', function() {
        const socialButtons = document.querySelectorAll('.social-btn');
        socialButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const platform = this.classList.contains('facebook') ? 'Facebook' :
                                this.classList.contains('twitter') || this.classList.contains('x-twitter') ? 'X (Twitter)' :
                                this.classList.contains('whatsapp') ? 'WhatsApp' : 'Link';
                
                // You can add analytics tracking here
                console.log(`Shared on ${platform}`);
            });
        });
    });
</script>
@endpush