@extends('layouts.app')

@section('title', 'تفاصيل الموقع السياحي - ' . $touristSite->name_ar)
@section('page-title', 'تفاصيل الموقع السياحي')

@php
    $infoRows = [
        ['label' => 'المحافظة', 'value' => $touristSite->governorate?->name_ar, 'link' => $touristSite->governorate ? route('governorates.show', $touristSite->governorate->id) : null],
        ['label' => 'الولاية', 'value' => $touristSite->wilayat?->name_ar, 'link' => $touristSite->wilayat ? route('wilayats.show', $touristSite->wilayat->id) : null],
    ];
@endphp

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="m-0 text-2xl font-bold text-ab-navy">تفاصيل الموقع السياحي</h1>
            <p class="m-0 mt-1 text-sm text-ab-body">عرض جميع معلومات الموقع السياحي</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tourist-sitesController.edit', $touristSite->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">تعديل</a>
            <a href="{{ route('tourist-sitesController.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">العودة للقائمة</a>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns:minmax(0,2fr) minmax(240px,1fr)">
        <div class="bg-white border border-ab-border rounded-[22px] p-6 sm:p-8 flex flex-col gap-4">
            <h2 class="m-0 text-lg font-bold text-ab-navy">{{ $touristSite->name_ar ?? 'غير محدد' }}</h2>

            <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit, minmax(200px,1fr))">
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالعربية</span>
                    <span class="block font-bold text-ab-navy">{{ $touristSite->name_ar ?? 'غير محدد' }}</span>
                </div>
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الاسم بالإنجليزية</span>
                    <span class="block font-bold text-ab-navy" dir="ltr">{{ $touristSite->name_en ?? 'غير محدد' }}</span>
                </div>
                @foreach ($infoRows as $row)
                    <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                        <span class="block text-xs font-semibold text-ab-teal mb-1">{{ $row['label'] }}</span>
                        @if ($row['value'])
                            <a href="{{ $row['link'] }}" class="text-sm font-semibold text-ab-navy underline">{{ $row['value'] }}</a>
                        @else
                            <span class="text-sm text-ab-muted">غير محدد</span>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($touristSite->location)
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الموقع الجغرافي</span>
                    <span class="text-sm text-ab-navy">{{ $touristSite->location }}</span>
                </div>
            @endif

            @if ($touristSite->website_url)
                <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                    <span class="block text-xs font-semibold text-ab-teal mb-1">الموقع الرسمي</span>
                    <a href="{{ $touristSite->website_url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-ab-navy underline">زيارة الموقع</a>
                </div>
            @endif

            <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                <span class="block text-xs font-semibold text-ab-teal mb-1">الوصف بالعربية</span>
                <p class="m-0 text-sm text-ab-navy leading-relaxed">{{ $touristSite->description_ar ?? 'غير محدد' }}</p>
            </div>
            <div class="bg-ab-warm border border-ab-border rounded-2xl p-4">
                <span class="block text-xs font-semibold text-ab-teal mb-1">الوصف بالإنجليزية</span>
                <p class="m-0 text-sm text-ab-navy leading-relaxed" dir="ltr">{{ $touristSite->description_en ?? 'غير محدد' }}</p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-ab-border">
                <span class="text-xs text-ab-muted">آخر تحديث: {{ $touristSite->updated_at->format('Y-m-d H:i:s') }}</span>
                <form action="{{ route('tourist-sitesController.destroy', $touristSite->id) }}" method="POST"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟ سيتم حذف جميع الصور المرتبطة به أيضاً.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-red-50 text-red-600 text-sm font-semibold">حذف الموقع السياحي</button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-ab-border rounded-[22px] p-6 flex flex-col gap-4 h-fit">
            <h3 class="m-0 text-sm font-bold text-ab-navy">معلومات إضافية</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex items-center justify-between"><span class="text-ab-body">معرّف الموقع</span><span class="font-bold text-ab-navy">#{{ $touristSite->id }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">تاريخ الإنشاء</span><span class="font-semibold text-ab-navy">{{ $touristSite->created_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">آخر تحديث</span><span class="font-semibold text-ab-navy">{{ $touristSite->updated_at->diffForHumans() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">عدد الصور</span><span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">{{ $touristSite->images->count() }} صورة</span></div>
                <div class="flex items-center justify-between"><span class="text-ab-body">الموقع الإلكتروني</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $touristSite->website_url ? 'bg-emerald-50 text-emerald-700' : 'bg-ab-cool text-ab-muted' }}">{{ $touristSite->website_url ? 'متوفر' : 'غير متوفر' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white border border-ab-border rounded-[22px] p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            <h2 class="m-0 text-lg font-bold text-ab-navy">إدارة الصور ({{ $touristSite->images->count() }} صورة)</h2>
            <button type="button" onclick="AdminUI.openModal('addImagesModal')"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"></path></svg>
                إضافة صور
            </button>
        </div>

        @if ($touristSite->images->count() > 0)
            <div class="grid gap-4" style="grid-template-columns:repeat(auto-fill, minmax(160px,1fr))">
                @foreach ($touristSite->images as $image)
                    <div class="relative">
                        <img src="{{ $image->image_url }}" alt="{{ $touristSite->name_ar }}" loading="lazy"
                            onclick="showSiteImage('{{ $image->image_url }}', '{{ $touristSite->name_ar }}')"
                            class="w-full h-40 object-cover rounded-2xl cursor-pointer">
                        <form action="{{ route('tourist-sites.images.destroy', [$touristSite->id, $image->id]) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')" class="absolute top-2 left-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="حذف الصورة" class="w-8 h-8 grid place-items-center rounded-full bg-white/90 text-red-600 shadow">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <x-admin.empty-state title="لا توجد صور للموقع السياحي" body="يمكنك إضافة صور للموقع السياحي باستخدام الزر أعلاه." />
        @endif
    </div>

    {{-- نافذة إضافة صور --}}
    <x-admin.modal id="addImagesModal">
        <form action="{{ route('tourist-sites.images.store', $touristSite->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
            @csrf
            <div class="p-6 flex flex-col gap-4">
                <h3 class="m-0 text-lg font-bold text-ab-navy">إضافة صور للموقع السياحي</h3>

                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold text-ab-navy">رفع ملفات الصور</span>
                    <input type="file" name="image_files[]" multiple accept="image/*" id="image_files" onchange="previewSiteFiles(this)"
                        class="text-sm text-ab-body file:me-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-ab-cool file:text-ab-navy file:font-semibold w-full border border-ab-border-2 rounded-2xl p-1.5">
                    @error('image_files') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    <span class="text-xs text-ab-muted">يمكنك اختيار عدة ملفات صور (JPG, PNG, GIF) - الحد الأقصى 2MB لكل صورة</span>
                </label>

                <div id="file_preview" class="hidden">
                    <span class="block text-xs font-semibold text-ab-teal mb-2">معاينة الملفات المرفوعة</span>
                    <div id="preview_container" class="grid grid-cols-3 gap-2"></div>
                </div>

                <div class="flex items-start gap-2 bg-sky-50 border border-sky-200 rounded-2xl p-3 text-sky-800 text-xs">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v5M12 16h.01"></path></svg>
                    <span><strong>ملاحظة:</strong> يمكن رفع ملفات الصور المحلية فقط. الصيغ المدعومة: JPG, PNG, GIF</span>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t border-ab-border">
                <button type="button" onclick="AdminUI.closeModal('addImagesModal')" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold">إلغاء</button>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold">حفظ الصور</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- نافذة عرض الصورة --}}
    <x-admin.modal id="imageModal" max-width="max-w-3xl">
        <div class="p-4">
            <img id="siteImageModalImg" src="" alt="" class="w-full max-h-[75vh] object-contain rounded-xl">
        </div>
    </x-admin.modal>

@endsection

@push('scripts')
<script>
    function showSiteImage(url, title) {
        const img = document.getElementById('siteImageModalImg');
        img.src = url;
        img.alt = title;
        AdminUI.openModal('imageModal');
    }

    function previewSiteFiles(input) {
        const previewBox = document.getElementById('file_preview');
        const container = document.getElementById('preview_container');
        container.innerHTML = '';
        if (!input.files || !input.files.length) {
            previewBox.classList.add('hidden');
            return;
        }
        previewBox.classList.remove('hidden');
        Array.from(input.files).forEach(function (file) {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-20 object-cover rounded-xl';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
