@extends('layouts.tourism')

@section('title', 'عن البادية - البادية')
@section('description', 'البادية منصة رقمية تعرّف بالمواقع والخدمات السياحية في سلطنة عُمان')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'الرئيسية', 'url' => route('tourism.index')],
        ['label' => 'عن البادية'],
    ]" />

    <section class="max-w-[1240px] mx-auto px-5 pt-4">
        <div class="relative overflow-hidden rounded-[34px] bg-ab-navy p-8 md:p-14">
            <span class="absolute -top-[80px] -left-[100px] w-[320px] h-[320px] rounded-full bg-white/5"></span>
            <div class="relative flex flex-col gap-5 max-w-2xl">
                <span class="inline-flex self-start items-center gap-2 bg-ab-sand/15 text-ab-sand text-[13px] font-semibold px-4 py-2 rounded-full">عن البادية</span>
                <h1 class="m-0 text-white font-bold" style="font-size:clamp(34px,5.4vw,56px)">دليلك إلى سياحة عُمان</h1>
                <p class="m-0 text-white/80 text-lg leading-relaxed">البادية منصة رقمية تجمع المواقع والخدمات السياحية في محافظات وولايات سلطنة عُمان في مكان واحد، لتساعدك على التخطيط لرحلتك بسهولة.</p>
            </div>
        </div>
    </section>

    <section class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(180px,1fr))">
            @foreach ([['value' => $stats['total_governorates'], 'label' => 'محافظة'], ['value' => $stats['total_wilayats'], 'label' => 'ولاية'], ['value' => $stats['total_tourist_sites'], 'label' => 'موقع سياحي'], ['value' => $stats['total_tourist_services'], 'label' => 'خدمة سياحية']] as $stat)
                <div class="bg-white border border-ab-border rounded-[22px] p-6 text-center">
                    <span class="block text-3xl font-bold text-ab-navy">{{ $stat['value'] }}</span>
                    <span class="block mt-1 text-sm text-ab-body">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="max-w-[1240px] mx-auto px-5 pt-14 md:pt-20">
        <div class="grid gap-5" style="grid-template-columns:repeat(auto-fit, minmax(280px,1fr))">
            <div class="bg-white border border-ab-border rounded-[22px] p-7 flex flex-col gap-3">
                <span class="w-[54px] h-[54px] rounded-2xl bg-ab-cool grid place-items-center text-ab-teal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                </span>
                <h3 class="m-0 text-lg font-bold text-ab-navy">بيانات حقيقية فقط</h3>
                <p class="m-0 text-sm text-ab-body leading-relaxed">لا نضيف بيانات غير مؤكدة. كل حقل لا يتوفر له مصدر حقيقي يظهر بوضوح كـ«لا توجد بيانات بعد» بدل تلفيقه.</p>
            </div>
            <div class="bg-white border border-ab-border rounded-[22px] p-7 flex flex-col gap-3">
                <span class="w-[54px] h-[54px] rounded-2xl bg-ab-cool grid place-items-center text-ab-teal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
                </span>
                <h3 class="m-0 text-lg font-bold text-ab-navy">تغطية كل عُمان</h3>
                <p class="m-0 text-sm text-ab-body leading-relaxed">١١ محافظة و٦٣ ولاية مُدرجة على المنصة، ونعمل باستمرار على إضافة مواقع وخدمات جديدة.</p>
            </div>
            <div class="bg-white border border-ab-border rounded-[22px] p-7 flex flex-col gap-3">
                <span class="w-[54px] h-[54px] rounded-2xl bg-ab-cool grid place-items-center text-ab-teal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                </span>
                <h3 class="m-0 text-lg font-bold text-ab-navy">اتجاهات فورية</h3>
                <p class="m-0 text-sm text-ab-body leading-relaxed">كل موقع وخدمة مرتبط مباشرة بخرائط جوجل، بلا خرائط وهمية أو معلومات غير دقيقة.</p>
            </div>
        </div>
    </section>

    <section class="max-w-[1240px] mx-auto px-5 py-14 md:py-20">
        <div class="rounded-[30px] bg-ab-navy p-8 md:p-12 text-center flex flex-col items-center gap-3">
            <h2 class="m-0 text-2xl md:text-3xl font-bold text-white">ابدأ رحلتك مع البادية</h2>
            <p class="m-0 max-w-xl text-white/70">تصفّح المحافظات، أو ابحث مباشرة عن وجهتك.</p>
            <div class="flex flex-wrap items-center justify-center gap-3 mt-2">
                <a href="{{ route('tourism.governorates') }}" class="px-6 py-3 rounded-full bg-ab-sand text-ab-navy text-sm font-semibold no-underline">تصفّح المحافظات</a>
                <a href="{{ route('tourism.search') }}" class="px-6 py-3 rounded-full border border-white/30 text-white text-sm font-semibold no-underline">ابحث الآن</a>
            </div>
        </div>
    </section>

@endsection
