@extends('errors.layout')

@section('title', 'خطأ 404 - الصفحة غير موجودة')

@section('content')
<div class="error-icon" style="color: var(--neutral-color);">
    <i class="fas fa-search"></i>
</div>

<div class="error-code">404</div>

<div class="error-title">الصفحة غير موجودة</div>

<div class="error-description">
    عذراً، الصفحة التي تبحث عنها غير موجودة. يرجى التحقق من الرابط أو العودة للصفحة الرئيسية.
</div>

<div class="error-actions">
    <a href="{{ url()->previous() }}" class="btn-error btn-outline-error">
        <i class="fas fa-arrow-right"></i>
        العودة للصفحة السابقة
    </a>
    <a href="{{ route('tourism.governorates') }}" class="btn-error btn-primary-error">
        <i class="fas fa-home"></i>
        الصفحة الرئيسية
    </a>
</div>

<div class="error-details">
    <h6><i class="fas fa-map-marked-alt me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 404 يعني أن الصفحة المطلوبة غير موجودة. تأكد من صحة الرابط أو استخدم البحث للعثور على ما تبحث عنه.</p>
</div>
@endsection
