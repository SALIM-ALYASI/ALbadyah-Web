<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSource;
use App\Models\TouristService;
use App\Models\TouristSite;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * نقطة استقبال واحدة لبوت البادية المستقل (تلجرام + OSM). لا علاقة لها
 * بلوحة الإدمن ولا بـ Sanctum — توثيق مستقل عبر BadyahBotApiAuth فقط.
 *
 * كل عنصر يُوافَق عليه بتلجرام يصل هنا ويُحفظ pending + is_active=false
 * دائمًا. لا يوجد أي مسار هنا ينشر عنصرًا مباشرة؛ الأدمن يكمل البيانات
 * وينشرها يدويًا من لوحة التحكم.
 */
class BadyahBotItemController extends Controller
{
    private const DATA_SOURCE_SLUG = 'badyah-telegram-bot-osm';

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
            'wilayat_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:60'],
            'website' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('name_ar') && !$request->filled('name_en')) {
                $validator->errors()->add('name_ar', 'لازم اسم عربي أو إنجليزي على الأقل.');
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

        $wilayat = Wilayat::where('name_ar', 'like', "%{$data['wilayat_name']}%")->first();

        if (!$wilayat) {
            return response()->json([
                'success' => false,
                'message' => "ولاية غير معروفة: {$data['wilayat_name']}",
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
            // pending + غير نشط دائمًا: الأدمن يكمل الصورة/الوصف/التصنيف وينشر يدويًا
            'verification_status' => 'pending',
            'is_active' => false,
            'ai_generated' => false,
            'name_ar_source' => isset($data['name_ar']) ? 'untranslated' : 'ai_translation',
            'collector_name' => 'AlBadyahTelegramBot',
            'collected_at' => now(),
        ];

        if ($data['category'] === 'service') {
            $service = TouristService::create($shared + [
                'slug' => Str::slug($slugSource).'-'.Str::random(6),
                'phone' => $data['phone'] ?? null,
                'service_type_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'duplicate' => false,
                'message' => 'تم حفظ الخدمة كـ pending، بانتظار استكمال البيانات من لوحة التحكم.',
                'data' => ['id' => $service->id, 'type' => 'service'],
            ], 201);
        }

        $site = TouristSite::create($shared + [
            'slug' => Str::slug($slugSource).'-'.Str::random(6),
            // description_ar/description_en غير قابلة لـ null بقاعدة البيانات
            'description_ar' => '',
            'description_en' => '',
            'tourist_site_category_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'duplicate' => false,
            'message' => 'تم حفظ الموقع كـ pending، بانتظار استكمال البيانات من لوحة التحكم.',
            'data' => ['id' => $site->id, 'type' => 'site'],
        ], 201);
    }
}
