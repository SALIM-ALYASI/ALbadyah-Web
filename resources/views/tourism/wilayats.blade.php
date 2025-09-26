@extends('layouts.tourism')

@section('title', 'الولايات - البادية')
@section('description', 'اكتشف جميع ولايات سلطنة عُمان والمواقع السياحية والخدمات المتاحة')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="hero-content fade-in-up">
                    <h1 class="hero-title">ولايات سلطنة عُمان</h1>
                    <p class="hero-subtitle">اكتشف جميع ولايات السلطنة والمواقع السياحية والخدمات المتميزة</p>
                    
                    <!-- Hero Stats -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $wilayats->count() }}</div>
                            <div class="stat-label">ولاية</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $wilayats->sum(function($wilayat) { return $wilayat->touristSites->count(); }) }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $wilayats->sum(function($wilayat) { return $wilayat->touristServices->count(); }) }}</div>
                            <div class="stat-label">خدمة سياحية</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="search-filter-card">
                    <form method="GET" action="{{ route('tourism.wilayats') }}" class="search-form">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search" class="form-label">
                                        <i class="fas fa-search"></i>البحث
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="ابحث عن ولاية...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="governorate" class="form-label">
                                        <i class="fas fa-building"></i>المحافظة
                                    </label>
                                    <select class="form-control" id="governorate" name="governorate">
                                        <option value="">جميع المحافظات</option>
                                        @foreach($governorates as $gov)
                                            <option value="{{ $gov->id }}" {{ request('governorate') == $gov->id ? 'selected' : '' }}>
                                                {{ $gov->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort" class="form-label">
                                        <i class="fas fa-sort"></i>ترتيب حسب
                                    </label>
                                    <select class="form-control" id="sort" name="sort">
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                                        <option value="sites" {{ request('sort') == 'sites' ? 'selected' : '' }}>عدد المواقع</option>
                                        <option value="services" {{ request('sort') == 'services' ? 'selected' : '' }}>عدد الخدمات</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wilayats Grid -->
<section class="section bg-light">
    <div class="container">
        @if($wilayats->count() > 0)
            <div class="row">
                @foreach($wilayats as $wilayat)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="wilayat-card">
                        <div class="wilayat-header">
                            <h3 class="wilayat-title">{{ $wilayat->name_ar }}</h3>
                            <p class="wilayat-name-en">{{ $wilayat->name_en }}</p>
                            <div class="wilayat-governorate">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $wilayat->governorate->name_ar ?? 'غير محدد' }}
                            </div>
                        </div>
                        
                        <div class="wilayat-content">
                            <p class="wilayat-description">
                                {{ Str::limit($wilayat->description ?? 'ولاية جميلة في سلطنة عُمان', 120) }}
                            </p>
                            
                            <div class="wilayat-stats">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="stat-text">
                                        <span class="stat-number">{{ $wilayat->touristSites->count() }}</span>
                                        <span class="stat-label">موقع سياحي</span>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div class="stat-text">
                                        <span class="stat-number">{{ $wilayat->touristServices->count() }}</span>
                                        <span class="stat-label">خدمة سياحية</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="wilayat-actions">
                            <a href="{{ route('tourism.wilayat', $wilayat->id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-eye me-2"></i>عرض الولاية
                            </a>
                            <a href="https://www.google.com/maps/search/{{ urlencode($wilayat->name_ar . ' ' . $wilayat->governorate->name_ar . ' سلطنة عمان') }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="fas fa-map-marker-alt me-2"></i>جوجل ماب
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($wilayats->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrapper">
                        {{ $wilayats->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">لم يتم العثور على ولايات</h4>
                        <p class="text-muted">جرب البحث بكلمات مختلفة أو تصفح جميع المحافظات</p>
                        <a href="{{ route('tourism.governorates') }}" class="btn btn-primary">
                            <i class="fas fa-building me-2"></i>تصفح المحافظات
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>استكشف المزيد</h3>
                <p>اكتشف جمال سلطنة عُمان في المحافظات والمواقع السياحية</p>
                <div class="cta-buttons">
                    <a href="{{ route('tourism.governorates') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-building me-2"></i>تصفح المحافظات
                    </a>
                    <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-camera me-2"></i>المواقع السياحية
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

    /* Search and Filter */
    .search-filter-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 3rem;
    }

    .search-form .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        border: 2px solid rgba(97, 76, 57, 0.1);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(97, 76, 57, 0.25);
    }

    /* Wilayat Cards */
    .wilayat-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .wilayat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .wilayat-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .wilayat-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .wilayat-name-en {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0.75rem;
        font-style: italic;
    }

    .wilayat-governorate {
        font-size: 0.9rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .wilayat-content {
        padding: 1.5rem;
    }

    .wilayat-description {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .wilayat-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    .wilayat-stats .stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
        flex: 1;
    }

    .wilayat-stats .stat-icon {
        width: 40px;
        height: 40px;
        background: rgba(97, 76, 57, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .wilayat-stats .stat-icon i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .wilayat-stats .stat-text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .wilayat-stats .stat-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1;
    }

    .wilayat-stats .stat-label {
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .wilayat-actions {
        padding: 1.5rem;
        padding-top: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .page-link {
        color: var(--primary-color);
        border: none;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
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
            font-size: 1.1rem;
        }

        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
        }

        .hero-stats .stat-number {
            font-size: 2rem;
        }

        .search-filter-card {
            padding: 1.5rem;
        }

        .wilayat-stats .stat-item {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .wilayat-stats .stat-text {
            align-items: center;
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

        .wilayat-header {
            padding: 1rem;
        }

        .wilayat-content {
            padding: 1rem;
        }

        .wilayat-actions {
            padding: 1rem;
        }
    }
</style>
@endpush
