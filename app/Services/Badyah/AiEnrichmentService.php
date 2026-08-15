<?php

namespace App\Services\Badyah;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * تخصيب البيانات بالذكاء الاصطناعي (Groq) — مفتاح مستقل تمامًا عن أي
 * مشروع آخر (config('services.groq')).
 *
 * دور الذكاء الاصطناعي هنا محصور بدقة فيما يلي:
 *   1. مطابقة (وليس توليد) الاسم العربي الرسمي إن وُجد في نسخة عربية من نفس
 *      المصدر — النص يُستخدم كما هو حرفيًا، الذكاء الاصطناعي فقط يحدد أي
 *      عنصر عربي يطابق نفس المكان الإنجليزي (مهمة مطابقة كيانات، لا اختلاق).
 *   2. ترجمة أمينة فقط عند عدم وجود نص عربي رسمي مطابق (لا توليد حقائق).
 *   3. تصنيف الموقع ضمن قائمة تصنيفات مضبوطة مسبقًا فقط.
 *   4. تقدير ai_confidence (ثقة الذكاء الاصطناعي في عمله هو فقط، وليس ثقة
 *      المصدر — تلك تُحسب من data_source.trust_level بمعزل عن أي نموذج AI).
 *
 * أي شيء غير متأكد منه النموذج يُعاد null + يُذكر في needs_review_fields،
 * ويُمنع صراحة في الـ prompt اختلاق ساعات عمل/أسعار/تاريخ غير مذكور.
 */
class AiEnrichmentService
{
    /**
     * يحاول إيجاد الاسم/الوصف العربي الرسمي لعنصر إنجليزي من قائمة مرشحين
     * عرب (مثلًا بطاقات نفس المصدر بنسخته العربية). لا يُترجم — فقط يطابق
     * الهوية، ويرجع النص العربي كما هو حرفيًا من المصدر عند التطابق.
     *
     * @param array{name_en: string, description_en: ?string} $englishItem
     * @param array<int, array{name_ar: string, description_ar: ?string, source_url: string}> $arabicCandidates
     * @return array{name_ar: string, description_ar: ?string, source_url: string}|null
     */
    public function matchOfficialArabicSource(array $englishItem, array $arabicCandidates): ?array
    {
        if (empty($arabicCandidates)) {
            return null;
        }

        $systemPrompt = <<<PROMPT
You match tourism place records across two languages from the SAME official website.
You are given one English place (name + description) and a numbered list of Arabic candidate
places from the same site's Arabic version. Decide if any candidate refers to the EXACT SAME
physical place as the English one (not just a similar category — the same specific place).

Rules:
- Do NOT translate or modify any text. Only decide which index (if any) matches.
- Only report a match if you are highly confident (same landmark, not just same governorate).
- Output STRICT JSON ONLY: {"match_index": number|null, "confidence": number}
  match_index is the 0-based index into the candidates list, or null if no confident match.
PROMPT;

        $userPrompt = json_encode([
            'english_place' => $englishItem,
            'arabic_candidates' => array_map(
                fn ($c, $i) => ['index' => $i, 'name_ar' => $c['name_ar'], 'description_ar' => $c['description_ar']],
                $arabicCandidates,
                array_keys($arabicCandidates)
            ),
        ], JSON_UNESCAPED_UNICODE);

        $parsed = $this->callGroqJson($systemPrompt, $userPrompt);

        $index = $parsed['match_index'] ?? null;
        $confidence = (float) ($parsed['confidence'] ?? 0);

        if ($index === null || !isset($arabicCandidates[$index]) || $confidence < 80) {
            return null;
        }

        return $arabicCandidates[$index];
    }

    /**
     * @param array{name_en: string, description_en: ?string, source_url: string} $rawItem
     * @param array{name_ar: string, description_ar: ?string}|null $officialArabic نص عربي رسمي جاهز (له أولوية مطلقة على الترجمة)
     */
    public function enrichTouristSite(array $rawItem, ?array $officialArabic = null): array
    {
        $categories = collect(config('badyah.site_categories'))
            ->map(fn ($c, $slug) => "{$slug} ({$c['name_en']} / {$c['name_ar']})")
            ->implode(', ');

        if ($officialArabic) {
            return $this->classifyOnly($rawItem, $officialArabic, $categories);
        }

        return $this->translateAndClassify($rawItem, $categories);
    }

    private function classifyOnly(array $rawItem, array $officialArabic, string $categories): array
    {
        $systemPrompt = <<<PROMPT
You classify Omani tourism places into a fixed category taxonomy. You are given facts already
verified from an official source — do NOT alter or re-translate them, only classify.

Choose exactly one category slug from this fixed list only (never invent a new one):
{$categories}
If none fits confidently, return "landmark".

Output STRICT JSON ONLY: {"category_slug": string, "confidence": number, "needs_review_fields": string[]}
confidence: 0-100, how confident you are in the category choice only.
needs_review_fields: field names you are unsure about regarding classification only (usually just "category" or empty).
PROMPT;

        $userPrompt = json_encode([
            'name_en' => $rawItem['name_en'],
            'description_en' => $rawItem['description_en'],
            'name_ar' => $officialArabic['name_ar'],
            'description_ar' => $officialArabic['description_ar'],
        ], JSON_UNESCAPED_UNICODE);

        $parsed = $this->callGroqJson($systemPrompt, $userPrompt);

        return [
            'name_ar' => $officialArabic['name_ar'],
            'description_ar' => $officialArabic['description_ar'],
            'description_en' => $rawItem['description_en'],
            'name_ar_source' => 'official', // من المصدر الرسمي، مو ترجمة AI
            'category_slug' => $this->sanitizeCategorySlug($parsed['category_slug'] ?? null),
            'ai_confidence' => is_numeric($parsed['confidence'] ?? null) ? (float) $parsed['confidence'] : 50.0,
            'needs_review_fields' => array_values(array_filter((array) ($parsed['needs_review_fields'] ?? []))),
        ];
    }

    private function translateAndClassify(array $rawItem, string $categories): array
    {
        $systemPrompt = <<<PROMPT
You are a strict data-translation and classification assistant for an Omani tourism database.

RULES (must follow exactly):
- You are given facts about ONE tourist site, sourced from an official tourism website.
- No official Arabic source text was found for this place, so you must translate the given
  English name and description into Arabic FAITHFULLY. Do not add any fact, number, date, price,
  opening hour, or historical claim that is not present in the input text.
- Choose exactly one category slug from this fixed list only (never invent a new one):
  {$categories}
  If none fits confidently, return "landmark".
- ai_confidence: 0-100, your confidence that the category AND translation are both accurate
  representations of the given input (not confidence about the place itself).
- needs_review_fields: array of field names you are not fully certain about (e.g. "category",
  "name_ar", "description_ar"). Empty array if fully confident.
- Output STRICT JSON ONLY, no prose, matching exactly this shape:
  {"name_ar": string, "description_ar": string, "description_en": string, "category_slug": string,
   "ai_confidence": number, "needs_review_fields": string[]}
PROMPT;

        $userPrompt = json_encode([
            'name_en' => $rawItem['name_en'],
            'description_en' => $rawItem['description_en'],
            'source_url' => $rawItem['source_url'],
        ], JSON_UNESCAPED_UNICODE);

        $parsed = $this->callGroqJson($systemPrompt, $userPrompt);

        if (!isset($parsed['name_ar'])) {
            throw new \RuntimeException('استجابة Groq غير صالحة: name_ar غير موجود.');
        }

        $needsReview = array_values(array_filter((array) ($parsed['needs_review_fields'] ?? [])));
        // ترجمة آلية بدون مصدر عربي رسمي: نُعلِّم الاسم/الوصف للمراجعة دائمًا احتياطًا
        $needsReview = array_values(array_unique([...$needsReview, 'name_ar', 'description_ar']));

        return [
            'name_ar' => $parsed['name_ar'],
            'description_ar' => $parsed['description_ar'] ?? null,
            'description_en' => $parsed['description_en'] ?? $rawItem['description_en'],
            'name_ar_source' => 'ai_translation',
            'category_slug' => $this->sanitizeCategorySlug($parsed['category_slug'] ?? null),
            'ai_confidence' => is_numeric($parsed['ai_confidence'] ?? null) ? (float) $parsed['ai_confidence'] : 50.0,
            'needs_review_fields' => $needsReview,
        ];
    }

    private function sanitizeCategorySlug(?string $slug): string
    {
        $allowedSlugs = array_keys(config('badyah.site_categories'));
        return in_array($slug, $allowedSlugs, true) ? $slug : 'landmark';
    }

    private function callGroqJson(string $systemPrompt, string $userPrompt): array
    {
        $response = Http::withToken(config('services.groq.api_key'))
            ->baseUrl(config('services.groq.base_url'))
            ->timeout(30)
            ->retry(2, 800)
            ->post('/chat/completions', [
                'model' => config('services.groq.model'),
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Badyah AiEnrichmentService: Groq request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('فشل طلب Groq: HTTP '.$response->status());
        }

        $content = $response->json('choices.0.message.content');
        $parsed = json_decode((string) $content, true);

        if (!is_array($parsed)) {
            throw new \RuntimeException('استجابة Groq غير صالحة (JSON غير متوقع): '.$content);
        }

        return $parsed;
    }
}
