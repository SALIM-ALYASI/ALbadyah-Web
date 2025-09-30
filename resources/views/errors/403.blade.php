@extends('errors.layout')

@section('title', 'خطأ 403 - ممنوع الوصول')

@section('content')
<div class="error-icon" style="color: var(--error-color);">
    <i class="fas fa-ban"></i>
</div>

<div class="error-code">403</div>

<div class="error-title">ممنوع الوصول</div>

<div class="error-description">
    عذراً، ليس لديك صلاحية للوصول إلى هذه الصفحة. يرجى التواصل مع المسؤول إذا كنت تعتقد أن هذا خطأ.
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
    <h6><i class="fas fa-exclamation-circle me-2"></i>معلومات إضافية:</h6>
    <p>خطأ 403 يعني أن الخادم فهم طلبك لكنه يرفض تنفيذه. قد تحتاج إلى صلاحيات إضافية أو قد تكون الصفحة محظورة.</p>
</div>
@endsection
