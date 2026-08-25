@props([
    'name' => 'image',
    'urlName' => null,
    'label' => 'الصورة',
    'current' => null,
    'required' => false,
])

@php $uid = 'preview-' . $name . '-' . uniqid(); @endphp

<div class="flex flex-col gap-3">
    <span class="text-sm font-semibold text-ab-navy">{{ $label }}</span>
    <div class="flex flex-col sm:flex-row gap-4 items-start">
        <div id="{{ $uid }}" data-placeholder='<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" style="margin:auto"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>'
            class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden bg-ab-cool border border-ab-border grid place-items-center">
            @if ($current)
                <img src="{{ $current }}" class="w-full h-full object-cover" alt="">
            @else
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
            @endif
        </div>

        <div class="flex-1 flex flex-col gap-3 w-full min-w-0">
            <label class="flex flex-col gap-1.5">
                <span class="text-xs text-ab-body">رفع من الجهاز</span>
                <input type="file" name="{{ $name }}" accept="image/*" {{ $required && !$current ? 'required' : '' }}
                    onchange="AdminUI.previewImageFile(this, '{{ $uid }}')"
                    class="text-sm text-ab-body file:me-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-ab-cool file:text-ab-navy file:font-semibold w-full border border-ab-border-2 rounded-2xl p-1.5">
            </label>
            @if ($urlName)
                <label class="flex flex-col gap-1.5">
                    <span class="text-xs text-ab-body">أو رابط صورة مباشر</span>
                    <input type="url" name="{{ $urlName }}" value="{{ old($urlName) }}" placeholder="https://..."
                        oninput="AdminUI.previewImageUrl(this, '{{ $uid }}')"
                        class="w-full border border-ab-border-2 rounded-2xl px-4 py-2.5 text-sm text-ab-navy focus:outline-none focus:border-ab-teal">
                </label>
            @endif
        </div>
    </div>
</div>
