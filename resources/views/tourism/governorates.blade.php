@extends('layouts.tourism')

@section('title', 'المحافظات - البادية')
@section('description', 'اكتشف جميع محافظات سلطنة عُمان ومواقعها السياحية الرائعة')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <div class="hero-content fade-in-up">
                    <h1 class="hero-title">محافظات سلطنة عُمان</h1>
                    <p class="hero-subtitle">من الجبال الشامخة في الداخلية إلى السواحل الذهبية في الباطنة، اكتشف التنوع الجغرافي والثقافي في جميع محافظات السلطنة</p>
                    
                    <!-- Hero Stats -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorates->count() }}</div>
                            <div class="stat-label">محافظة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorates->sum('wilayats_count') }}</div>
                            <div class="stat-label">ولاية</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorates->sum('tourist_sites_count') }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $governorates->sum('tourist_services_count') }}</div>
                            <div class="stat-label">خدمة سياحية</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Governorates Grid -->
<section class="section">
    <div class="container">
        <div class="row">
            @forelse($governorates as $governorate)
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="governorate-card">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="governorate-image">
                                @if($governorate->image_path)
                                    <img src="{{ asset($governorate->image_path) }}" alt="{{ $governorate->name_ar }}" class="img-fluid">
                                @elseif($governorate->image_url)
                                    <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" class="img-fluid">
                                @else
                                    <img src="{{ asset('images/albadyah.jpg') }}" alt="{{ $governorate->name_ar }}" class="img-fluid">
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="governorate-content">
                                <h3 class="governorate-name">{{ $governorate->name_ar }}</h3>
                                <p class="governorate-name-en">{{ $governorate->name_en }}</p>
                                
                                <div class="governorate-stats">
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $governorate->wilayats_count }}</span>
                                        <span class="stat-label">ولاية</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $governorate->tourist_sites_count }}</span>
                                        <span class="stat-label">موقع سياحي</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $governorate->tourist_services_count }}</span>
                                        <span class="stat-label">خدمة سياحية</span>
                                    </div>
                                </div>
                                
                                <div class="governorate-actions">
                                    <a href="https://www.google.com/maps/search/{{ urlencode($governorate->name_ar . ' سلطنة عمان') }}" target="_blank" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>جوجل ماب
                                    </a>
                                    <a href="{{ route('tourism.governorate', $governorate->id) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-1"></i>استكشف المحافظة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد محافظات متاحة حالياً</h4>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="text-white">إحصائيات المحافظات</h2>
                <p class="text-white-50">نظرة عامة على المحافظات والمواقع السياحية</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $governorates->count() }}</span>
                    <div class="stat-label">إجمالي المحافظات</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $governorates->sum('wilayats_count') }}</span>
                    <div class="stat-label">إجمالي الولايات</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $governorates->sum('tourist_sites_count') }}</span>
                    <div class="stat-label">إجمالي المواقع السياحية</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $governorates->sum('tourist_services_count') }}</span>
                    <div class="stat-label">إجمالي الخدمات السياحية</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>هل تريد استكشاف المواقع السياحية؟</h3>
                <p>اكتشف أجمل الأماكن في جميع محافظات سلطنة عُمان</p>
                <div class="cta-buttons">
                    <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-camera me-2"></i>عرض المواقع السياحية
                    </a>
                    <a href="{{ route('tourism.tourist-services') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-concierge-bell me-2"></i>عرض الخدمات السياحية
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
    /* Hero Section */
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.3rem;
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

    /* Governorate Cards */
    .governorate-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    .governorate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .governorate-image {
        height: 200px;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .governorate-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .placeholder-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .governorate-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .governorate-name-en {
        color: var(--text-light);
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .governorate-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
        background: var(--bg-light);
        padding: 0.75rem;
        border-radius: 10px;
        flex: 1;
        min-width: 80px;
    }
    
    .stat-item .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .stat-item .stat-label {
        font-size: 0.8rem;
        color: var(--text-light);
    }
    
    .governorate-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    
    .governorate-actions .btn {
        flex: 1;
        min-width: 120px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .governorate-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
        }

        .hero-stats .stat-number {
            font-size: 2rem;
        }

        .governorate-stats {
            justify-content: center;
        }
        
        .stat-item {
            min-width: 70px;
        }
        
        .governorate-actions {
            flex-direction: column;
        }
        
        .governorate-actions .btn {
            width: 100%;
        }
        
        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .hero-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .hero-stats .stat-number {
            font-size: 1.8rem;
        }
    }
</style>
@endpush
