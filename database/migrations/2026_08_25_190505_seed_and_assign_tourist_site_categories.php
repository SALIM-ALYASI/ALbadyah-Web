<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * جدول tourist_site_categories كان فاضيًا بالكامل بالإنتاج (اكتُشف أثناء
     * تدقيق بيانات الموقع) رغم وجود عمود tourist_site_category_id على كل
     * موقع سياحي منشور - أي موقع من الـ83 ما كان يقدر يُصنَّف. هذا الـmigration
     * يزرع مجموعة تصنيفات واضحة (بلا تكرار، بخلاف القائمة القديمة بالتطوير
     * المحلي) ثم يربط كل موقع منشور فعليًا بتصنيفه حسب اسمه.
     */
    private array $categories = [
        'fort' => ['name_ar' => 'قلعة وحصن', 'name_en' => 'Fort & Castle', 'slug' => 'fort-castle'],
        'palace' => ['name_ar' => 'قصر وموقع ملكي', 'name_en' => 'Palace & Royal Site', 'slug' => 'palace-royal-site'],
        'museum' => ['name_ar' => 'متحف', 'name_en' => 'Museum', 'slug' => 'museum'],
        'souq' => ['name_ar' => 'سوق', 'name_en' => 'Souq / Market', 'slug' => 'souq'],
        'beach' => ['name_ar' => 'شاطئ وساحل', 'name_en' => 'Beach & Coast', 'slug' => 'beach-coast'],
        'religious' => ['name_ar' => 'مسجد ومعلم ديني', 'name_en' => 'Mosque & Religious Site', 'slug' => 'mosque-religious'],
        'heritage' => ['name_ar' => 'تراث وثقافة', 'name_en' => 'Heritage & Culture', 'slug' => 'heritage-culture'],
        'landmark' => ['name_ar' => 'معلم سياحي عام', 'name_en' => 'General Landmark', 'slug' => 'general-landmark'],
        'shopping' => ['name_ar' => 'تجاري وتسوق', 'name_en' => 'Commercial & Shopping', 'slug' => 'commercial-shopping'],
        'nature' => ['name_ar' => 'حديقة ومحمية طبيعية', 'name_en' => 'Garden & Nature Reserve', 'slug' => 'garden-nature-reserve'],
        'marina' => ['name_ar' => 'مرسى وميناء', 'name_en' => 'Marina & Port', 'slug' => 'marina-port'],
    ];

    /** name_ar الموقع => مفتاح التصنيف بالمصفوفة أعلاه */
    private array $assignments = [
        'سوق مطرح' => 'souq',
        'قلعة مطرح' => 'fort',
        'شاطئ القرم' => 'beach',
        'سد وادي ضيقة' => 'nature',
        'المتحف الوطني' => 'museum',
        'متحف بيت الزبير' => 'museum',
        'كورنيش مطرح' => 'heritage',
        'بيت البرندة' => 'museum',
        'حديقة ريام' => 'nature',
        'حصن قريات' => 'fort',
        'هوية نجم (حفرة بمة)' => 'nature',
        'بندر الخيران' => 'beach',
        'جزيرة الفحل' => 'nature',
        'شاطئ الجصة' => 'beach',
        'بندر الجصة' => 'beach',
        'السيفة' => 'beach',
        'قنتب' => 'beach',
        'يتي' => 'beach',
        'محمية جزر الديمانيات الطبيعية' => 'nature',
        'شاطئ فنس' => 'beach',
        'بيت المقحم' => 'heritage',
        'سوق السمك بمطرح' => 'souq',
        'حصن الساحل - قريات' => 'fort',
        'شاطئ السيب' => 'beach',
        'شاطئ البستان' => 'beach',
        'شاطئ قريات' => 'beach',
        'وادي العربيين' => 'nature',
        'حديقة القرم الطبيعية' => 'nature',
        'مرسى الموج' => 'marina',
        'محمية القرم الطبيعية' => 'nature',
        'بحيرات الأنصب' => 'nature',
        'قلعة الخوض' => 'fort',
        'عين غلا الحارة' => 'nature',
        'كثبان بوشر' => 'nature',
        'شاطئ ضباب' => 'beach',
        'وادي السرين' => 'nature',
        'محمية وادي السرين الطبيعية' => 'nature',
        'وادي الميح (اللجام)' => 'nature',
        'كهف غار هضاضة' => 'nature',
        'جبل سقيف' => 'nature',
        'سوق السيب' => 'souq',
        'وادي الخوض' => 'nature',
        'شاطئ العذيبة' => 'beach',
        'متحف قوات السلطان المسلحة' => 'museum',
        'شاطئ بمة' => 'beach',
        'حصن دما / السيب' => 'fort',
        'سور اللواتية' => 'heritage',
        'سور روي' => 'heritage',
        'حصن الروجة' => 'fort',
        'فلج بوشر' => 'heritage',
        'جامع السلطان قابوس الأكبر' => 'religious',
        'دار الأوبرا السلطانية مسقط' => 'landmark',
        'قلعة الميراني' => 'fort',
        'قلعة الجلالي' => 'fort',
        'قصر العلم' => 'palace',
        'متحف بوابة مسقط' => 'museum',
        'بوابة مسقط الحديثة' => 'heritage',
        'سور مسقط' => 'heritage',
        'الباب الكبير' => 'heritage',
        'متحف التاريخ الطبيعي' => 'museum',
        'أكواريوم عُمان' => 'landmark',
        'مركز عُمان للمؤتمرات والمعارض' => 'shopping',
        'عُمان أفنيوز مول' => 'shopping',
        'مسقط جراند مول' => 'shopping',
        'سيتي سنتر مسقط' => 'shopping',
        'سيتي سنتر القرم' => 'shopping',
        'مركز البهجة' => 'shopping',
        'المتحف العُماني الفرنسي' => 'museum',
        'متحف المكان والناس' => 'museum',
        'متحف العملات النقدية' => 'museum',
        'سوق السمك بالسيب' => 'souq',
        'حديقة كلبوه' => 'nature',
        'شاطئ بندر الجصة' => 'beach',
        'شاطئ السيفة' => 'beach',
        'مول عُمان' => 'shopping',
        'مول مسقط' => 'shopping',
        'حصن بيت الفلج' => 'fort',
        'متحف السيارات السلطانية' => 'museum',
        'موقع رأس الحمراء الأثري RH-5' => 'heritage',
        'القبة الفلكية - تنمية نفط عُمان' => 'landmark',
        'حديقة الميدان التجاري' => 'nature',
        'مكتبة إسلامية - مطرح' => 'religious',
        'حدائق النباتات العُمانية' => 'nature',
    ];

    public function up(): void
    {
        $categoryIds = [];
        foreach ($this->categories as $key => $cat) {
            $existing = DB::table('tourist_site_categories')->where('slug', $cat['slug'])->first();
            if ($existing) {
                $categoryIds[$key] = $existing->id;
                continue;
            }
            $categoryIds[$key] = DB::table('tourist_site_categories')->insertGetId([
                'name_ar' => $cat['name_ar'],
                'name_en' => $cat['name_en'],
                'slug' => $cat['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($this->assignments as $nameAr => $key) {
            DB::table('tourist_sites')
                ->where('name_ar', $nameAr)
                ->whereNull('tourist_site_category_id')
                ->update(['tourist_site_category_id' => $categoryIds[$key]]);
        }
    }

    public function down(): void
    {
        DB::table('tourist_sites')
            ->whereIn('name_ar', array_keys($this->assignments))
            ->update(['tourist_site_category_id' => null]);

        DB::table('tourist_site_categories')
            ->whereIn('slug', array_column($this->categories, 'slug'))
            ->delete();
    }
};
