<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .debug { background: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <h1>اختبار Form إضافة موقع سياحي</h1>
    
    <div class="debug">
        <h3>معلومات الـ Route:</h3>
        <p><strong>URL:</strong> /dashboard/tourist-sites</p>
        <p><strong>Method:</strong> POST</p>
        <p><strong>Action:</strong> {{ route('tourist-sites.store') }}</p>
    </div>

    <form action="/dashboard/tourist-sites" method="POST" enctype="multipart/form-data" id="testForm">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <div class="form-group">
            <label for="name_ar">الاسم بالعربية *</label>
            <input type="text" name="name_ar" id="name_ar" value="موقع سياحي تجريبي" required>
        </div>
        
        <div class="form-group">
            <label for="name_en">الاسم بالإنجليزية *</label>
            <input type="text" name="name_en" id="name_en" value="Test Tourist Site" required>
        </div>
        
        <div class="form-group">
            <label for="description_ar">الوصف بالعربية *</label>
            <textarea name="description_ar" id="description_ar" required>وصف تجريبي للموقع السياحي</textarea>
        </div>
        
        <div class="form-group">
            <label for="description_en">الوصف بالإنجليزية *</label>
            <textarea name="description_en" id="description_en" required>Test description for tourist site</textarea>
        </div>
        
        <div class="form-group">
            <label for="location">الموقع الجغرافي</label>
            <input type="text" name="location" id="location" value="مسقط، سلطنة عمان">
        </div>
        
        <div class="form-group">
            <label for="website_url">رابط الموقع</label>
            <input type="url" name="website_url" id="website_url" value="https://example.com">
        </div>
        
        <div class="form-group">
            <label for="featured_image">الصورة المميزة</label>
            <input type="file" name="featured_image" id="featured_image" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="images">الصور الإضافية</label>
            <input type="file" name="images[]" id="images" accept="image/*" multiple>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" checked>
                تفعيل الموقع السياحي
            </label>
        </div>
        
        <button type="submit">إرسال البيانات</button>
    </form>

    <script>
    document.getElementById('testForm').addEventListener('submit', function(e) {
        console.log('Form submitted!');
        console.log('Form data:', new FormData(this));
        
        // إظهار البيانات في console
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
    });
    </script>
</body>
</html>
