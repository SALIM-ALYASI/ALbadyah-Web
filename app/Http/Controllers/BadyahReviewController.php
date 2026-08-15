<?php

namespace App\Http\Controllers;

use App\Models\TouristService;
use App\Models\TouristSite;
use App\Models\VerificationLog;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

/**
 * دورة مراجعة البادية عبر البريد — مبدأ أمان صارم:
 *
 * فتح الرابط (GET) لا يغيّر أي شيء بقاعدة البيانات إطلاقًا، حتى لو فتحه
 * فاحص أمان تلقائي أو معاينة روابط بالبريد. GET فقط يعرض صفحة تأكيد.
 * التغيير الفعلي يحدث حصرًا عبر POST (زر تأكيد يضغطه المراجع بنفسه)،
 * محمي بـ CSRF + نفس توقيع الرابط + صلاحية زمنية محدودة (48-72 ساعة).
 */
class BadyahReviewController extends Controller
{
    private const MODELS = [
        'tourist-site' => TouristSite::class,
        'tourist-service' => TouristService::class,
    ];

    private const DECISIONS = [
        'approved' => 'approved',
        'needs_review' => 'needs_review',
        'rejected' => 'rejected',
    ];

    /**
     * GET — معاينة فقط. لا يكتب أي شيء بقاعدة البيانات.
     */
    public function preview(Request $request, string $recordType, int $recordId)
    {
        $record = $this->resolveRecord($recordType, $recordId);
        $intent = $this->resolveIntent($request->query('intent'));

        if ($record->verification_status !== 'pending') {
            return view('badyah.review-result', ['record' => $record, 'alreadyDecided' => true]);
        }

        // رابط POST يحمل نفس توقيت الانتهاء بالضبط (لا يُمدَّد بمجرد فتح المعاينة)
        $expires = $request->query('expires')
            ? Carbon::createFromTimestamp((int) $request->query('expires'))
            : now()->addHours(72);

        $confirmUrl = URL::temporarySignedRoute('badyah.review.confirm', $expires, [
            'recordType' => $recordType,
            'recordId' => $recordId,
            'intent' => $intent,
        ]);

        // الولاية غير معروفة من المصدر لهذه السجلات — نعرض قائمة ولايات نفس
        // المحافظة حتى يحددها المراجع البشري بمعرفته الفعلية (مو تخمين AI).
        $wilayatOptions = $record->wilayat_id
            ? collect()
            : Wilayat::where('governorate_id', $record->governorate_id)->orderBy('name_ar')->get();

        return view('badyah.review-confirm', [
            'record' => $record,
            'intent' => $intent,
            'confirmUrl' => $confirmUrl,
            'expiresAt' => $expires,
            'wilayatOptions' => $wilayatOptions,
        ]);
    }

    /**
     * POST — التغيير الفعلي الوحيد. يتطلب CSRF + توقيع صالح غير منتهٍ.
     */
    public function confirm(Request $request, string $recordType, int $recordId)
    {
        $record = $this->resolveRecord($recordType, $recordId);
        $intent = $this->resolveIntent($request->query('intent'));

        // Idempotency: أي محاولة ثانية (نفس الرابط أو ضغط مزدوج) لا تُغيّر شيئًا
        if ($record->verification_status !== 'pending') {
            return view('badyah.review-result', ['record' => $record, 'alreadyDecided' => true]);
        }

        $reviewer = $request->input('reviewer', 'gmail-review-link');
        $note = trim((string) $request->input('note', ''));
        $confirmArabicName = $request->boolean('confirm_arabic_name');

        $oldStatus = $record->verification_status;
        $record->verification_status = $intent;
        $record->reviewed_by = $reviewer;
        $record->reviewed_at = now();

        // الولاية: يُقبل فقط إذا اختارها المراجع صراحة من قائمة ولايات نفس
        // المحافظة (لا قيمة حرة، لا تخمين) — تصحيح معرفة بشرية حقيقية.
        $selectedWilayatId = $request->input('wilayat_id');
        if (!$record->wilayat_id && $selectedWilayatId) {
            $validWilayat = Wilayat::where('id', $selectedWilayatId)
                ->where('governorate_id', $record->governorate_id)
                ->exists();

            if ($validWilayat) {
                $record->wilayat_id = $selectedWilayatId;
                $record->needs_review_fields = collect($record->needs_review_fields)
                    ->reject(fn ($f) => $f === 'wilayat_id')
                    ->values()
                    ->all();
            }
        }

        // الاعتماد العام للسجل شيء، وتأكيد صحة الاسم العربي الرسمي شيء آخر تمامًا.
        // name_ar_verified لا يتغيّر تلقائيًا لمجرد approved — فقط إذا أكّده المراجع صراحة.
        if ($confirmArabicName) {
            $record->name_ar_verified = true;
            $record->name_ar_source = 'official';
        }

        if ($intent === 'rejected') {
            $record->is_active = false;
        }

        $record->save();

        VerificationLog::record(
            record: $record,
            action: match ($intent) {
                'approved' => 'approved',
                'rejected' => 'rejected',
                default => 'flagged_needs_review',
            },
            oldStatus: $oldStatus,
            newStatus: $intent,
            actorType: 'admin',
            actorName: $reviewer,
            notes: $note !== '' ? $note : null,
        );

        return view('badyah.review-result', [
            'record' => $record,
            'alreadyDecided' => false,
            'decision' => $intent,
        ]);
    }

    private function resolveRecord(string $recordType, int $recordId)
    {
        abort_unless(isset(self::MODELS[$recordType]), 404, 'نوع سجل غير معروف.');
        return self::MODELS[$recordType]::findOrFail($recordId);
    }

    private function resolveIntent(?string $intent): string
    {
        abort_unless(isset(self::DECISIONS[$intent]), 404, 'قرار غير معروف.');
        return self::DECISIONS[$intent];
    }
}
