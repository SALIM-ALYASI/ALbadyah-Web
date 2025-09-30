@extends('errors.layout')

@section('title', 'خطأ 401 - غير مخول للوصول')

@section('content')
<div class="error-icon" style="color: var(--error-color);">
    <i class="fas fa-lock"></i>
</div>

<div class="error-code">401</div>

<div class="error-title">غير مخول للوصول</div>

<div class="error-description">
    عذراً، تحتاج إلى تسجيل الدخول للوصول إلى هذه الصفحة. يرجى تسجيل الدخول أولاً.
</div>

<div class="error-actions">
    <a href="{{ route('login') }}" class="btn-error btn-primary-error">
        <i class="fas fa-sign-in-alt"></i>
        تسجيل الدخول
    </a>
    <a href="{{ route('tourism.governorates') }}" class="btn-error btn-outline-error">
        <i class="fas fa-home"></i>
        الصفحة الرئيسية
    </a>
</div>

<div class="error-details">
    <h6><i class="fas fa-shield-alt me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 401 يعني أنك تحتاج إلى مصادقة صحيحة للوصول إلى هذا المورد. تأكد من تسجيل الدخول بحساب صحيح.</p>
</div>
@endsection
