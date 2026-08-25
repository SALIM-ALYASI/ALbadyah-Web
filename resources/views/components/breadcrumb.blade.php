@props(['items'])

<nav aria-label="مسار التصفح" class="max-w-[1240px] mx-auto px-5 pt-6 flex flex-wrap items-center gap-2 text-[13.5px]">
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B7C6C4" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>
        @endif
        @if (!empty($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="text-ab-body no-underline hover:text-ab-navy">{{ $item['label'] }}</a>
        @else
            <span class="font-bold text-ab-navy">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
