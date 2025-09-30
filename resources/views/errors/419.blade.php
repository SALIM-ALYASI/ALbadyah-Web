@extends('errors.layout')

@section('title', 'خطأ 419 - انتهت صلاحية الصفحة')

@section('content')
<div class="error-icon" style="color: var(--warning-color);">
    <i class="fas fa-clock"></i>
</div>

<div class="error-code">419</div>

<div class="error-title">انتهت صلاحية الصفحة</div>

<div class="error-description">
    عذراً، انتهت صلاحية هذه الصفحة. يرجى تحديث الصفحة والمحاولة مرة أخرى.
</div>

<div class="error-actions">
    <a href="javascript:location.reload()" class="btn-error btn-primary-error">
        <i class="fas fa-sync-alt"></i>
        تحديث الصفحة
    </a>
    <a href="{{ route('tourism.governorates') }}" class="btn-error btn-outline-error">
        <i class="fas fa-home"></i>
        الصفحة الرئيسية
    </a>
</div>

<div class="error-details">
    <h6><i class="fas fa-hourglass-half me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 419 يعني أن رمز الحماية (CSRF Token) انتهت صلاحيته. هذا يحدث عادة عند ترك الصفحة مفتوحة لفترة طويلة.</p>
</div>
@endsection
