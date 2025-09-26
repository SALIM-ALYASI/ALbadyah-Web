@extends('layouts.app')

@section('title', 'الولايات')
@section('page-title', 'إدارة الولايات')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">الولايات</h1>
        <p class="text-muted mb-0">إدارة وعرض جميع الولايات في النظام</p>
    </div>
    <a href="{{ route('wilayats.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة ولاية جديدة
    </a>
</div>

@if($wilayats->count() > 0)
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $wilayats->count() }}</h3>
                <p>إجمالي الولايات</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $wilayats->where('website_url', '!=', null)->count() }}</h3>
                <p>ولايات لها موقع إلكتروني</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $wilayats->where('image_url', '!=', null)->count() }}</h3>
                <p>ولايات لها صور</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $wilayats->filter(function($item) { return $item->created_at && $item->created_at->isToday(); })->count() }}</h3>
                <p>ولايات أضيفت اليوم</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم بالعربية</th>
                    <th>الاسم بالإنجليزية</th>
                    <th>المحافظة</th>
                    <th>موقع الويب</th>
                    <th>الصورة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wilayats as $index => $wilayat)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $wilayat->name_ar ?? 'غير محدد' }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-muted">{{ $wilayat->name_en ?? 'غير محدد' }}</span>
                    </td>
                    <td>
                        @if($wilayat->governorate)
                            <span class="badge bg-info">{{ $wilayat->governorate->name_ar }}</span>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>
                        @if($wilayat->website_url)
                            <a href="{{ $wilayat->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i>
                                زيارة الموقع
                            </a>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>
                        @if($wilayat->has_image)
                            <div class="position-relative">
                                <img src="{{ $wilayat->image_url }}" alt="{{ $wilayat->name_ar }}" 
                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded" style="width: 50px; height: 50px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-medium">{{ $wilayat->created_at->format('Y-m-d') }}</span>
                            <small class="text-muted">{{ $wilayat->created_at->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('wilayats.show', $wilayat->id) }}" 
                               class="btn btn-sm btn-success" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('wilayats.edit', $wilayat->id) }}" 
                               class="btn btn-sm btn-warning" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('wilayats.destroy', $wilayat->id) }}" 
                                  method="POST" style="display: inline;" 
                                  onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
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
    <!-- Empty State -->
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-map-marked-alt"></i>
                <h4>لا توجد ولايات</h4>
                <p class="mb-4">لم يتم إضافة أي ولايات بعد. ابدأ بإضافة أول ولاية في النظام.</p>
                <a href="{{ route('wilayats.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    إضافة أول ولاية
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
