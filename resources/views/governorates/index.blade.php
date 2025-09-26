@extends('layouts.app')

@section('title', 'المحافظات')
@section('page-title', 'إدارة المحافظات')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">المحافظات</h1>
        <p class="text-muted mb-0">إدارة وعرض جميع المحافظات في النظام</p>
    </div>
    <a href="{{ route('governorates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة محافظة جديدة
    </a>
</div>

@if($governorates->count() > 0)
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $governorates->count() }}</h3>
                <p>إجمالي المحافظات</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $governorates->where('website_url', '!=', null)->count() }}</h3>
                <p>محافظات لها موقع إلكتروني</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $governorates->where('image_url', '!=', null)->count() }}</h3>
                <p>محافظات لها صور</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $governorates->filter(function($item) { return $item->created_at && $item->created_at->isToday(); })->count() }}</h3>
                <p>محافظات أضيفت اليوم</p>
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
                    <th>موقع الويب</th>
                    <th>الصورة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($governorates as $index => $governorate)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $governorate->name_ar ?? 'غير محدد' }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-muted">{{ $governorate->name_en ?? 'غير محدد' }}</span>
                    </td>
                    <td>
                        @if($governorate->website_url)
                            <a href="{{ $governorate->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i>
                                زيارة الموقع
                            </a>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>
                        @if($governorate->has_image)
                            <div class="position-relative">
                                <img src="{{ $governorate->image_url }}" alt="{{ $governorate->name_ar }}" 
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
                            <span class="fw-medium">{{ $governorate->created_at->format('Y-m-d') }}</span>
                            <small class="text-muted">{{ $governorate->created_at->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('governorates.show', $governorate->id) }}" 
                               class="btn btn-sm btn-success" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('governorates.edit', $governorate->id) }}" 
                               class="btn btn-sm btn-warning" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('governorates.destroy', $governorate->id) }}" 
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
                <i class="fas fa-building"></i>
                <h4>لا توجد محافظات</h4>
                <p class="mb-4">لم يتم إضافة أي محافظات بعد. ابدأ بإضافة أول محافظة في النظام.</p>
                <a href="{{ route('governorates.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    إضافة أول محافظة
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
