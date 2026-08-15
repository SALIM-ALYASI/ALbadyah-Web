<?php

namespace App\Services\Badyah\Collectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * يجمع من experienceoman.om الرسمي (ثقة عالية جدًا حسب ترتيب المصادر).
 *
 * يقرأ النسخة الإنجليزية كمصدر أساسي (اسم/وصف/رابط/إحداثيات لكل موقع)،
 * ويقرأ اختياريًا قائمة "وجهات" العربية لنفس المحافظة كمرشحين للاسم
 * العربي الرسمي (المطابقة بينهما تتم بمعرفة الذكاء الاصطناعي في طبقة
 * AiEnrichmentService::matchOfficialArabicSource — هذا الكلاس لا يخمّن
 * التطابق بنفسه، فقط يجمع المرشحين).
 *
 * لا يخترع أي حقل غير موجود فعليًا في الصفحة المصدر. أي عنصر تجاري
 * (مول مثلًا) لا يُحذف بصمت — يُجمع ويُعلَّم is_tourism_candidate=false.
 */
class ExperienceOmanCollector implements SourceCollectorInterface
{
    public const VERSION = '1.1.0';

    /** أنماط تدل على أن العنصر تجاري/تسوق وليس موقعًا سياحيًا بالمعنى المقصود */
    private const COMMERCIAL_NAME_PATTERNS = ['/\bmall\b/i'];

    public function key(): string
    {
        return 'experience_oman';
    }

    public function isEnabled(): bool
    {
        return (bool) config('badyah.sources.experience_oman.enabled');
    }

    public function collect(array $context = []): array
    {
        if (!$this->isEnabled()) {
            throw new \RuntimeException('مصدر experience_oman معطّل حاليًا في config/badyah.php.');
        }

        $governorateSlug = $context['governorate_slug'] ?? 'muscat';
        $limit = $context['limit'] ?? 10;

        $baseUrl = rtrim(config('badyah.sources.experience_oman.base_url'), '/');
        $listingUrl = "{$baseUrl}/en/destinations/{$governorateSlug}";

        $listingHtml = $this->fetch($listingUrl);
        $candidates = $this->parseListing($listingHtml);

        $now = now();
        $items = [];
        foreach ($candidates as $candidate) {
            $candidate['source_url'] = $this->toAbsoluteUrl($candidate['source_url'], $baseUrl);
            $detailHtml = $this->fetch($candidate['source_url']);
            $coords = $this->extractCoordinates($detailHtml);
            $isCommercial = $this->isCommercial($candidate['name_en']);

            $items[] = [
                'name_en' => $candidate['name_en'],
                'description_en' => $candidate['description_en'],
                'source_url' => $candidate['source_url'],
                'source_name' => 'Experience Oman',
                'source_type' => 'official_tourism_authority',
                'governorate_slug' => $governorateSlug,
                'latitude' => $coords['latitude'] ?? null,
                'longitude' => $coords['longitude'] ?? null,
                'coordinates_source' => $coords['latitude'] !== null
                    ? 'OpenStreetMap embed on Experience Oman detail page ('.$candidate['source_url'].')'
                    : null,
                'is_tourism_candidate' => !$isCommercial,
                'excluded_reason' => $isCommercial ? 'Shopping mall — commercial venue, not a tourism site by category' : null,
                'preset_category_slug' => $isCommercial ? 'commercial_shopping' : null,
                'collected_at' => $now,
                'source_last_checked_at' => $now,
                'collector_name' => static::class,
                'collector_version' => self::VERSION,
            ];

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * يجمع مرشحين للاسم/الوصف العربي الرسمي من صفحة "وجهات" العربية،
     * مفلترة بنفس المحافظة. لا يُترجم شيئًا — نص خام كما هو من المصدر.
     *
     * @return array<int, array{name_ar: string, description_ar: ?string, source_url: string}>
     */
    public function collectArabicCandidates(string $governorateSlug): array
    {
        $arabicLabel = config("badyah.governorate_ar_labels.{$governorateSlug}");
        if (!$arabicLabel) {
            return [];
        }

        $baseUrl = rtrim(config('badyah.sources.experience_oman.base_url'), '/');
        // الصفحة العربية لقائمة الوجهات (اسمها بالعربي في الرابط نفسه على الموقع الرسمي)
        $listingUrl = "{$baseUrl}/ar/%D9%88%D8%AC%D9%87%D8%A7%D8%AA";

        $html = $this->fetch($listingUrl);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);
        $xpath = new \DOMXPath($dom);

        $discoverLinks = $xpath->query("//a[contains(text(), 'اكتشف المزيد')]");

        $candidates = [];
        foreach ($discoverLinks as $link) {
            $card = $link->parentNode?->parentNode;
            if (!$card) {
                continue;
            }

            $cardText = preg_replace('/\s+/u', ' ', trim($card->textContent));
            if (!str_contains($cardText, $arabicLabel)) {
                continue;
            }

            $href = $link->getAttribute('href');
            // بنية البطاقة: [شارة اختيارية] الاسم [المحافظة] الوصف "اكتشف المزيد"
            $withoutBadgeAndCta = trim(str_replace(['وجهات موصى بها', 'اكتشف المزيد'], '', $cardText));
            $withoutGovernorate = trim(str_replace($arabicLabel, '|', $withoutBadgeAndCta));
            [$name, $description] = array_pad(explode('|', $withoutGovernorate, 2), 2, null);

            $name = trim((string) $name);
            $description = $description !== null ? trim($description) : null;

            if (!$name || !$href) {
                continue;
            }

            $candidates[] = [
                'name_ar' => $name,
                'description_ar' => $description ?: null,
                'source_url' => $this->toAbsoluteUrl($href, $baseUrl),
            ];
        }

        return $candidates;
    }

    private function fetch(string $url): string
    {
        $response = Http::withHeaders(['User-Agent' => 'BadyahBot/1.0 (+al-badyah.com; tourism data collector)'])
            ->timeout(20)
            ->retry(2, 500)
            ->get($url);

        if (!$response->successful()) {
            Log::warning('Badyah ExperienceOmanCollector: fetch failed', ['url' => $url, 'status' => $response->status()]);
            throw new \RuntimeException("تعذّر جلب الصفحة: {$url} (HTTP {$response->status()})");
        }

        return $response->body();
    }

    /**
     * يعتمد على بنية DOM الفعلية لبطاقات experienceoman.om:
     * div.card-body > h5.card-title (الاسم) + p.card-text (وصف قصير) + a[href] (رابط التفاصيل)
     *
     * @return array<int, array{name_en: string, description_en: ?string, source_url: string}>
     */
    private function parseListing(string $html): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);

        $xpath = new \DOMXPath($dom);
        $cards = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' card-body ')]");

        $items = [];
        foreach ($cards as $card) {
            $titleNodes = $xpath->query(".//h5[contains(@class,'card-title')]", $card);
            $descNodes = $xpath->query(".//p[contains(@class,'card-text')]", $card);
            $linkNodes = $xpath->query(".//a[@href]", $card);

            if ($titleNodes->length === 0 || $linkNodes->length === 0) {
                continue;
            }

            $name = trim($titleNodes->item(0)->textContent);
            $description = $descNodes->length ? trim(preg_replace('/\s+/u', ' ', $descNodes->item(0)->textContent)) : null;
            $href = $linkNodes->item(0)->getAttribute('href');

            if (!$name || !$href) {
                continue;
            }

            $items[] = [
                'name_en' => $name,
                'description_en' => $description ?: null,
                'source_url' => $href,
            ];
        }

        return $items;
    }

    private function toAbsoluteUrl(string $url, string $baseUrl): string
    {
        return str_starts_with($url, 'http') ? $url : $baseUrl.'/'.ltrim($url, '/');
    }

    /**
     * @return array{latitude: ?float, longitude: ?float}
     */
    private function extractCoordinates(string $html): array
    {
        if (preg_match('~openstreetmap\.org/\?#map=\d+/(-?\d+\.\d+)/(-?\d+\.\d+)~', $html, $m)) {
            return ['latitude' => (float) $m[1], 'longitude' => (float) $m[2]];
        }

        return ['latitude' => null, 'longitude' => null];
    }

    private function isCommercial(string $name): bool
    {
        foreach (self::COMMERCIAL_NAME_PATTERNS as $pattern) {
            if (preg_match($pattern, $name)) {
                return true;
            }
        }

        return false;
    }
}
