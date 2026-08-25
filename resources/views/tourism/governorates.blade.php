@extends('layouts.tourism')

@section('title', 'محافظات عُمان - البادية')
@section('description', 'تصفّح محافظات سلطنة عُمان الإحدى عشرة وولاياتها الثلاث والستين')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'المحافظات'],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="max-w-[780px]">
            <span class="inline-flex items-center gap-2 bg-ab-chip-bg text-ab-chip-text text-[13px] font-semibold px-4 py-2 rounded-full">{{ $governorates->count() }} محافظة · {{ $governorates->sum('wilayats_count') }} ولاية</span>
            <h1 class="mt-4 mb-3 text-ab-navy font-bold" style="font-size:clamp(34px,5.4vw,56px)">محافظات عُمان</h1>
            <p class="m-0 text-ab-body text-lg leading-relaxed">تصفّح محافظات سلطنة عُمان الإحدى عشرة، وادخل على أي واحدة لاستكشاف ولاياتها ومواقعها وخدماتها.</p>
        </div>

        <div class="grid gap-5 mt-10 mb-16" style="grid-template-columns:repeat(auto-fill, minmax(268px,1fr))">
            @foreach ($governorates as $governorate)
                <x-governorate-card :governorate="$governorate" />
            @endforeach
        </div>
    </section>

@endsection
