@props(['label', 'value'])

<div class="bg-ab-navy rounded-[22px] p-5 flex items-center gap-4 text-white">
    <span class="w-12 h-12 shrink-0 rounded-2xl bg-white/12 grid place-items-center text-ab-sand">
        {{ $slot }}
    </span>
    <div class="flex flex-col min-w-0">
        <span class="text-2xl font-bold">{{ $value }}</span>
        <span class="text-xs text-white/70 truncate">{{ $label }}</span>
    </div>
</div>
