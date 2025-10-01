@extends('layouts.app')

@section('title', 'المواقع السياحية')
@section('page-title', 'إدارة المواقع السياحية')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">المواقع السياحية</h1>
        <p class="text-muted mb-0">إدارة وعرض جميع المواقع السياحية في النظام</p>
    </div>
    <a href="{{ route('tourist-sites.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة موقع سياحي جديد
    </a>
</div>

@if($touristSites->count() > 0)
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristSites->count() }}</h3>
                <p>إجمالي المواقع السياحية</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristSites->where('website_url', '!=', null)->count() }}</h3>
                <p>مواقع لها موقع إلكتروني</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristSites->filter(function($item) { return $item->images->count() > 0; })->count() }}</h3>
                <p>مواقع لها صور</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $touristSites->filter(function($item) { return $item->created_at && $item->created_at->isToday(); })->count() }}</h3>
                <p>مواقع أضيفت اليوم</p>
            </div>
        </div>
    </div>

    <!-- Sites Grid -->
    <div class="row">
        @foreach($touristSites as $site)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <!-- Site Image -->
                @if($site->images->count() > 0)
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        @php $firstImage = $site->images->first(); @endphp
                        <img src="{{ $firstImage->image_url }}" 
                             alt="{{ $site->name_ar }}" 
                             class="card-img-top" 
                             style="height: 100%; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary">{{ $site->images->count() }} صورة</span>
                        </div>
                    </div>
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $site->name_ar }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($site->description_ar, 100) }}</p>
                    
                    <div class="mb-3">
                        @if($site->governorate)
                            <span class="badge bg-info me-1">{{ $site->governorate->name_ar }}</span>
                        @endif
                        @if($site->wilayat)
                            <span class="badge bg-secondary me-1">{{ $site->wilayat->name_ar }}</span>
                        @endif
                        @if($site->location)
                            <span class="badge bg-success">{{ $site->location }}</span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $site->created_at->format('Y-m-d') }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('tourist-sites.show', $site->id) }}" 
                                   class="btn btn-success" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tourist-sites.edit', $site->id) }}" 
                                   class="btn btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tourist-sites.destroy', $site->id) }}" 
                                      method="POST" style="display: inline;" 
                                      onsubmit="return confirmDelete()"
                                      id="deleteForm{{ $site->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-camera"></i>
                <h4>لا توجد مواقع سياحية</h4>
                <p class="mb-4">لم يتم إضافة أي مواقع سياحية بعد. ابدأ بإضافة أول موقع سياحي في النظام.</p>
                <a href="{{ route('tourist-sites.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    إضافة أول موقع سياحي
                </a>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
        color: #fff;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(97, 76, 57, 0.3);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
    }
    
    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stats-card p {
        opacity: 0.9;
        margin: 0;
        font-size: 0.9rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟ سيتم حذف جميع الصور المرتبطة به أيضاً.');
    }
    
    // إضافة معالجة أفضل للنموذج
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirmDelete()) {
                    e.preventDefault();
                    return false;
                }
                
                // إضافة loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحذف...';
                    submitBtn.disabled = true;
                }
            });
        });
    });
    
    // إضافة معالجة للأخطاء
    window.addEventListener('error', function(e) {
        console.error('JavaScript Error:', e.error);
    });
</script>
@endpush
@endsection
