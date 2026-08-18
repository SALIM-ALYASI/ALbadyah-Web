@extends('layouts.app')

@section('title', 'مراجعة بيانات بوت البادية')

@section('content')
@php
    $statusLabels = [
        'pending_review' => ['بانتظار المراجعة', 'primary'],
        'needs_enrichment' => ['يحتاج إثراء', 'warning'],
        'approved_draft' => ['مسودة معتمدة', 'info'],
        'deferred' => ['مؤجل', 'secondary'],
        'rejected' => ['مرفوض', 'danger'],
        'published' => ['محفوظ في الموقع', 'success'],
    ];
@endphp

<div class="container-fluid" dir="rtl">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark">
                <i class="fas fa-robot text-primary ml-2"></i>
                مراجعة بيانات بوت البادية
            </h1>
            <p class="text-muted mb-0">راجع البيانات وعدّلها ثم احفظها في جداول الموقع أو انشرها للعامة.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-home ml-1"></i> لوحة التحكم
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">بانتظار العمل</div><div class="display-6 fw-bold text-primary">{{ $stats['waiting'] }}</div>
            </div></div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">تحتاج إثراء</div><div class="display-6 fw-bold text-warning">{{ $stats['needs_enrichment'] }}</div>
            </div></div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">محفوظة في الموقع</div><div class="display-6 fw-bold text-success">{{ $stats['published'] }}</div>
            </div></div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">مرفوضة</div><div class="display-6 fw-bold text-danger">{{ $stats['rejected'] }}</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('map-candidates.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label">بحث</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="الاسم العربي، الإنجليزي أو رقم OSM">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" name="status">
                        <option value="">كل الحالات</option>
                        @foreach($statusLabels as $value => $meta)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $meta[0] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">النوع</label>
                    <select class="form-select" name="category">
                        <option value="">الكل</option>
                        <option value="site" @selected(request('category') === 'site')>موقع سياحي</option>
                        <option value="service" @selected(request('category') === 'service')>خدمة سياحية</option>
                    </select>
                </div>
                <div class="col-lg-2 d-grid">
                    <button class="btn btn-primary"><i class="fas fa-search ml-1"></i> تصفية</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($candidates->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th><th>المعلم أو الخدمة</th><th>النوع</th><th>الولاية</th>
                                <th>الثقة</th><th>الحالة</th><th>تاريخ الوصول</th><th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($candidates as $candidate)
                            @php($status = $statusLabels[$candidate->status] ?? [$candidate->status, 'secondary'])
                            <tr>
                                <td>{{ $candidate->id }}</td>
                                <td style="min-width:230px">
                                    <strong>{{ $candidate->name_ar ?: 'بدون اسم عربي' }}</strong>
                                    <div class="text-muted small" dir="ltr">{{ $candidate->name_en ?: '—' }}</div>
                                    @if($candidate->osm_type && $candidate->osm_id)
                                        <small class="text-muted">OSM: {{ $candidate->osm_type }}/{{ $candidate->osm_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $candidate->category === 'site' ? 'success' : 'info' }}">
                                        {{ $candidate->category === 'site' ? 'موقع' : 'خدمة' }}
                                    </span>
                                </td>
                                <td>{{ optional($candidate->wilayat)->name_ar ?: '—' }}</td>
                                <td>
                                    @if($candidate->overall_confidence !== null)
                                        {{ round($candidate->overall_confidence * 100) }}%
                                    @else — @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                                    @if($candidate->published_id)
                                        <div class="small text-muted mt-1">#{{ $candidate->published_id }} في {{ $candidate->published_table }}</div>
                                    @endif
                                </td>
                                <td><small>{{ optional($candidate->created_at)->format('Y-m-d H:i') }}</small></td>
                                <td>
                                    <a href="{{ route('map-candidates.edit', $candidate->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-{{ $candidate->status === 'published' ? 'eye' : 'edit' }} ml-1"></i>
                                        {{ $candidate->status === 'published' ? 'عرض' : 'مراجعة' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $candidates->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد بيانات مطابقة</h5>
                    <p class="text-muted mb-0">بعد إرسال البوت لمرشح جديد سيظهر هنا تلقائيًا.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card{border-radius:16px}.table th{white-space:nowrap;font-weight:600}.badge{font-size:.78rem}.gap-3{gap:1rem}
</style>
@endsection
