@extends('layouts.app')

@section('title', 'تفاصيل الموقع السياحي - ' . $touristSite->name_ar)
@section('page-title', 'تفاصيل الموقع السياحي')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">تفاصيل الموقع السياحي</h1>
        <p class="text-muted mb-0">عرض جميع معلومات الموقع السياحي</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('tourist-sitesController.edit', $touristSite->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
        <a href="{{ route('tourist-sitesController.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Info Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-camera me-2"></i>
                    {{ $touristSite->name_ar ?? 'غير محدد' }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالعربية</h6>
                            </div>
                            <p class="fs-5 fw-bold mb-0">{{ $touristSite->name_ar ?? 'غير محدد' }}</p>
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-language text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الاسم بالإنجليزية</h6>
                            </div>
                            <p class="fs-5 mb-0">{{ $touristSite->name_en ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">المحافظة</h6>
                            </div>
                            @if($touristSite->governorate)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info fs-6 me-2">{{ $touristSite->governorate->name_ar }}</span>
                                    <a href="{{ route('governorates.show', $touristSite->governorate->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض المحافظة
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                        
                        <div class="info-item mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="info-icon me-3">
                                    <i class="fas fa-map-marked-alt text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-primary">الولاية</h6>
                            </div>
                            @if($touristSite->wilayat)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary fs-6 me-2">{{ $touristSite->wilayat->name_ar }}</span>
                                    <a href="{{ route('wilayats.show', $touristSite->wilayat->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض الولاية
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($touristSite->location)
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="info-icon me-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">الموقع الجغرافي</h6>
                    </div>
                    <p class="fs-6 mb-0">{{ $touristSite->location }}</p>
                </div>
                @endif

                @if($touristSite->website_url)
                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="info-icon me-3">
                            <i class="fas fa-globe text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">الموقع الرسمي</h6>
                    </div>
                    <a href="{{ $touristSite->website_url }}" 
                       target="_blank" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-2"></i>
                        زيارة الموقع
                    </a>
                </div>
                @endif

                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="info-icon me-3">
                            <i class="fas fa-align-right text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">الوصف بالعربية</h6>
                    </div>
                    <p class="mb-0">{{ $touristSite->description_ar ?? 'غير محدد' }}</p>
                </div>

                <div class="info-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="info-icon me-3">
                            <i class="fas fa-align-left text-primary"></i>
                        </div>
                        <h6 class="mb-0 text-primary">الوصف بالإنجليزية</h6>
                    </div>
                    <p class="mb-0">{{ $touristSite->description_en ?? 'غير محدد' }}</p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            آخر تحديث: {{ $touristSite->updated_at->format('Y-m-d H:i:s') }}
                        </small>
                    </div>
                    <form action="{{ route('tourist-sitesController.destroy', $touristSite->id) }}" 
                          method="POST" 
                          style="display: inline;" 
                          onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            حذف الموقع السياحي
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Side Info Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات إضافية
                </h6>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hashtag text-primary me-2"></i>
                        <small class="text-muted">معرف الموقع</small>
                    </div>
                    <span class="badge bg-primary fs-6">#{{ $touristSite->id }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <small class="text-muted">تاريخ الإنشاء</small>
                    </div>
                    <span class="fw-medium">{{ $touristSite->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-edit text-primary me-2"></i>
                        <small class="text-muted">آخر تحديث</small>
                    </div>
                    <span class="fw-medium">{{ $touristSite->updated_at->diffForHumans() }}</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-images text-primary me-2"></i>
                        <small class="text-muted">عدد الصور</small>
                    </div>
                    <span class="badge bg-success">{{ $touristSite->images->count() }} صورة</span>
                </div>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <small class="text-muted">الموقع الإلكتروني</small>
                    </div>
                    @if($touristSite->website_url)
                        <span class="badge bg-success">متوفر</span>
                    @else
                        <span class="badge bg-secondary">غير متوفر</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Images Management Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-images me-2"></i>
                        إدارة الصور ({{ $touristSite->images->count() }} صورة)
                    </h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addImagesModal">
                        <i class="fas fa-plus"></i>
                        إضافة صور
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($touristSite->images->count() > 0)
                    <div class="row">
                        @foreach($touristSite->images as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="position-relative">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $touristSite->name_ar }}" 
                                     class="img-fluid rounded shadow" 
                                     style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                     onclick="openImageModal('{{ $image->image_url }}', '{{ $touristSite->name_ar }}')">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <form action="{{ route('tourist-sites.images.destroy', [$touristSite->id, $image->id]) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle" 
                                                title="حذف الصورة">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد صور للموقع السياحي</h5>
                        <p class="text-muted">يمكنك إضافة صور للموقع السياحي باستخدام الزر أعلاه</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Images Modal -->
<div class="modal fade" id="addImagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    إضافة صور للموقع السياحي
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist-sites.images.store', $touristSite->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label for="image_files" class="form-label">
                            <i class="fas fa-upload me-1 text-primary"></i>
                            رفع ملفات الصور
                        </label>
                        <input type="file" 
                               class="form-control @error('image_files') is-invalid @enderror" 
                               id="image_files" 
                               name="image_files[]" 
                               multiple 
                               accept="image/*"
                               onchange="previewFiles(this)">
                        @error('image_files')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            يمكنك اختيار عدة ملفات صور (JPG, PNG, GIF) - الحد الأقصى 2MB لكل صورة
                        </div>
                    </div>
                    
                    <!-- File Preview Section -->
                    <div id="file_preview" class="mb-4" style="display: none;">
                        <h6 class="text-muted mb-2">معاينة الملفات المرفوعة:</h6>
                        <div id="preview_container" class="row"></div>
                    </div>
                    
                    <!-- Note about local files only -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>ملاحظة:</strong> يمكن رفع ملفات الصور المحلية فقط. الصيغ المدعومة: JPG, PNG, GIF
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ الصور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .info-item {
        padding: 1rem;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: rgba(97, 76, 57, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .info-icon i {
        font-size: 1.2rem;
    }
</style>
@endpush

@push('scripts')
<script>
    let imageCount = 0;
    
    function openImageModal(imageUrl, siteName) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalImage').alt = siteName;
        document.getElementById('imageModalTitle').textContent = siteName;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
    
    // File preview function
    function previewFiles(input) {
        const previewContainer = document.getElementById('preview_container');
        const filePreview = document.getElementById('file_preview');
        
        if (input.files && input.files.length > 0) {
            filePreview.style.display = 'block';
            previewContainer.innerHTML = '';
            
            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-6 mb-2';
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" 
                                     alt="معاينة ${index + 1}" 
                                     class="img-fluid rounded shadow" 
                                     style="width: 100%; height: 80px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 m-1">
                                    <span class="badge bg-primary">${index + 1}</span>
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            filePreview.style.display = 'none';
        }
    }
    
    // تم إزالة دوال إضافة الروابط الخارجية - النظام يدعم الملفات المحلية فقط
    
    function confirmDelete() {
        return confirm('هل أنت متأكد من حذف هذا الموقع السياحي؟ سيتم حذف جميع الصور المرتبطة به أيضاً.');
    }
</script>
@endpush
@endsection
