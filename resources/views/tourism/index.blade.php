@extends('layouts.tourism')

@section('title', 'البادية - اكتشف جمال سلطنة عُمان')
@section('description', 'اكتشف أجمل المواقع السياحية في سلطنة عُمان واستمتع بخدماتنا المتميزة')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <div class="hero-logo mb-4">
                        <div class="hero-logo-container mb-4">
                            <img src="{{ asset('images/loogo.png') }}" alt="البادية" class="hero-logo-img">
                        </div>
                        <h1 style="font-size: 4rem; font-weight: 700; text-shadow: 3px 3px 6px rgba(0,0,0,0.5); margin-bottom: 0;">
                            البادية
                        </h1>
                        <p style="font-size: 1.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); margin-top: 0.5rem;">
                            اكتشف جمال سلطنة عُمان
                        </p>
                    </div>
                    <p style="font-size: 1.3rem; margin-bottom: 2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                        من الجبال الشامخة إلى السواحل الذهبية، من الصحراء الذهبية إلى الواحات الخضراء<br>
                        رحلة عبر تراث عُمان العريق وطبيعتها الساحرة
                    </p>
                    
                    <!-- إحصائيات الزيارات -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number" id="hero-total-visits">-</div>
                            <div class="stat-label">زيارة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="hero-total-cities">-</div>
                            <div class="stat-label">مدينة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_tourist_sites'] }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_tourist_services'] }}</div>
                            <div class="stat-label">خدمة سياحية</div>
                        </div>
                    </div>
                    <div class="hero-buttons">
                        <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-mountain me-2"></i>استكشف المواقع السياحية
                        </a>
                        <a href="{{ route('tourism.tourist-services') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-concierge-bell me-2"></i>خدماتنا السياحية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['total_governorates'] }}</span>
                    <div class="stat-label">محافظة</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['total_wilayats'] }}</span>
                    <div class="stat-label">ولاية</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['total_tourist_sites'] }}</span>
                    <div class="stat-label">موقع سياحي</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['total_tourist_services'] }}</span>
                    <div class="stat-label">خدمة سياحية</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tourist Sites -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">أبرز المواقع السياحية</h2>
                <p class="section-subtitle">اكتشف أجمل الأماكن في سلطنة عُمان</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($featuredSites as $site)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 mountain-shadow">
                    @if($site->images->count() > 0)
                        <img src="{{ $site->images->first()->image_url }}" class="card-img-top" alt="{{ $site->name_ar }}">
                    @else
                        @php
                            $omaniImages = [
                                'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // صحراء
                                'https://images.unsplash.com/photo-1544735716-392fe2489ffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // جبال
                                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // سواحل
                                'https://images.unsplash.com/photo-1583417319070-4a69db38a482?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // واحات
                                'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // قلعة
                                'https://images.unsplash.com/photo-1544735716-392fe2489ffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'  // تراث
                            ];
                            $randomImage = $omaniImages[array_rand($omaniImages)];
                        @endphp
                        <img src="{{ $randomImage }}" class="card-img-top" alt="{{ $site->name_ar }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $site->name_ar }}</h5>
                        <p class="card-text">{{ $site->name_en }}</p>
                        <p class="card-text text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $site->location ?? 'سلطنة عُمان' }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('tourism.tourist-site', $site->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد مواقع سياحية متاحة حالياً</h4>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>عرض جميع المواقع السياحية
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Governorates Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">استكشف المحافظات</h2>
                <p class="section-subtitle">اكتشف التنوع الجغرافي والثقافي في سلطنة عُمان</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($governorates->take(6) as $governorate)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 mountain-shadow">
                    <img src="{{ $governorate->image_url }}" class="card-img-top" alt="{{ $governorate->name_ar }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $governorate->name_ar }}</h5>
                        <p class="card-text">{{ $governorate->name_en }}</p>
                        <div class="governorate-stats mb-3">
                            <span class="badge custom-badge-1 me-2">
                                <i class="fas fa-map-marked-alt me-1"></i>{{ $governorate->wilayats_count }} ولاية
                            </span>
                            <span class="badge custom-badge-2 me-2">
                                <i class="fas fa-camera me-1"></i>{{ $governorate->tourist_sites_count }} موقع
                            </span>
                            <span class="badge custom-badge-3">
                                <i class="fas fa-concierge-bell me-1"></i>{{ $governorate->tourist_services_count }} خدمة
                            </span>
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('tourism.governorate', $governorate->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i>استكشف المحافظة
                            </a>
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
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('tourism.governorates') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>عرض جميع المحافظات
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%); color: white;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="mb-4">ابدأ رحلتك في البادية</h2>
                <p class="mb-4">من الجبال الشامخة إلى السواحل الذهبية، من الصحراء الذهبية إلى الواحات الخضراء<br>
                اكتشف خدماتنا السياحية المتميزة واحجز رحلتك المثالية عبر تراث عُمان العريق</p>
                <div class="cta-buttons">
                    <a href="{{ route('tourism.tourist-services') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-concierge-bell me-2"></i>خدماتنا السياحية
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadHeroStats();
});

function loadHeroStats() {
    // جلب إجمالي الزيارات
    fetch('/total-visits')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('hero-total-visits').textContent = formatNumber(data.total_visits);
            }
        })
        .catch(error => {
            console.error('Error loading total visits:', error);
        });

    // جلب إحصائيات مفصلة لعدد المدن
    fetch('/visit-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.visits_by_city) {
                const uniqueCities = data.data.visits_by_city.length;
                document.getElementById('hero-total-cities').textContent = formatNumber(uniqueCities);
            }
        })
        .catch(error => {
            console.error('Error loading city stats:', error);
        });
}

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}
</script>
@endpush

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)), 
                    url('{{ asset("images/AL-badyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    .hero-logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .hero-logo-img {
        height: 140px;
        width: auto;
        object-fit: contain;
        filter: brightness(1.1) contrast(1.1) drop-shadow(0 6px 12px rgba(0,0,0,0.4));
        transition: all 0.3s ease;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        backdrop-filter: blur(5px);
    }
    
    .hero-logo-img:hover {
        transform: scale(1.1);
        filter: brightness(1.3) contrast(1.2) drop-shadow(0 8px 16px rgba(0,0,0,0.5));
        background: rgba(255, 255, 255, 0.2);
    }
    
    .hero-buttons {
        margin-top: 2rem;
    }
    
    .governorate-stats .badge {
        font-size: 0.8rem;
    }
    
    .cta-buttons {
        margin-top: 2rem;
    }
    
    /* Custom Badge Colors */
    .custom-badge-1 {
        background: linear-gradient(135deg, #614c39 0%, #a1815a 100%) !important;
        color: white !important;
        border: none;
        font-weight: 500;
    }
    
    .custom-badge-2 {
        background: linear-gradient(135deg, #a1815a 0%, #deb47a 100%) !important;
        color: white !important;
        border: none;
        font-weight: 500;
    }
    
    .custom-badge-3 {
        background: linear-gradient(135deg, #deb47a 0%, #c19b6c 100%) !important;
        color: white !important;
        border: none;
        font-weight: 500;
    }
    
    .custom-badge-1:hover {
        background: linear-gradient(135deg, #4a3a2a 0%, #8a6f4a 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(97, 76, 57, 0.3);
    }
    
    .custom-badge-2:hover {
        background: linear-gradient(135deg, #8a6f4a 0%, #c9a06a 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(161, 129, 90, 0.3);
    }
    
    .custom-badge-3:hover {
        background: linear-gradient(135deg, #c9a06a 0%, #a8855c 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(222, 180, 122, 0.3);
    }
    
    @media (max-width: 768px) {
        .hero-logo-img {
            height: 100px;
        }
        
        .hero-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
    }
    
    /* إحصائيات الصفحة الرئيسية */
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
    }

    .hero-stats .stat-item {
        text-align: center;
        flex: 1;
    }

    .hero-stats .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .hero-stats .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
        }
        
        .hero-stats .stat-number {
            font-size: 2rem;
        }
        
        .hero-stats .stat-label {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .hero-stats {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .hero-stats .stat-item {
            flex: 1 1 calc(50% - 0.5rem);
        }
        
        .hero-stats .stat-number {
            font-size: 1.8rem;
        }
    }
</style>
@endpush
