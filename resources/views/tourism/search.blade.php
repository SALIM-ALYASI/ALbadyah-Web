@extends('layouts.tourism')

@section('title', 'البحث - البادية')
@section('description', 'ابحث عن وجهتك المثالية في سلطنة عُمان')

@php
    $tabs = [
        ['key' => 'all', 'label' => 'الكل'],
        ['key' => 'sites', 'label' => 'المواقع السياحية'],
        ['key' => 'wilayats', 'label' => 'الولايات'],
        ['key' => 'services', 'label' => 'الخدمات'],
        ['key' => 'governorates', 'label' => 'المحافظات'],
    ];

    $groups = [];
    if ($touristSites->isNotEmpty()) {
        $groups[] = ['title' => 'المواقع السياحية', 'items' => $touristSites->map(fn ($s) => [
            'name' => $s->name_ar,
            'meta' => implode(' · ', array_filter([$s->governorate?->name_ar, $s->wilayat?->name_ar])),
            'url' => route('tourism.tourist-site', $s->slug ?: $s->id),
            'image' => $s->images->isNotEmpty() ? $s->featured_image : null,
        ])];
    }
    if ($wilayatsResults->isNotEmpty()) {
        $groups[] = ['title' => 'الولايات', 'items' => $wilayatsResults->map(fn ($w) => [
            'name' => $w->name_ar,
            'meta' => $w->governorate?->name_ar,
            'url' => route('tourism.wilayat', $w->slug ?: $w->id),
            'image' => null,
        ])];
    }
    if ($touristServices->isNotEmpty()) {
        $groups[] = ['title' => 'الخدمات', 'items' => $touristServices->map(fn ($s) => [
            'name' => $s->name_ar,
            'meta' => $s->serviceType?->name_ar,
            'url' => route('tourism.tourist-service', $s->slug ?: $s->id),
            'image' => $s->has_image ? $s->image_url : null,
        ])];
    }
    if ($governoratesResults->isNotEmpty()) {
        $groups[] = ['title' => 'المحافظات', 'items' => $governoratesResults->map(fn ($g) => [
            'name' => $g->name_ar,
            'meta' => null,
            'url' => route('tourism.governorate', $g->slug ?: $g->id),
            'image' => $g->has_image ? $g->image_url : null,
        ])];
    }
    $isEmpty = empty($groups);
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'البحث'],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <h1 class="m-0 text-ab-navy font-bold text-3xl md:text-4xl mb-2">ابحث في البادية</h1>
        <p class="m-0 text-ab-body">محافظة، ولاية، موقع سياحي أو خدمة — بحث واحد يغطي كل المنصة.</p>

        <form action="{{ route('tourism.search') }}" method="GET" class="relative mt-6 max-w-2xl">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8A9B9E" stroke-width="2.5" stroke-linecap="round" class="absolute top-1/2 -translate-y-1/2 right-5 pointer-events-none"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
            <input type="text" name="query" value="{{ $query }}" autocomplete="off" placeholder="ابحث عن مكان أو خدمة..."
                class="w-full bg-white border border-ab-border-2 rounded-full pr-14 pl-5 py-4 text-ab-navy" style="box-shadow:0 12px 30px rgba(36,59,68,.08)">
        </form>

        <div class="flex flex-wrap items-center gap-2 mt-4">
            <span class="text-sm text-ab-muted">أمثلة:</span>
            @foreach (['مطرح', 'قلعة', 'فندق في مسقط', 'مول'] as $example)
                <a href="{{ route('tourism.search', ['query' => $example]) }}" class="px-4 py-1.5 rounded-full bg-ab-cool text-ab-navy text-[13px] no-underline">{{ $example }}</a>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center gap-2 mt-6">
            @foreach ($tabs as $t)
                <a href="{{ route('tourism.search', ['query' => $query, 'tab' => $t['key']]) }}"
                    class="px-4 py-2 rounded-full text-sm font-semibold no-underline {{ $tab === $t['key'] ? 'bg-ab-navy text-white' : 'border border-ab-border text-ab-navy' }}">{{ $t['label'] }}</a>
            @endforeach
        </div>

        <div class="mt-8 mb-16">
            @if ($isEmpty)
                <x-empty-state
                    :title="$query ? 'لا توجد نتائج لـ «' . $query . '»' : 'ابدأ بكتابة كلمة للبحث'"
                    :body="$query ? 'جرّب كلمة أخرى، مثل اسم ولاية أو محافظة.' : 'ابحث عن اسم موقع سياحي أو خدمة أو ولاية أو محافظة.'">
                    <x-slot:actions>
                        <a href="{{ route('tourism.tourist-sites') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">كل المواقع السياحية</a>
                        <a href="{{ route('tourism.tourist-services') }}" class="px-5 py-2.5 rounded-full border border-ab-border-2 text-ab-navy text-sm font-semibold no-underline">كل الخدمات</a>
                    </x-slot:actions>
                </x-empty-state>
            @else
                <div class="flex flex-col gap-8">
                    @foreach ($groups as $group)
                        <div>
                            <p class="text-sm font-semibold text-ab-muted mb-3">{{ $group['title'] }} · {{ count($group['items']) }} {{ count($group['items']) == 1 ? 'نتيجة' : 'نتائج' }}</p>
                            <div class="grid gap-3" style="grid-template-columns:repeat(auto-fill, minmax(300px,1fr))">
                                @foreach ($group['items'] as $item)
                                    <a href="{{ $item['url'] }}" class="flex items-center gap-3 bg-white border border-ab-border rounded-[22px] p-3.5 no-underline" style="min-height:96px">
                                        @if ($item['image'])
                                            <img src="{{ $item['image'] }}" alt="" class="w-[72px] h-[72px] shrink-0 rounded-2xl object-cover">
                                        @else
                                            <span class="w-[72px] h-[72px] shrink-0 rounded-2xl bg-ab-cool grid place-items-center text-ab-teal text-xl font-bold">{{ mb_substr($item['name'], 0, 1) }}</span>
                                        @endif
                                        <span class="flex flex-col min-w-0 flex-1">
                                            <span class="font-bold text-ab-navy truncate">{{ $item['name'] }}</span>
                                            @if ($item['meta'])
                                                <span class="text-[13px] text-[#7C8F94] truncate">{{ $item['meta'] }}</span>
                                            @endif
                                        </span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="m9 18 6-6-6-6"></path></svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection
