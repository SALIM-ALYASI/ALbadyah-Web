@extends('errors.layout')

@section('title', 'خطأ 500 - خطأ في الخادم')

@section('content')
<div class="error-icon" style="color: var(--error-color);">
    <i class="fas fa-server"></i>
</div>

<div class="error-code">500</div>

<div class="error-title">خطأ في الخادم</div>

<div class="error-description">
    عذراً، حدث خطأ داخلي في الخادم. فريقنا يعمل على حل هذه المشكلة. يرجى المحاولة مرة أخرى لاحقاً.
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
    <h6><i class="fas fa-tools me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 500 يعني أن هناك مشكلة في الخادم. إذا استمرت المشكلة، يرجى التواصل مع فريق الدعم الفني.</p>
</div>
@endsection
