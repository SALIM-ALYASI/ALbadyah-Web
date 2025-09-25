@extends('layouts.app')

@section('title', 'عرض جميع البيانات')
@section('page-title', 'عرض جميع البيانات')

@section('content')
<div class="container-fluid">
    <!-- إحصائيات عامة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>الإحصائيات العامة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-primary">{{ $stats['total_governorates'] }}</h3>
                                <p>المحافظات</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-success">{{ $stats['total_wilayats'] }}</h3>
                                <p>الولايات</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-info">{{ $stats['total_tourist_sites'] }}</h3>
                                <p>المواقع السياحية</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-warning">{{ $stats['total_tourist_services'] }}</h3>
                                <p>الخدمات السياحية</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-danger">{{ $stats['total_service_types'] }}</h3>
                                <p>أنواع الخدمات</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="stats-card text-center">
                                <h3 class="text-secondary">{{ $stats['total_images'] }}</h3>
                                <p>الصور</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تبويبات للجداول -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="dataTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="governorates-tab" data-bs-toggle="tab" data-bs-target="#governorates" type="button" role="tab">
                                <i class="fas fa-building me-1"></i>المحافظات ({{ $governorates->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wilayats-tab" data-bs-toggle="tab" data-bs-target="#wilayats" type="button" role="tab">
                                <i class="fas fa-map-marked-alt me-1"></i>الولايات ({{ $wilayats->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tourist-sites-tab" data-bs-toggle="tab" data-bs-target="#tourist-sites" type="button" role="tab">
                                <i class="fas fa-camera me-1"></i>المواقع السياحية ({{ $touristSites->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tourist-services-tab" data-bs-toggle="tab" data-bs-target="#tourist-services" type="button" role="tab">
                                <i class="fas fa-concierge-bell me-1"></i>الخدمات السياحية ({{ $touristServices->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="service-types-tab" data-bs-toggle="tab" data-bs-target="#service-types" type="button" role="tab">
                                <i class="fas fa-tags me-1"></i>أنواع الخدمات ({{ $serviceTypes->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                <i class="fas fa-images me-1"></i>الصور ({{ $touristImages->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dataTabsContent">
                        <!-- المحافظات -->
                        <div class="tab-pane fade show active" id="governorates" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم بالعربية</th>
                                            <th>الاسم بالإنجليزية</th>
                                            <th>موقع الويب</th>
                                            <th>الولايات</th>
                                            <th>المواقع السياحية</th>
                                            <th>الخدمات السياحية</th>
                                            <th>الصورة</th>
                                            <th>تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($governorates as $governorate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $governorate->name_ar }}</td>
                                            <td>{{ $governorate->name_en }}</td>
                                            <td>
                                                @if($governorate->website_url)
                                                    <a href="{{ $governorate->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> زيارة الموقع
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-info">{{ $governorate->wilayats_count }}</span></td>
                                            <td><span class="badge bg-success">{{ $governorate->tourist_sites_count }}</span></td>
                                            <td><span class="badge bg-warning">{{ $governorate->tourist_services_count }}</span></td>
                                            <td>
                                                @if($governorate->image_url)
                                                    <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">لا توجد صورة</span>
                                                @endif
                                            </td>
                                            <td>{{ $governorate->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">لا توجد محافظات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- الولايات -->
                        <div class="tab-pane fade" id="wilayats" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم بالعربية</th>
                                            <th>الاسم بالإنجليزية</th>
                                            <th>المحافظة</th>
                                            <th>موقع الويب</th>
                                            <th>الصورة</th>
                                            <th>تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wilayats as $wilayat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $wilayat->name_ar }}</td>
                                            <td>{{ $wilayat->name_en }}</td>
                                            <td>
                                                @if($wilayat->governorate)
                                                    <span class="badge bg-primary">{{ $wilayat->governorate->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($wilayat->website_url)
                                                    <a href="{{ $wilayat->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> زيارة الموقع
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($wilayat->image_url)
                                                    <img src="{{ $wilayat->image_url }}" alt="{{ $wilayat->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">لا توجد صورة</span>
                                                @endif
                                            </td>
                                            <td>{{ $wilayat->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">لا توجد ولايات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- المواقع السياحية -->
                        <div class="tab-pane fade" id="tourist-sites" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم بالعربية</th>
                                            <th>الاسم بالإنجليزية</th>
                                            <th>الموقع</th>
                                            <th>المحافظة</th>
                                            <th>الولاية</th>
                                            <th>عدد الصور</th>
                                            <th>موقع الويب</th>
                                            <th>تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($touristSites as $site)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $site->name_ar }}</td>
                                            <td>{{ $site->name_en }}</td>
                                            <td>{{ $site->location ?? 'غير محدد' }}</td>
                                            <td>
                                                @if($site->governorate)
                                                    <span class="badge bg-primary">{{ $site->governorate->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($site->wilayat)
                                                    <span class="badge bg-success">{{ $site->wilayat->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-info">{{ $site->images->count() }}</span></td>
                                            <td>
                                                @if($site->website_url)
                                                    <a href="{{ $site->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> زيارة الموقع
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                            <td>{{ $site->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">لا توجد مواقع سياحية</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- الخدمات السياحية -->
                        <div class="tab-pane fade" id="tourist-services" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم بالعربية</th>
                                            <th>الاسم بالإنجليزية</th>
                                            <th>نوع الخدمة</th>
                                            <th>المحافظة</th>
                                            <th>الولاية</th>
                                            <th>موقع الويب</th>
                                            <th>الصورة</th>
                                            <th>تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($touristServices as $service)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $service->name_ar }}</td>
                                            <td>{{ $service->name_en }}</td>
                                            <td>
                                                @if($service->serviceType)
                                                    <span class="badge bg-warning">{{ $service->serviceType->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->governorate)
                                                    <span class="badge bg-primary">{{ $service->governorate->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->wilayat)
                                                    <span class="badge bg-success">{{ $service->wilayat->name_ar }}</span>
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->website_url)
                                                    <a href="{{ $service->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> زيارة الموقع
                                                    </a>
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->image_url)
                                                    <img src="{{ $service->image_url }}" alt="{{ $service->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">لا توجد صورة</span>
                                                @endif
                                            </td>
                                            <td>{{ $service->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">لا توجد خدمات سياحية</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- أنواع الخدمات -->
                        <div class="tab-pane fade" id="service-types" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم بالعربية</th>
                                            <th>الاسم بالإنجليزية</th>
                                            <th>عدد الخدمات</th>
                                            <th>تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($serviceTypes as $type)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $type->name_ar }}</td>
                                            <td>{{ $type->name_en }}</td>
                                            <td><span class="badge bg-info">{{ $type->tourist_services_count }}</span></td>
                                            <td>{{ $type->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">لا توجد أنواع خدمات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- الصور -->
                        <div class="tab-pane fade" id="images" role="tabpanel">
                            <div class="row">
                                @forelse($touristImages as $image)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card">
                                        <img src="{{ $image->image_url }}" class="card-img-top" alt="صورة" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                @if($image->touristSite)
                                                    {{ $image->touristSite->name_ar }}
                                                @else
                                                    صورة غير مرتبطة
                                                @endif
                                            </h6>
                                            <p class="card-text">
                                                <small class="text-muted">{{ $image->created_at->format('Y-m-d H:i') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-images fa-3x mb-3"></i>
                                        <p>لا توجد صور</p>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.stats-card h3 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.stats-card p {
    margin: 0;
    font-size: 1.1rem;
    color: #6c757d;
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 0;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background-color: #614c39;
    color: white;
    border-bottom: 2px solid #614c39;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    background-color: #f8f9fa;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.img-thumbnail {
    border: 2px solid #dee2e6;
}

.badge {
    font-size: 0.8rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush
