@extends('layouts.tourism')

@section('title', 'مرجع المواقع السياحية - البادية')

@section('content')
<meta name="robots" content="noindex,nofollow">

<style>
.reference-page { background:#f7f8f8; min-height:70vh; padding:40px 0; }
.reference-head { margin-bottom:24px; }
.reference-head h1 { font-weight:700; color:#243B44; }
.reference-box { background:#fff; border-radius:18px; padding:20px; box-shadow:0 4px 18px rgba(0,0,0,.06); }
.reference-table th { white-space:nowrap; background:#243B44; color:#fff; }
.reference-table td { vertical-align:middle; }
.reference-count { font-size:1.1rem; font-weight:600; color:#243B44; }
</style>

<section class="reference-page">
<div class="container">

    <div class="reference-head d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1>مرجع المواقع السياحية</h1>
            <p class="text-muted mb-0">مرجع سريع للتحقق من المواقع الموجودة وتجنب التكرار.</p>
        </div>

        <a href="{{ route('reference.services') }}" class="btn btn-outline-dark">
            عرض الخدمات السياحية
        </a>
    </div>

    <div class="reference-box mb-4">
        <form method="GET" action="{{ route('reference.sites') }}" class="row g-3">

            <div class="col-lg-4">
                <label class="form-label">بحث بالاسم</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="مثال: قلعة نزوى">
            </div>

            <div class="col-lg-3">
                <label class="form-label">المحافظة</label>
                <select name="governorate_id" class="form-select">
                    <option value="">كل المحافظات</option>
                    @foreach($governorates as $governorate)
                        <option value="{{ $governorate->id }}"
                            @selected((string)request('governorate_id') === (string)$governorate->id)>
                            {{ $governorate->name_ar }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">الولاية</label>
                <select name="wilayat_id" class="form-select">
                    <option value="">كل الولايات</option>
                    @foreach($wilayats as $wilayat)
                        <option value="{{ $wilayat->id }}"
                            @selected((string)request('wilayat_id') === (string)$wilayat->id)>
                            {{ $wilayat->name_ar }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-dark w-100">بحث</button>
                <a href="{{ route('reference.sites') }}" class="btn btn-outline-secondary">مسح</a>
            </div>

        </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="reference-count">
            إجمالي قاعدة البيانات: {{ number_format($totalSites) }}
        </div>
        <div class="text-muted">
            النتائج: {{ number_format($touristSites->total()) }}
        </div>
    </div>

    <div class="reference-box p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 reference-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم العربي</th>
                        <th>الاسم الإنجليزي</th>
                        <th>المحافظة</th>
                        <th>الولاية</th>
                        <th>التصنيف</th>
                        <th>التحقق</th>
                        <th>النشر</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($touristSites as $site)
                    <tr>
                        <td>{{ $site->id }}</td>
                        <td><strong>{{ $site->name_ar ?: '—' }}</strong></td>
                        <td dir="ltr">{{ $site->name_en ?: '—' }}</td>
                        <td>{{ optional($site->governorate)->name_ar ?: '—' }}</td>
                        <td>{{ optional($site->wilayat)->name_ar ?: '—' }}</td>
                        <td>{{ optional($site->category)->name_ar ?: '—' }}</td>
                        <td>{{ $site->verification_status ?: '—' }}</td>
                        <td>
                            @if($site->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            لا توجد نتائج مطابقة.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $touristSites->links() }}
    </div>

</div>
</section>
@endsection
