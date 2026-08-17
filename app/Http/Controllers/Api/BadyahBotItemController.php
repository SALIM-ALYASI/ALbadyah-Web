<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSource;
use App\Models\Governorate;
use App\Models\ServiceType;
use App\Models\TouristService;
use App\Models\TouristSite;
use App\Models\TouristSiteCategory;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * نقطة استقبال واحدة لبوت البادية المستقل (تلجرام + OSM). لا علاقة لها
 * بلوحة الإدمن ولا بـ Sanctum — توثيق مستقل عبر BadyahBotApiAuth فقط.
 *
 * كل عنصر يُوافَق عليه بتلجرام (بعد إثراء AI بالبوت نفسه) يصل هنا ويُحفظ
 * approved + is_active=true فورًا - ينشر مباشرة بالموقع العام بدون خطوة
 * يدوية إضافية باللوحة. الأدمن يقدر يعدّل أو يخفي أي سجل لاحقًا من اللوحة
 * لو احتاج.
 */
class BadyahBotItemController extends Controller
{
    private const DATA_SOURCE_SLUG = 'badyah-telegram-bot-osm';

    /**
     * قراءة فقط: يرجّع العناصر اللي جاية من هذا البوت تحديدًا (collector_name)
     * عشان التأكد إنها وصلت فعليًا للقاعدة، بدون الحاجة للوحة التحكم أو phpMyAdmin.
     *
     * فلاتر اختيارية: ?type=site|service  ?status=pending|approved|rejected|needs_review
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $status = $request->query('status');
        $limit = min((int) $request->query('limit', 100), 200);

        $columns = [
            'id', 'name_ar', 'name_en', 'wilayat_id', 'governorate_id',
            'verification_status', 'is_active', 'external_id', 'created_at',
        ];

        $sites = collect();
        if ($type !== 'service') {
            $q = TouristSite::with(['wilayat:id,name_ar', 'governorate:id,name_ar'])
                ->where('collector_name', 'AlBadyahTelegramBot');
            if ($status) {
                $q->where('verification_status', $status);
            }
            $sites = $q->orderByDesc('id')->limit($limit)->get($columns)->map(fn ($s) => [
                'type' => 'site',
                'id' => $s->id,
                'name_ar' => $s->name_ar,
                'name_en' => $s->name_en,
                'wilayat' => $s->wilayat?->name_ar,
                'governorate' => $s->governorate?->name_ar,
                'verification_status' => $s->verification_status,
                'is_active' => (bool) $s->is_active,
                'external_id' => $s->external_id,
                'created_at' => $s->created_at,
            ]);
        }

        $services = collect();
        if ($type !== 'site') {
            $q = TouristService::with(['wilayat:id,name_ar', 'governorate:id,name_ar'])
                ->where('collector_name', 'AlBadyahTelegramBot');
            if ($status) {
                $q->where('verification_status', $status);
            }
            $services = $q->orderByDesc('id')->limit($limit)->get($columns)->map(fn ($s) => [
                'type' => 'service',
                'id' => $s->id,
                'name_ar' => $s->name_ar,
                'name_en' => $s->name_en,
                'wilayat' => $s->wilayat?->name_ar,
                'governorate' => $s->governorate?->name_ar,
                'verification_status' => $s->verification_status,
                'is_active' => (bool) $s->is_active,
                'external_id' => $s->external_id,
                'created_at' => $s->created_at,
            ]);
        }

        return response()->json([
            'success' => true,
            'counts' => ['sites' => $sites->count(), 'services' => $services->count()],
            'sites' => $sites->values(),
            'services' => $services->values(),
        ]);
    }

    /**
     * قراءة فقط: قائمة التصنيفات الحقيقية (تصنيفات المواقع + أنواع الخدمات)
     * عشان البوت (أو أي إثراء بالذكاء الاصطناعي قبل الإرسال) يختار من تصنيفات
     * موجودة فعليًا بدل ما يخترع تصنيف جديد.
     */
    public function categories()
    {
        return response()->json([
            'success' => true,
            'site_categories' => TouristSiteCategory::orderBy('name_ar')->get(['id', 'name_ar', 'name_en']),
            'service_types' => ServiceType::orderBy('name_ar')->get(['id', 'name_ar', 'name_en']),
        ]);
    }

    /**
     * قراءة فقط: كل محافظات عُمان مع ولاياتها، عشان البوت يبني أزرار اختيار
     * بدل ما الأدمن يكتب اسم المحافظة/الولاية يدويًا (يمنع أخطاء المطابقة).
     * تُجلب مرة وحدة وتُخزَّن بذاكرة البوت (نفس نمط /categories).
     */
    public function areas()
    {
        $governorates = Governorate::with(['wilayats' => function ($q) {
            $q->orderBy('name_ar')->select('id', 'name_ar', 'name_en', 'governorate_id');
        }])->orderBy('name_ar')->get(['id', 'name_ar', 'name_en']);

        return response()->json([
            'success' => true,
            'governorates' => $governorates,
        ]);
    }

    /**
     * قراءة فقط: إحصائية ولاية محددة (مواقع/خدمات محفوظة + بانتظار المراجعة)
     * تُعرض للأدمن بتلجرام بعد اختيار الولاية بالزر.
     */
    public function wilayatStats(int $wilayatId)
    {
        // ملاحظة: لا نعتمد على implicit route-model-binding هنا لأن
        // middleware group 'api' بهذا المشروع (bootstrap/app.php) أُعيد
        // تعريفه بدون SubstituteBindings::class - نفس نمط باقي هذا الملف
        // (WilayatApiController يستخدم {identifier} + بحث يدوي لنفس السبب).
        $wilayat = Wilayat::find($wilayatId);
        if (!$wilayat) {
            return response()->json(['success' => false, 'message' => 'ولاية غير موجودة.'], 404);
        }

        $pendingStatuses = ['pending', 'needs_review'];

        $sitesTotal = TouristSite::where('wilayat_id', $wilayat->id)->count();
        $sitesPending = TouristSite::where('wilayat_id', $wilayat->id)
            ->whereIn('verification_status', $pendingStatuses)->count();
        $servicesTotal = TouristService::where('wilayat_id', $wilayat->id)->count();
        $servicesPending = TouristService::where('wilayat_id', $wilayat->id)
            ->whereIn('verification_status', $pendingStatuses)->count();

        return response()->json([
            'success' => true,
            'wilayat_id' => $wilayat->id,
            'sites_total' => $sitesTotal,
            'sites_pending' => $sitesPending,
            'services_total' => $servicesTotal,
            'services_pending' => $servicesPending,
            'pending_total' => $sitesPending + $servicesPending,
        ]);
    }

    /**
     * قراءة فقط: كل عناصر ولاية محددة، من أي مصدر (مو بس هذا البوت) —
     * يخدم غرضين: فحص التكرار قبل عرض نتيجة جديدة على الأدمن، وشاشة
     * "عرض البيانات الموجودة" بتلجرام. فلتر اختياري ?type=site|service.
     */
    public function wilayatItems(int $wilayatId, Request $request)
    {
        $wilayat = Wilayat::find($wilayatId);
        if (!$wilayat) {
            return response()->json(['success' => false, 'message' => 'ولاية غير موجودة.'], 404);
        }

        $type = $request->query('type');
        $baseColumns = [
            'id', 'name_ar', 'name_en', 'latitude', 'longitude', 'website_url',
            'source_url', 'external_id', 'verification_status', 'is_active', 'collector_name',
        ];

        // TouristSite ما فيه عمود phone أصلاً (بعكس TouristService) - نضيفه
        // بس لاستعلام الخدمات، ونطبّع اسم الحقل لـ website/phone بالرد
        // (بدل website_url) عشان يطابق شكل حقول map_candidates بجهة البوت.
        $sites = $type !== 'service'
            ? TouristSite::where('wilayat_id', $wilayat->id)->get($baseColumns)->values()
                ->map(fn ($s) => $this->normalizeItemFields($s, null))
            : collect();

        $services = $type !== 'site'
            ? TouristService::where('wilayat_id', $wilayat->id)->get([...$baseColumns, 'phone'])->values()
                ->map(fn ($s) => $this->normalizeItemFields($s, $s->phone))
            : collect();

        return response()->json([
            'success' => true,
            'wilayat_id' => $wilayat->id,
            'sites' => $sites,
            'services' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => ['required', 'in:site,service'],
            'external_id' => ['required', 'string', 'max:190'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'subtype' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'wilayat_id' => ['nullable', 'integer', 'exists:wilayats,id'],
            'wilayat_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:60'],
            'website' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'tourist_site_category_id' => ['nullable', 'integer', 'exists:tourist_site_categories,id'],
            'service_type_id' => ['nullable', 'integer', 'exists:service_types,id'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('name_ar') && !$request->filled('name_en')) {
                $validator->errors()->add('name_ar', 'لازم اسم عربي أو إنجليزي على الأقل.');
            }
            if (!$request->filled('wilayat_id') && !$request->filled('wilayat_name')) {
                $validator->errors()->add('wilayat_id', 'لازم wilayat_id أو wilayat_name على الأقل.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الطلب غير صالحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // المصدر الأساسي الآن: wilayat_id (البوت يختاره من زر حقيقي، بدون
        // كتابة اسم يدويًا). wilayat_name يبقى فقط لتوافق خلفي (اختبار
        // يدوي بـ curl أو بوت قديم لم يُحدَّث بعد).
        if (!empty($data['wilayat_id'])) {
            $wilayat = Wilayat::find($data['wilayat_id']);
        } else {
            // البوت أحيانًا يجيب اسم الولاية من Nominatim ببادئة "ولاية"/"محافظة"
            // بينما المخزّن بقاعدة البيانات بدون بادئة، فنشيلها قبل المطابقة.
            $wilayatName = trim(preg_replace('/^(محافظة|ولاية)\s+/u', '', trim($data['wilayat_name'])));
            $wilayat = Wilayat::where('name_ar', 'like', "%{$wilayatName}%")->first();
        }

        if (!$wilayat) {
            return response()->json([
                'success' => false,
                'message' => 'ولاية غير معروفة: '.($data['wilayat_id'] ?? $data['wilayat_name'] ?? '—'),
            ], 422);
        }

        $dataSource = DataSource::firstOrCreate(
            ['slug' => self::DATA_SOURCE_SLUG],
            [
                'name' => 'بوت البادية - تلجرام (OpenStreetMap)',
                'type' => 'project_owned',
                'base_url' => 'https://www.openstreetmap.org',
                'trust_level' => 3,
                'is_active' => true,
                'notes' => 'عناصر جُمعت من OpenStreetMap واعتُمدت يدويًا عبر بوت تلجرام مستقل، ثم أُرسلت لهذا الـ API.',
            ]
        );

        $externalId = "osm-{$data['external_id']}";

        // منع التكرار: نفس external_id لنفس المصدر = نفس العنصر بالضبط
        $existingSite = TouristSite::where('data_source_id', $dataSource->id)
            ->where('external_id', $externalId)->first();
        $existingService = TouristService::where('data_source_id', $dataSource->id)
            ->where('external_id', $externalId)->first();

        if ($existingSite || $existingService) {
            $existing = $existingSite ?: $existingService;
            return response()->json([
                'success' => true,
                'duplicate' => true,
                'message' => 'العنصر محفوظ مسبقًا، ما تكرر.',
                'data' => ['id' => $existing->id, 'type' => $existingSite ? 'site' : 'service'],
            ]);
        }

        $nameAr = $data['name_ar'] ?? $data['name_en'];
        $nameEn = $data['name_en'] ?? $data['name_ar'];
        $slugSource = $data['name_en'] ?? $data['name_ar'];

        // منع تكرار أوسع: نفس المكان الحقيقي أحيانًا يوصل بمعرّف OSM مختلف
        // (عنصر ثاني بنفس الموقع)، أو أصلًا موجود يدويًا من قبل البوت بلا
        // external_id. نطابق بالاسم + نفس الولاية، ونتأكد بالمسافة إذا
        // كان عند الطرفين إحداثيات (نتساهل لو أحدهما بلا إحداثيات، زي
        // السجلات القديمة المدخلة يدويًا).
        $modelClass = $data['category'] === 'service' ? TouristService::class : TouristSite::class;
        $likelyDuplicate = $this->findLikelyDuplicate(
            $modelClass, $nameAr, $nameEn, $wilayat->id, (float) $data['latitude'], (float) $data['longitude']
        );

        if ($likelyDuplicate) {
            return response()->json([
                'success' => true,
                'duplicate' => true,
                'message' => 'موجود مسبقًا بنفس الاسم والولاية، ما تكرر.',
                'data' => ['id' => $likelyDuplicate->id, 'type' => $data['category']],
            ]);
        }

        $shared = [
            'name_ar' => $nameAr,
            'name_en' => $nameEn,
            'website_url' => $data['website'] ?? null,
            'governorate_id' => $wilayat->governorate_id,
            'wilayat_id' => $wilayat->id,
            'data_source_id' => $dataSource->id,
            'source_url' => $data['source_url'] ?? null,
            'source_name' => 'OpenStreetMap',
            'source_type' => 'osm',
            'external_id' => $externalId,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'coordinates_source' => 'osm',
            // موافقة تلجرام = نشر فوري مباشرة على الموقع العام، بدون خطوة يدوية إضافية باللوحة
            'verification_status' => 'approved',
            'is_active' => true,
            'ai_generated' => true,
            'name_ar_source' => isset($data['name_ar']) ? 'untranslated' : 'ai_translation',
            'collector_name' => 'AlBadyahTelegramBot',
            'collected_at' => now(),
        ];

        // ساعات العمل ما لها عمود مستقل بقاعدة البيانات حاليًا؛ تُلحق بنهاية
        // الوصف حتى ما تضيع المعلومة، بدل تعديل الشيما لحقل واحد نادر الحاجة.
        $appendHours = fn (?string $desc) => $desc && !empty($data['opening_hours'])
            ? rtrim($desc)."\n\nساعات العمل: {$data['opening_hours']}"
            : $desc;

        if ($data['category'] === 'service') {
            $service = TouristService::create($shared + [
                'slug' => Str::slug($slugSource).'-'.Str::random(6),
                'phone' => $data['phone'] ?? null,
                'service_type_id' => $data['service_type_id'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'description_ar' => $appendHours($data['description_ar'] ?? null),
                'description_en' => $data['description_en'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'duplicate' => false,
                'message' => 'تم حفظ الخدمة ونشرها مباشرة بالموقع.',
                'data' => ['id' => $service->id, 'type' => 'service'],
            ], 201);
        }

        $site = TouristSite::create($shared + [
            'slug' => Str::slug($slugSource).'-'.Str::random(6),
            // description_ar/description_en غير قابلة لـ null بقاعدة البيانات
            'description_ar' => $appendHours($data['description_ar'] ?? '') ?? '',
            'description_en' => $data['description_en'] ?? '',
            'tourist_site_category_id' => $data['tourist_site_category_id'] ?? null,
        ]);

        // الموقع يستخدم جدول صور منفصل (tourist_images)، مو عمود مباشر
        if (!empty($data['image_url'])) {
            $site->images()->create([
                'image_url' => $data['image_url'],
                'alt_text_ar' => $site->name_ar,
                'is_featured' => true,
                'sort_order' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'duplicate' => false,
            'message' => 'تم حفظ الموقع ونشره مباشرة بالموقع.',
            'data' => ['id' => $site->id, 'type' => 'site'],
        ], 201);
    }

    /**
     * يبحث عن سجل موجود مسبقًا (أي مصدر، مو بس البوت) يبدو إنه نفس المكان:
     * نفس الاسم (عربي أو إنجليزي، تطابق تام بعد التنظيف) + نفس الولاية.
     * لو عند الطرفين إحداثيات، لازم كمان يكونان قريبين (≤500م) حتى ما
     * نخلط بين مكانين مختلفين بنفس الاسم العام. لو أحدهما بلا إحداثيات
     * (زي السجلات القديمة المُدخلة يدويًا)، الاسم + الولاية كافي.
     */
    private function findLikelyDuplicate(string $modelClass, ?string $nameAr, ?string $nameEn, int $wilayatId, float $lat, float $lng)
    {
        $nameArNorm = $nameAr ? mb_strtolower(trim($nameAr)) : null;
        $nameEnNorm = $nameEn ? mb_strtolower(trim($nameEn)) : null;

        if (!$nameArNorm && !$nameEnNorm) {
            return null;
        }

        $candidates = $modelClass::where('wilayat_id', $wilayatId)
            ->where(function ($q) use ($nameArNorm, $nameEnNorm) {
                if ($nameArNorm) {
                    $q->orWhereRaw('LOWER(TRIM(name_ar)) = ?', [$nameArNorm]);
                }
                if ($nameEnNorm) {
                    $q->orWhereRaw('LOWER(TRIM(name_en)) = ?', [$nameEnNorm]);
                }
            })
            ->get(['id', 'latitude', 'longitude']);

        foreach ($candidates as $candidate) {
            if ($candidate->latitude === null || $candidate->longitude === null) {
                return $candidate; // بلا إحداثيات نقارن فيها - الاسم + الولاية كافي
            }
            $distance = $this->haversineMeters($lat, $lng, (float) $candidate->latitude, (float) $candidate->longitude);
            if ($distance <= 500) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * يحوّل صف TouristSite/TouristService لمصفوفة بأسماء حقول موحّدة
     * (website بدل website_url، phone صريح) عشان تطابق شكل map_candidates
     * بجانب البوت لفحص التكرار (رقم هاتف/موقع إلكتروني).
     */
    private function normalizeItemFields($model, ?string $phone): array
    {
        return [
            'id' => $model->id,
            'name_ar' => $model->name_ar,
            'name_en' => $model->name_en,
            'latitude' => $model->latitude,
            'longitude' => $model->longitude,
            'website' => $model->website_url,
            'phone' => $phone,
            'source_url' => $model->source_url,
            'external_id' => $model->external_id,
            'verification_status' => $model->verification_status,
            'is_active' => (bool) $model->is_active,
            'collector_name' => $model->collector_name,
        ];
    }

    private function haversineMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
