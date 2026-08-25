@props(['id', 'maxWidth' => 'max-w-2xl'])

<div id="{{ $id }}" class="fixed inset-0 z-[60] grid place-items-center p-4" style="display:none" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/70" onclick="AdminUI.closeModal('{{ $id }}')"></div>
    <div class="relative w-full {{ $maxWidth }} bg-white rounded-[22px] overflow-hidden max-h-[90vh] flex flex-col">
        <button type="button" onclick="AdminUI.closeModal('{{ $id }}')" aria-label="إغلاق"
            class="absolute top-3 left-3 z-10 w-9 h-9 rounded-full bg-white/90 grid place-items-center text-ab-navy shadow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"></path></svg>
        </button>
        <div class="overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
