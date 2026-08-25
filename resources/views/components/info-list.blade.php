@props(['rows'])

<div class="flex flex-col">
    @foreach ($rows as $index => $row)
        <div class="flex items-start gap-3 py-3.5 {{ $index < count($rows) - 1 ? 'border-b border-ab-border' : '' }}">
            <span class="w-10 h-10 shrink-0 grid place-items-center rounded-2xl bg-white border border-ab-border text-ab-teal text-base font-bold">{{ $row['glyph'] }}</span>
            <span class="flex flex-col gap-0.5 min-w-0">
                <span class="text-[13px] font-semibold text-ab-body">{{ $row['label'] }}</span>
                @if (!empty($row['value']))
                    <span class="text-[15.5px] font-medium text-ab-navy break-words">{{ $row['value'] }}</span>
                @else
                    <span class="text-[15.5px] font-medium text-ab-muted">لا توجد بيانات بعد</span>
                @endif
            </span>
        </div>
    @endforeach
</div>
