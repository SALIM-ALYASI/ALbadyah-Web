@extends('layouts.app')

@section('title', 'إدارة المواقع السياحية الجديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            إدارة المواقع السياحية الجديدة
                        </h3>
                        @if(isset($totalSites) && isset($activeSites))
                            <small class="text-muted">
                                إجمالي المواقع: {{ $totalSites }} | المواقع النشطة: {{ $activeSites }}
                            </small>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('tourist-sites-new.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            إضافة موقع سياحي جديد
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلاتر البحث -->
                    <form method="GET" action="{{ route('tourist-sites-new.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">البحث</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           value="{{ request('search') }}" placeholder="البحث في الأسماء أو الأوصاف">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="governorate_id">المحافظة</label>
                                    <select name="governorate_id" id="governorate_id" class="form-control">
                                        <option value="">جميع المحافظات</option>
                                        @foreach($governorates as $governorate)
                                            <option value="{{ $governorate->id }}" 
                                                    {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                {{ $governorate->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="wilayat_id">الولاية</label>
                                    <select name="wilayat_id" id="wilayat_id" class="form-control">
                                        <option value="">جميع الولايات</option>
                                        @foreach($wilayats as $wilayat)
                                            <option value="{{ $wilayat->id }}" 
                                                    {{ request('wilayat_id') == $wilayat->id ? 'selected' : '' }}>
                                                {{ $wilayat->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-search mr-1"></i>
                                            بحث
                                        </button>
                                        <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times mr-1"></i>
                                            إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- جدول المواقع السياحية -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>الاسم (عربي)</th>
                                    <th>الاسم (إنجليزي)</th>
                                    <th>المحافظة</th>
                                    <th>الولاية</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($touristSites as $site)
                                    <tr>
                                        <td>{{ $loop->iteration + ($touristSites->currentPage() - 1) * $touristSites->perPage() }}</td>
                                        <td>
                                            @php
                                                $imageUrl = \App\Helpers\ImageHelper::getImageUrl($site->getRawOriginal('featured_image'), null, 'images/default-tourist-site.jpg');
                                            @endphp
                                            @if($site->getRawOriginal('featured_image'))
                                                <img src="{{ $imageUrl }}" alt="{{ $site->name_ar }}" 
                                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                                     onerror="this.src='{{ asset('images/default-tourist-site.jpg') }}'; this.onerror=null;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $site->name_ar }}</td>
                                        <td>{{ $site->name_en }}</td>
                                        <td>
                                            @if($site->governorate)
                                                <span class="badge badge-info">{{ $site->governorate->name_ar }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($site->wilayat)
                                                <span class="badge badge-success">{{ $site->wilayat->name_ar }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($site->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>{{ $site->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('tourist-sites-new.show', $site->id) }}" 
                                                   class="btn btn-info btn-sm" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tourist-sites-new.edit', $site->id) }}" 
                                                   class="btn btn-warning btn-sm" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tourist-sites-new.destroy', $site->id) }}" 
                                                      method="POST" style="display: inline-block;" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">لا توجد مواقع سياحية</h5>
                                                @if(request()->hasAny(['search', 'governorate_id', 'wilayat_id', 'status']))
                                                    <p class="text-muted">لم يتم العثور على أي مواقع سياحية تطابق معايير البحث المحددة</p>
                                                    <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-secondary mr-2">
                                                        <i class="fas fa-times mr-1"></i>
                                                        إزالة الفلاتر
                                                    </a>
                                                @else
                                                    <p class="text-muted">لم يتم إضافة أي مواقع سياحية بعد</p>
                                                @endif
                                                <a href="{{ route('tourist-sites-new.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    إضافة موقع سياحي جديد
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($touristSites->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $touristSites->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تحديث الولايات عند تغيير المحافظة
    document.getElementById('governorate_id').addEventListener('change', function() {
        const governorateId = this.value;
        const wilayatSelect = document.getElementById('wilayat_id');
        
        // إعادة تعيين الولاية
        wilayatSelect.innerHTML = '<option value="">جميع الولايات</option>';
        
        if (governorateId) {
            // يمكن إضافة AJAX لتحميل الولايات الخاصة بالمحافظة المحددة
            // للآن سنترك جميع الولايات متاحة
        }
    });
</script>
@endpush
