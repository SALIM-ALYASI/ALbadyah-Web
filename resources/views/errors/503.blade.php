@extends('errors.layout')

@section('title', 'خطأ 503 - الخدمة غير متاحة')

@section('content')
<div class="error-icon" style="color: var(--warning-color);">
    <i class="fas fa-tools"></i>
</div>

<div class="error-code">503</div>

<div class="error-title">الخدمة غير متاحة</div>

<div class="error-description">
    عذراً، الخدمة غير متاحة حالياً. نحن نقوم بأعمال الصيانة أو التحديث. يرجى المحاولة مرة أخرى خلال بضع دقائق.
</div>

<div class="error-actions">
    <a href="javascript:location.reload()" class="btn-error btn-primary-error">
        <i class="fas fa-sync-alt"></i>
        إعادة المحاولة
    </a>
    <a href="{{ route('tourism.governorates') }}" class="btn-error btn-outline-error">
        <i class="fas fa-home"></i>
        الصفحة الرئيسية
    </a>
</div>

<div class="error-details">
    <h6><i class="fas fa-wrench me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 503 يعني أن الخادم غير متاح مؤقتاً. عادة ما يكون هذا بسبب أعمال الصيانة أو التحديث. نعتذر عن الإزعاج.</p>
</div>
@endsection
