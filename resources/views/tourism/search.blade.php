@extends('layouts.tourism')

@section('title', 'البحث - البادية')
@section('description', 'ابحث عن وجهتك المثالية في سلطنة عُمان')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <div class="search-logo-container mb-4">
                        <img src="{{ asset('images/loogo.png') }}" alt="البادية" class="search-logo-img">
                    </div>
                    <h1 class="search-title">ابحث عن وجهتك المثالية</h1>
                    <p class="search-subtitle">استخدم محرك البحث للعثور على المواقع السياحية والخدمات</p>
                    
                    <form class="search-form" action="{{ route('tourism.search.results') }}" method="GET">
                        <div class="search-input-group">
                            <input type="text" 
                                   name="query" 
                                   class="search-input" 
                                   placeholder="ابحث عن موقع سياحي أو خدمة...." 
                                   value="{{ request('query') }}"
                                   required>
                            <button type="submit" class="search-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="search-categories">
                        <a href="{{ route('tourism.tourist-sites') }}" class="category-button">
                            <i class="fas fa-map-marker-alt"></i>
                            البحث في المواقع السياحية
                        </a>
                        <a href="{{ route('tourism.tourist-services') }}" class="category-button">
                            <i class="fas fa-concierge-bell"></i>
                            البحث في الخدمات السياحية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Results Section (if query exists) -->
@if(request('query'))
<section class="results-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="results-title">نتائج البحث عن: "{{ request('query') }}"</h2>
            </div>
        </div>
        
        <!-- Tourist Sites Results -->
        @if($touristSites && $touristSites->count() > 0)
        <div class="row">
            <div class="col-12">
                <h3 class="section-subtitle">المواقع السياحية</h3>
            </div>
        </div>
        <div class="row">
            @foreach($touristSites as $site)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 mountain-shadow">
                    @if($site->images->count() > 0)
                        @php $firstSiteImage = $site->images->first(); @endphp
                        @if($firstSiteImage->image_path)
                            <img src="{{ asset($firstSiteImage->image_path) }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 200px; object-fit: cover;">
                        @elseif($firstSiteImage->image_url)
                            <img src="{{ $firstSiteImage->image_url }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 200px; object-fit: cover;">
                        @endif
                    @else
                        <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $site->name_ar }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $site->name_ar }}</h5>
                        <p class="card-text">{{ $site->name_en }}</p>
                        <p class="card-text text-muted small">{{ Str::limit($site->description_ar, 100) }}</p>
                        <div class="mt-auto">
                            <a href="{{ route('tourism.tourist-site', $site->slug) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Tourist Services Results -->
        @if($touristServices && $touristServices->count() > 0)
        <div class="row">
            <div class="col-12">
                <h3 class="section-subtitle">الخدمات السياحية</h3>
            </div>
        </div>
        <div class="row">
            @foreach($touristServices as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 mountain-shadow">
                    @if($service->image_path)
                        <img src="{{ asset('storage/' . $service->image_path) }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 200px; object-fit: cover;">
                    @elseif($service->image_url)
                        <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/albadyah.jpg') }}" class="card-img-top" alt="{{ $service->name_ar }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="service-header mb-3">
                            @if($service->serviceType)
                                <span class="badge custom-badge-service mb-2">{{ $service->serviceType->name_ar }}</span>
                            @endif
                            <h5 class="card-title">{{ $service->name_ar }}</h5>
                            <p class="card-text text-muted">{{ $service->name_en }}</p>
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('tourism.tourist-service', $service->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- No Results -->
        @if((!$touristSites || $touristSites->count() == 0) && (!$touristServices || $touristServices->count() == 0))
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لم يتم العثور على نتائج</h4>
                    <p class="text-muted">جرب البحث بكلمات مختلفة أو تصفح المواقع والخدمات المتاحة</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)), 
                    url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 70vh;
        display: flex;
        align-items: center;
        color: white;
        text-align: center;
    }
    
    .search-logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .search-logo-img {
        height: 120px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        transition: all 0.3s ease;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        backdrop-filter: blur(5px);
    }
    
    .search-logo-img:hover {
        transform: scale(1.1);
        filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4));
        background: rgba(255, 255, 255, 0.2);
    }
    
    .search-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
    }
    
    .search-subtitle {
        font-size: 1.3rem;
        margin-bottom: 3rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .search-form {
        margin-bottom: 3rem;
    }
    
    .search-input-group {
        position: relative;
        max-width: 700px;
        margin: 0 auto;
        display: flex;
        background: white;
        border-radius: 50px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        overflow: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .search-input-group:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        transform: translateY(-3px);
    }
    
    .search-input {
        flex: 1;
        border: none;
        padding: 20px 30px;
        font-size: 1.2rem;
        outline: none;
        background: transparent;
        color: #2c3e50;
        font-weight: 400;
    }
    
    .search-input::placeholder {
        color: #adb5bd;
        font-style: normal;
        font-weight: 300;
    }
    
    .search-button {
        background: var(--primary-color);
        border: none;
        padding: 20px 30px;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 70px;
    }
    
    .search-button:hover {
        background: var(--secondary-color);
        transform: scale(1.05);
    }
    
    .search-categories {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .category-button {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 15px 30px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .category-button:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        border-color: var(--primary-color);
    }
    
    .category-button i {
        font-size: 1.2rem;
    }
    
    .results-section {
        padding: 80px 0;
        background: white;
    }
    
    .results-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #614c39;
        margin-bottom: 3rem;
        text-align: center;
    }
    
    .section-subtitle {
        font-size: 1.8rem;
        font-weight: 600;
        color: #a1815a;
        margin-bottom: 2rem;
        padding-bottom: 0.8rem;
        border-bottom: 3px solid #deb47a;
    }
    
    /* Custom Badge for Service Type */
    .custom-badge-service {
        background: linear-gradient(135deg, #6b8b8a 0%, #614c39 100%) !important;
        color: white !important;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .custom-badge-service:hover {
        background: linear-gradient(135deg, #5a7a79 0%, #4a3a2a 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(107, 139, 138, 0.3);
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 60px 0;
        }
        
        .search-logo-img {
            height: 80px;
        }
        
        .search-title {
            font-size: 2.5rem;
        }
        
        .search-subtitle {
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
        }
        
        .search-input-group {
            max-width: 100%;
            margin: 0 20px;
            border-radius: 25px;
        }
        
        .search-input {
            padding: 18px 25px;
            font-size: 1.1rem;
        }
        
        .search-button {
            padding: 18px 25px;
            min-width: 60px;
        }
        
        .search-categories {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .category-button {
            width: 100%;
            max-width: 350px;
            justify-content: center;
            padding: 15px 25px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .search-title {
            font-size: 2rem;
        }
        
        .search-subtitle {
            font-size: 1rem;
        }
        
        .search-input-group {
            margin: 0 15px;
        }
        
        .search-input {
            padding: 15px 20px;
            font-size: 1rem;
        }
        
        .search-button {
            padding: 15px 20px;
            min-width: 50px;
        }
    }
</style>
@endpush
