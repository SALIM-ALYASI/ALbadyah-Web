@extends('layouts.tourism')

@section('title', 'ولايات عُمان - البادية')
@section('description', 'تصفّح ولايات سلطنة عُمان الثلاث والستين')

@php
    $isFiltered = request()->filled('search') || request()->filled('governorate_id');
@endphp

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'الولايات'],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="max-w-[780px]">
            <h1 class="mb-3 text-ab-navy font-bold" style="font-size:clamp(34px,5.4vw,56px)">ولايات عُمان</h1>
            <p class="m-0 text-ab-body text-lg leading-relaxed">تصفّح ولايات سلطنة عُمان الثلاث والستين، وفلتر حسب المحافظة.</p>
        </div>

        <div class="mt-8">
            @include('tourism.partials.listing-filters', [
                'action' => route('tourism.wilayats'),
                'countLabel' => $wilayats->total() . ' ولاية',
                'isFiltered' => $isFiltered,
                'showViewToggle' => false,
                'selects' => [
                    ['name' => 'governorate_id', 'label' => 'المحافظة', 'selected' => request('governorate_id'), 'options' => $governorates->pluck('name_ar', 'id')],
                ],
            ])
        </div>

        <div class="mt-8 mb-6">
            @if ($wilayats->isEmpty())
                <x-empty-state title="لا توجد ولايات مطابقة" body="جرّب تعديل كلمة البحث أو إزالة الفلاتر.">
                    <x-slot:actions>
                        <a href="{{ route('tourism.wilayats') }}" class="px-5 py-2.5 rounded-full bg-ab-navy text-white text-sm font-semibold no-underline">إزالة الفلاتر</a>
                    </x-slot:actions>
                </x-empty-state>
            @else
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill, minmax(268px,1fr))">
                    @foreach ($wilayats as $wilayat)
                        <x-wilayat-card :wilayat="$wilayat" />
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mb-16">{{ $wilayats->onEachSide(1)->links() }}</div>
    </section>

@endsection
