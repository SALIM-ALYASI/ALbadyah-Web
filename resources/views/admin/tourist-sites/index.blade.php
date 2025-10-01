@extends('layouts.app')

@section('title', 'إدارة المواقع السياحية')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark">
                        <i class="fas fa-map-marked-alt text-primary mr-2"></i>
                        إدارة المواقع السياحية
                    </h1>
                    <p class="text-muted mb-0">إدارة وعرض جميع المواقع السياحية في النظام</p>
                </div>
                <div>
                    <a href="{{ route('tourist-sites.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus mr-2"></i>
                        إضافة موقع سياحي جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $touristSites->where('created_at', '>=', today())->count() }}</h4>
                            <p class="mb-0">مواقع أضيفت اليوم</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $touristSites->where('featured_image', '!=', null)->count() }}</h4>
                            <p class="mb-0">مواقع لها صور</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-image fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $touristSites->where('website_url', '!=', null)->count() }}</h4>
                            <p class="mb-0">مواقع لها موقع إلكتروني</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-globe fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $touristSites->count() }}</h4>
                            <p class="mb-0">إجمالي المواقع</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-map-marked-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tourist Sites Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-list mr-2"></i>
                قائمة المواقع السياحية
            </h5>
        </div>
        <div class="card-body p-0">
            @if($touristSites->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>الاسم بالعربية</th>
                                <th>الاسم بالإنجليزية</th>
                                <th>المحافظة</th>
                                <th>الولاية</th>
                                <th>الموقع الإلكتروني</th>
                                <th>الصورة</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($touristSites as $index => $site)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $site->name_ar }}</h6>
                                                <small class="text-muted">
                                                    @if($site->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $site->name_en }}</td>
                                    <td>
                                        @if($site->governorate)
                                            <span class="badge bg-info">{{ $site->governorate->name_ar }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($site->wilayat)
                                            <span class="badge bg-secondary">{{ $site->wilayat->name_ar }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($site->website_url)
                                            <a href="{{ $site->website_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                زيارة الموقع
                                            </a>
                                        @else
                                            <span class="text-muted">غير متوفر</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($site->featured_image)
                                            <img src="{{ $site->featured_image }}" alt="{{ $site->name_ar }}" 
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $site->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('tourist-sites.show', $site->id) }}" 
                                               class="btn btn-outline-success" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tourist-sites.edit', $site->id) }}" 
                                               class="btn btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tourist-sites.destroy', $site->id) }}" 
                                                  method="POST" style="display: inline-block;" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد مواقع سياحية</h5>
                    <p class="text-muted">ابدأ بإضافة موقع سياحي جديد</p>
                    <a href="{{ route('tourist-sites.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        إضافة موقع سياحي
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection
