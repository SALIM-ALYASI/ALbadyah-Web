@extends('layouts.app')

@section('title', 'تفاصيل الموقع السياحي - ' . $touristSite->name_ar)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-dark">
                        <i class="fas fa-map-marked-alt text-primary mr-2"></i>
                        تفاصيل الموقع السياحي
                    </h1>
                    <p class="text-muted mb-0">{{ $touristSite->name_ar }}</p>
                </div>
                <div>
                    <a href="{{ route('tourist-sites-new.edit', $touristSite->id) }}" class="btn btn-warning btn-lg mr-2">
                        <i class="fas fa-edit mr-2"></i>
                        تعديل
                    </a>
                    <a href="{{ route('tourist-sites-new.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-right mr-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- المعلومات الأساسية -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        المعلومات الأساسية
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    الاسم بالعربية
                                </h6>
                                <p class="mb-0">{{ $touristSite->name_ar }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    الاسم بالإنجليزية
                                </h6>
                                <p class="mb-0">{{ $touristSite->name_en }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-align-right mr-1"></i>
                                    الوصف بالعربية
                                </h6>
                                <p class="mb-0">{{ $touristSite->description_ar }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-align-left mr-1"></i>
                                    الوصف بالإنجليزية
                                </h6>
                                <p class="mb-0">{{ $touristSite->description_en }}</p>
                            </div>
                        </div>
                    </div>

                    @if($touristSite->location)
                        <div class="info-item mb-4">
                            <h6 class="text-primary fw-bold mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                الموقع الجغرافي
                            </h6>
                            <p class="mb-0">{{ $touristSite->location }}</p>
                        </div>
                    @endif

                    <!-- المحافظة والولاية -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-success fw-bold mb-2">
                                    <i class="fas fa-building mr-1"></i>
                                    المحافظة
                                </h6>
                                @if($touristSite->governorate)
                                    <span class="badge bg-success fs-6">{{ $touristSite->governorate->name_ar }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-success fw-bold mb-2">
                                    <i class="fas fa-map-pin mr-1"></i>
                                    الولاية
                                </h6>
                                @if($touristSite->wilayat)
                                    <span class="badge bg-success fs-6">{{ $touristSite->wilayat->name_ar }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- الحالة والتاريخ -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-info fw-bold mb-2">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    الحالة
                                </h6>
                                @if($touristSite->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="text-info fw-bold mb-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    تاريخ الإنشاء
                                </h6>
                                <p class="mb-0">{{ $touristSite->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصورة المميزة وإدارة الصور -->
        <div class="col-lg-4">
            <!-- الصورة المميزة -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star mr-2"></i>
                        الصورة المميزة
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    @if($touristSite->featured_image)
                        <img src="{{ $touristSite->featured_image }}" alt="{{ $touristSite->name_ar }}" 
                             class="img-fluid rounded shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
                        <div class="mt-3">
                            <button class="btn btn-outline-warning btn-sm" onclick="openImageModal('{{ $touristSite->featured_image }}', '{{ $touristSite->name_ar }}')">
                                <i class="fas fa-expand mr-1"></i>
                                عرض بحجم أكبر
                            </button>
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="text-muted">لا توجد صورة مميزة</p>
                                <a href="{{ route('tourist-sites-new.edit', $touristSite->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i>
                                    إضافة صورة
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- رفع صور جديدة -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>
                        رفع صور جديدة
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tourist-sites-new.images.store', $touristSite->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="images" class="form-label fw-bold">
                                <i class="fas fa-images mr-1"></i>
                                اختيار الصور
                            </label>
                            <input type="file" name="images[]" id="images" 
                                   class="form-control @error('images') is-invalid @enderror" 
                                   accept="image/*" multiple onchange="previewMultipleImages(this, 'upload-preview')">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                يمكن اختيار عدة صور في نفس الوقت. الصيغ المقبولة: GIF, JPEG, PNG, JPG. الحد الأقصى: 2MB لكل صورة
                            </small>
                        </div>

                        <div id="upload-preview" class="row mb-3"></div>

                        <button type="submit" class="btn btn-info w-100" id="uploadBtn">
                            <i class="fas fa-upload mr-2"></i>
                            رفع الصور
                        </button>
                    </form>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>
                        إحصائيات سريعة
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ strlen($touristSite->description_ar) }}</h4>
                                <small class="text-muted">حرف (عربي)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ strlen($touristSite->description_en) }}</h4>
                            <small class="text-muted">حرف (إنجليزي)</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-info mb-1">{{ $touristSite->images->count() }}</h4>
                            <small class="text-muted">صورة إضافية</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الصور الإضافية -->
    @if($touristSite->images->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images mr-2"></i>
                            الصور الإضافية ({{ $touristSite->images->count() }})
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog mr-1"></i>
                                إدارة الصور
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="selectAllImages()">
                                    <i class="fas fa-check-square mr-2"></i>تحديد الكل</a></li>
                                <li><a class="dropdown-item" href="#" onclick="clearSelection()">
                                    <i class="fas fa-square mr-2"></i>إلغاء التحديد</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteSelectedImages()">
                                    <i class="fas fa-trash mr-2"></i>حذف المحدد</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row" id="image-gallery">
                            @foreach($touristSite->images->sortBy('sort_order') as $image)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-image-id="{{ $image->id }}">
                                    <div class="card shadow-sm h-100">
                                        <div class="position-relative">
                                            <input type="checkbox" class="form-check-input position-absolute" 
                                                   style="top: 10px; right: 10px; z-index: 10;" 
                                                   data-image-id="{{ $image->id }}">
                                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text_ar }}" 
                                                 class="card-img-top" style="height: 200px; object-fit: cover; cursor: pointer;"
                                                 onclick="openImageModal('{{ $image->image_url }}', '{{ $image->alt_text_ar }}')">
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-sort mr-1"></i>
                                                    ترتيب: {{ $image->sort_order }}
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" 
                                                            onclick="openImageModal('{{ $image->image_url }}', '{{ $image->alt_text_ar }}')"
                                                            title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <form action="{{ route('tourist-sites-new.images.destroy', [$touristSite->id, $image->id]) }}" 
                                                          method="POST" style="display: inline-block;" 
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- أزرار الإجراءات -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                آخر تحديث: {{ $touristSite->updated_at->format('H:i:s d-m-Y') }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('tourist-sites-new.edit', $touristSite->id) }}" class="btn btn-warning btn-lg mr-2">
                                <i class="fas fa-edit mr-2"></i>
                                تعديل
                            </a>
                            <form action="{{ route('tourist-sites-new.destroy', $touristSite->id) }}" method="POST" 
                                  style="display: inline-block;" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-trash mr-2"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لعرض الصور -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">عرض الصورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
}

.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #007bff;
}

.btn {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

#image-gallery .card {
    transition: all 0.3s ease;
}

#image-gallery .card:hover {
    transform: scale(1.02);
}

.preview-item {
    position: relative;
    margin-bottom: 10px;
}

.preview-item .remove-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 12px;
}

#upload-preview img {
    border-radius: 10px;
    cursor: pointer;
}
</style>
@endsection

@push('scripts')
<script>
// معاينة الصور المتعددة
function previewMultipleImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-6 preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="معاينة ${index + 1}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                <button type="button" class="remove-btn" onclick="removePreview(this, ${index})">×</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// إزالة معاينة الصورة
function removePreview(btn, index) {
    btn.parentElement.remove();
}

// فتح modal لعرض الصورة
function openImageModal(imageSrc, imageAlt) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImage').alt = imageAlt;
    document.getElementById('imageModalTitle').textContent = imageAlt;
    
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// تحديد جميع الصور
function selectAllImages() {
    const checkboxes = document.querySelectorAll('#image-gallery input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

// إلغاء تحديد جميع الصور
function clearSelection() {
    const checkboxes = document.querySelectorAll('#image-gallery input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// حذف الصور المحددة
function deleteSelectedImages() {
    const checkboxes = document.querySelectorAll('#image-gallery input[type="checkbox"]:checked');
    if (checkboxes.length === 0) {
        alert('يرجى تحديد الصور المراد حذفها');
        return;
    }
    
    if (confirm(`هل أنت متأكد من حذف ${checkboxes.length} صورة؟`)) {
        checkboxes.forEach(checkbox => {
            const imageId = checkbox.dataset.imageId;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('tourist-sites-new.images.destroy', [$touristSite->id, '']) }}/${imageId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// تحسين تجربة رفع الصور
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const files = document.getElementById('images').files;
    if (files.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار صور للرفع');
        return false;
    }
    
    const uploadBtn = document.getElementById('uploadBtn');
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الرفع...';
    uploadBtn.disabled = true;
});

// إضافة تأثيرات بصرية للصور
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('#image-gallery img');
    images.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush