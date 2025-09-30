@extends('errors.layout')

@section('title', 'خطأ 400 - طلب غير صحيح')

@section('content')
<div class="error-icon" style="color: var(--warning-color);">
    <i class="fas fa-exclamation-triangle"></i>
</div>

<div class="error-code">400</div>

<div class="error-title">طلب غير صحيح</div>

<div class="error-description">
    عذراً، يبدو أن هناك مشكلة في الطلب الذي أرسلته. يرجى التحقق من البيانات المدخلة والمحاولة مرة أخرى.
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
    <h6><i class="fas fa-info-circle me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 400 يعني أن الخادم لا يستطيع معالجة الطلب بسبب خطأ في البيانات المرسلة. تأكد من صحة جميع الحقول المطلوبة.</p>
</div>
@endsection
