<?php

return [

    /*
    |--------------------------------------------------------------------------
    | مصادر جمع بيانات محرك البادية (Multi-Source)
    |--------------------------------------------------------------------------
    |
    | كل مصدر مفعّل/معطّل من هنا فقط. إضافة مصدر جديد لاحقًا = إضافة كلاس
    | Collector جديد يطبّق SourceCollectorInterface + سطر هنا، بدون أي
    | تعديل على باقي البوت.
    |
    */
    'sources' => [
        'experience_oman' => [
            'enabled' => true,
            'trust_level' => 5, // ثقة عالية جدًا (حسب ترتيب المصادر المعتمد)
            'base_url' => 'https://experienceoman.om',
        ],

        'mht_dataset' => [
            // معطّل حتى نحصل على رابط تحميل فعلي من وزارة التراث والسياحة
            'enabled' => false,
            'trust_level' => 5,
            'file_path' => env('MHT_DATASET_XLSX_PATH'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | اسم المحافظة بالعربي مقابل الـ slug الإنجليزي (لمطابقة صفحة "وجهات" العربية)
    |--------------------------------------------------------------------------
    */
    'governorate_ar_labels' => [
        'muscat' => 'محافظة مسقط',
    ],

    /*
    |--------------------------------------------------------------------------
    | بريد استلام دفعات مراجعة البادية (Gmail review cycle)
    |--------------------------------------------------------------------------
    */
    'review_email' => env('BADYAH_REVIEW_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | تصنيفات المواقع السياحية المعتمدة (Taxonomy سياحي بالغرض، مو بنوع المبنى)
    |--------------------------------------------------------------------------
    |
    | مراجَعة يدويًا بعد أول اختبار: بدل تصنيف تقني ضيق (fort/museum/theatre
    | منفصلة عن بعضها بلا معنى سياحي)، التصنيف الآن حسب الغرض السياحي من
    | زيارة المكان. الذكاء الاصطناعي يختار من هذه القائمة فقط ولا يخترع
    | تصنيفات جديدة. commercial_shopping مخصص للمولات ونحوها (تظهر في
    | القاعدة لكن is_tourism_candidate=false لها، لا تُحذف بصمت).
    |
    */
    'site_categories' => [
        'heritage_culture' => ['name_ar' => 'تراث وثقافة', 'name_en' => 'Heritage & Culture'],
        'museum' => ['name_ar' => 'متحف', 'name_en' => 'Museum'],
        'fort_castle' => ['name_ar' => 'قلعة وحصن', 'name_en' => 'Fort & Castle'],
        'mosque_religious' => ['name_ar' => 'مسجد ومعلم ديني', 'name_en' => 'Mosque & Religious Site'],
        'market_souq' => ['name_ar' => 'سوق', 'name_en' => 'Market / Souq'],
        'beach_coast' => ['name_ar' => 'شاطئ وساحل', 'name_en' => 'Beach & Coast'],
        'nature_outdoor' => ['name_ar' => 'طبيعة وأنشطة خارجية', 'name_en' => 'Nature & Outdoor'],
        'family_entertainment' => ['name_ar' => 'عائلي وترفيه', 'name_en' => 'Family & Entertainment'],
        'activity_adventure' => ['name_ar' => 'نشاط ومغامرة', 'name_en' => 'Activity & Adventure'],
        'commercial_shopping' => ['name_ar' => 'تجاري وتسوق', 'name_en' => 'Commercial & Shopping'],
        'palace_royal' => ['name_ar' => 'قصر وموقع ملكي', 'name_en' => 'Palace & Royal Landmark'],
        // fallback فقط للأماكن التي لا يوجد لها تصنيف أدق ضمن القائمة أعلاه
        'landmark' => ['name_ar' => 'معلم سياحي عام', 'name_en' => 'General Landmark'],
    ],
];
