<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * كل مرشّح دخل عبر الاكتشاف الآلي (OSM) كانت حالته "يحتاج إثراء" دائمًا
     * لأن wilayat_id فاضٍ - وهو شرط إلزامي بمنطق النشر (MapCandidatePublisher)
     * حتى لحفظ "للمراجعة" العادية، لا يخص النشر العام فقط.
     *
     * هذا الملف يطبّق نتيجة مطابقة جغرافية دقيقة (نقطة داخل حدود كل ولاية
     * الرسمية من OpenStreetMap، لا تقريب بمربّع) حُسبت خارج قاعدة البيانات:
     * 2117 من أصل 2273 مرشّح تطابقوا بنجاح مع ولاية حقيقية. الباقون (156،
     * أغلبهم ولاية صور غير المتوفرة حدودها بـOSM حاليًا + حالات حدّية
     * متفرقة) يبقون بحاجة تعيين يدوي.
     */
    public function up(): void
    {
        $path = __DIR__.'/data/map_candidate_wilayat_assignments.json';
        if (!file_exists($path)) {
            return;
        }

        $assignments = json_decode(file_get_contents($path), true) ?: [];

        $wilayatToGovernorate = DB::table('wilayats')->pluck('governorate_id', 'id')->toArray();

        foreach (array_chunk($assignments, 200, true) as $chunk) {
            foreach ($chunk as $candidateId => $wilayatId) {
                $governorateId = $wilayatToGovernorate[$wilayatId] ?? null;

                DB::table('map_candidates')
                    ->where('id', (int) $candidateId)
                    ->update([
                        'wilayat_id' => $wilayatId,
                        'governorate_id' => $governorateId,
                    ]);
            }
        }

        // أي مرشّح "يحتاج إثراء" صار عنده الآن ولاية + اسم عربي فعلي (مو
        // "بلا اسم") يصير جاهزًا للمراجعة العادية - لا نلمس أي حالة أخرى
        // (approved_draft/rejected/deferred/published) حتى لو تصادف عندها
        // wilayat_id فاضٍ لسبب آخر.
        DB::table('map_candidates')
            ->where('status', 'needs_enrichment')
            ->whereNotNull('wilayat_id')
            ->whereNotNull('name_ar')
            ->where('name_ar', '!=', '')
            ->update(['status' => 'pending_review']);
    }

    public function down(): void
    {
        $path = __DIR__.'/data/map_candidate_wilayat_assignments.json';
        if (!file_exists($path)) {
            return;
        }

        $assignments = json_decode(file_get_contents($path), true) ?: [];
        $ids = array_map('intval', array_keys($assignments));

        DB::table('map_candidates')
            ->whereIn('id', $ids)
            ->update(['wilayat_id' => null, 'governorate_id' => null, 'status' => 'needs_enrichment']);
    }
};
