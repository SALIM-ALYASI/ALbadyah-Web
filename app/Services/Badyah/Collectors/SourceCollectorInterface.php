<?php

namespace App\Services\Badyah\Collectors;

/**
 * عقد موحّد لأي مصدر بيانات في محرك البادية الذكي. إضافة مصدر جديد
 * (Dataset وزارة التراث والسياحة مستقبلًا مثلًا) = كلاس جديد يطبّق هذا
 * العقد + سطر تفعيل في config/badyah.php، بدون لمس بقية البوت.
 */
interface SourceCollectorInterface
{
    /**
     * مفتاح المصدر كما هو في config('badyah.sources').
     */
    public function key(): string;

    public function isEnabled(): bool;

    /**
     * يرجع مصفوفة عناصر خام غير مُخصَّبة بعد (قبل الذكاء الاصطناعي).
     * كل عنصر خام يجب أن يحتوي على الأقل: name (بأي لغة متوفرة)، source_url،
     * ومعرّف المحافظة إن أمكن تحديده من المصدر نفسه.
     *
     * @param array{governorate_slug?: string, limit?: int} $context
     * @return array<int, array<string, mixed>>
     */
    public function collect(array $context = []): array;
}
