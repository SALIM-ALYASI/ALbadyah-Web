@extends('layouts.app')

@section('title', 'مراجعة مرشح البوت')

@section('content')
@php($isPublished = $candidate->status === 'published')
<div class="container-fluid" dir="rtl">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-clipboard-check text-primary ml-2"></i>مراجعة المرشح #{{ $candidate->id }}</h1>
            <p class="text-muted mb-0">راجع جميع الحقول قبل الحفظ في الموقع.</p>
        </div>
        <a href="{{ route('map-candidates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
        </a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>لم يتم الحفظ:</strong>
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    @if($isPublished)
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle ml-1"></i>
            نُقل هذا المرشح إلى <strong>{{ $candidate->published_table }}</strong>
            برقم <strong>#{{ $candidate->published_id }}</strong> بتاريخ {{ optional($candidate->published_at)->format('Y-m-d H:i') }}.
        </div>
    @endif

    <form method="POST" action="{{ route('map-candidates.process', $candidate->id) }}" id="candidate-form">
        @csrf
        <div class="row">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0">المعلومات الأساسية</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">نوع السجل</label>
                                <select name="category" id="category" class="form-select" required @disabled($isPublished)>
                                    <option value="site" @selected(old('category', $candidate->category) === 'site')>موقع سياحي</option>
                                    <option value="service" @selected(old('category', $candidate->category) === 'service')>خدمة سياحية</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">الاسم العربي</label>
                                <input name="name_ar" class="form-control" value="{{ old('name_ar', $candidate->name_ar) }}" required @readonly($isPublished)>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">الاسم الإنجليزي</label>
                                <input name="name_en" class="form-control" dir="ltr" value="{{ old('name_en', $candidate->name_en) }}" required @readonly($isPublished)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوصف العربي</label>
                                <textarea name="description_ar" rows="7" class="form-control" @readonly($isPublished)>{{ old('description_ar', $candidate->description_ar) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوصف الإنجليزي</label>
                                <textarea name="description_en" rows="7" class="form-control" dir="ltr" @readonly($isPublished)>{{ old('description_en', $candidate->description_en) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">التصنيف الفرعي</label>
                                <input name="subtype" class="form-control" value="{{ old('subtype', $candidate->subtype) }}" @readonly($isPublished)>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">الهاتف</label>
                                <input name="phone" class="form-control" dir="ltr" value="{{ old('phone', $candidate->phone) }}" @readonly($isPublished)>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">الموقع الإلكتروني</label>
                                <input name="website" class="form-control" dir="ltr" value="{{ old('website', $candidate->website) }}" @readonly($isPublished)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ساعات العمل</label>
                                <input name="opening_hours" class="form-control" value="{{ old('opening_hours', $candidate->opening_hours) }}" @readonly($isPublished)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">العنوان / المنطقة</label>
                                <input name="address_ar" class="form-control" value="{{ old('address_ar', $candidate->address_ar) }}" @readonly($isPublished)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رابط الصورة</label>
                                <input name="image_url" class="form-control" dir="ltr" value="{{ old('image_url', $candidate->image_url) }}" @readonly($isPublished)>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0">الموقع والتصنيف</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">المحافظة</label>
                                <select name="governorate_id" id="governorate_id" class="form-select" @disabled($isPublished)>
                                    <option value="">تُحدد تلقائيًا من الولاية</option>
                                    @foreach($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" @selected((string) old('governorate_id', $candidate->governorate_id) === (string) $governorate->id)>{{ $governorate->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الولاية</label>
                                <select name="wilayat_id" id="wilayat_id" class="form-select" required @disabled($isPublished)>
                                    <option value="">اختر الولاية</option>
                                    @foreach($wilayats as $wilayat)
                                        <option value="{{ $wilayat->id }}" data-governorate="{{ $wilayat->governorate_id }}" @selected((string) old('wilayat_id', $candidate->wilayat_id) === (string) $wilayat->id)>{{ $wilayat->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 site-field">
                                <label class="form-label">تصنيف الموقع</label>
                                <select name="tourist_site_category_id" class="form-select" @disabled($isPublished)>
                                    <option value="">غير محدد</option>
                                    @foreach($siteCategories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('tourist_site_category_id', $candidate->tourist_site_category_id) === (string) $category->id)>{{ $category->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 service-field">
                                <label class="form-label">نوع الخدمة</label>
                                <select name="service_type_id" class="form-select" @disabled($isPublished)>
                                    <option value="">غير محدد</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" @selected((string) old('service_type_id', $candidate->service_type_id) === (string) $type->id)>{{ $type->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">خط العرض</label>
                                <input type="number" step="0.0000001" name="latitude" class="form-control" dir="ltr" value="{{ old('latitude', $candidate->latitude) }}" required @readonly($isPublished)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">خط الطول</label>
                                <input type="number" step="0.0000001" name="longitude" class="form-control" dir="ltr" value="{{ old('longitude', $candidate->longitude) }}" required @readonly($isPublished)>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0">المعاينة والثقة</h5></div>
                    <div class="card-body">
                        @if($candidate->image_url && !$candidate->image_is_placeholder)
                            <img src="{{ $candidate->image_url }}" alt="معاينة" class="img-fluid rounded mb-3 review-image">
                        @else
                            <div class="placeholder-image mb-3"><i class="fas fa-image fa-3x"></i><span>لا توجد صورة موثوقة</span></div>
                        @endif
                        <div class="d-flex justify-content-between mb-2"><span>درجة الثقة</span><strong>{{ $candidate->overall_confidence !== null ? round($candidate->overall_confidence * 100).'%' : '—' }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>الحالة</span><strong>{{ $candidate->status }}</strong></div>
                        <div class="d-flex justify-content-between mb-3"><span>OSM</span><code>{{ $candidate->osm_type }}/{{ $candidate->osm_id }}</code></div>
                        @if($candidate->latitude !== null && $candidate->longitude !== null)
                            <a class="btn btn-outline-primary w-100" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ $candidate->latitude }},{{ $candidate->longitude }}">
                                <i class="fas fa-map-marker-alt ml-1"></i> فتح في خرائط جوجل
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0">المصادر</h5></div>
                    <div class="card-body source-list">
                        @forelse($candidate->sources ?: [] as $source)
                            <div class="border rounded p-2 mb-2">
                                <strong>{{ $source['type'] ?? 'مصدر' }}</strong>
                                @if(isset($source['field']))<span class="text-muted">— {{ $source['field'] }}</span>@endif
                                @if(isset($source['confidence']))<span class="badge bg-light text-dark">{{ round($source['confidence'] * 100) }}%</span>@endif
                                @if(!empty($source['url']))
                                    <div><a href="{{ $source['url'] }}" target="_blank" rel="noopener" class="small text-break">{{ $source['url'] }}</a></div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted mb-0">لا توجد مصادر مسجلة.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @unless($isPublished)
            <div class="card border-0 shadow-sm mb-4 sticky-actions">
                <div class="card-body d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <button type="submit" name="action" value="save" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-save ml-1"></i> حفظ التعديلات فقط
                    </button>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" name="action" value="save_review" class="btn btn-warning btn-lg">
                            <i class="fas fa-archive ml-1"></i> حفظ في الموقع للمراجعة
                        </button>
                        <button type="submit" name="action" value="publish" class="btn btn-success btn-lg" onclick="return confirm('سيظهر هذا السجل للعامة مباشرة. هل راجعت الاسم والوصف والموقع والمصادر؟')">
                            <i class="fas fa-paper-plane ml-1"></i> حفظ ونشر للعامة
                        </button>
                    </div>
                </div>
            </div>
        @endunless
    </form>

    @unless($isPublished)
        <form method="POST" action="{{ route('map-candidates.reject', $candidate->id) }}" class="card border-danger shadow-sm mb-4" onsubmit="return confirm('هل تريد رفض هذا المرشح؟')">
            @csrf
            <div class="card-body d-flex flex-wrap gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label class="form-label">سبب الرفض (اختياري)</label>
                    <input name="rejected_reason" class="form-control" placeholder="مثال: مكرر أو ليس معلمًا سياحيًا">
                </div>
                <button class="btn btn-outline-danger"><i class="fas fa-trash ml-1"></i> رفض المرشح</button>
            </div>
        </form>
    @endunless
</div>

<style>
.card{border-radius:16px}.gap-2{gap:.5rem}.gap-3{gap:1rem}.review-image{width:100%;max-height:280px;object-fit:cover}.placeholder-image{height:220px;background:#f3f5f7;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#8b96a3;gap:.75rem}.source-list{max-height:420px;overflow:auto}.sticky-actions{position:sticky;bottom:12px;z-index:10;border:1px solid rgba(0,0,0,.08)!important}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const category = document.getElementById('category');
    const governorate = document.getElementById('governorate_id');
    const wilayat = document.getElementById('wilayat_id');

    function toggleCategoryFields() {
        document.querySelectorAll('.site-field').forEach(el => el.style.display = category.value === 'site' ? '' : 'none');
        document.querySelectorAll('.service-field').forEach(el => el.style.display = category.value === 'service' ? '' : 'none');
    }
    function filterWilayats() {
        const selected = governorate.value;
        Array.from(wilayat.options).forEach(option => {
            if (!option.value) return;
            option.hidden = selected && option.dataset.governorate !== selected;
        });
    }
    if (category && !category.disabled) category.addEventListener('change', toggleCategoryFields);
    if (governorate && !governorate.disabled) governorate.addEventListener('change', filterWilayats);
    toggleCategoryFields();
    filterWilayats();
});
</script>
@endsection
