<?php

namespace App\Console\Commands;

use App\Mail\BadyahReviewBatchMail;
use App\Models\TouristSite;
use App\Services\Badyah\Collectors\ExperienceOmanCollector;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * يرسل دفعات مراجعة بريدية (حتى 5 سجلات لكل رسالة) لكل السجلات المعلّقة
 * التي جمعها ExperienceOmanCollector في تجربة مسقط الأولى فقط.
 *
 * افتراضيًا Dry-Run (أو صراحة عبر --dry-run): يبني الرسائل ويحفظها كملفات
 * HTML للمعاينة بدون إرسال فعلي ولا أي تعديل على قاعدة البيانات.
 *
 * الإرسال الحقيقي (--send) يُرفض تلقائيًا إذا: البريد المستلم غير مضبوط،
 * إعدادات SMTP ناقصة، المُرسِل لا يزال log، أو التطبيق بوضع Debug.
 */
class BadyahSendReviewBatch extends Command
{
    protected $signature = 'badyah:send-review-batch
        {--to= : البريد المستلم (افتراضيًا من BADYAH_REVIEW_EMAIL في .env)}
        {--chunk=5 : عدد السجلات في كل رسالة}
        {--send : إرسال فعلي؛ يتطلب اجتياز كل شروط الأمان بالأسفل}
        {--dry-run : معاينة صريحة بدون إرسال (نفس السلوك الافتراضي بدون --send)}
        {--expires-hours=72 : مدة صلاحية روابط المراجعة بالساعات}
        {--dry-run-dir= : مجلد حفظ معاينة HTML في وضع Dry-Run}';

    protected $description = 'يبني/يرسل دفعات مراجعة بريدية لسجلات Pending من تجربة مسقط، مع روابط قرار موقّعة لكل سجل';

    public function handle(): int
    {
        $isSend = (bool) $this->option('send');

        if ($isSend) {
            $guardError = $this->checkSendGuards();
            if ($guardError) {
                $this->error($guardError);
                $this->line('استخدم --dry-run (أو بدون --send) لمعاينة الرسائل بأمان بدون إرسال.');
                return self::FAILURE;
            }
        }

        $records = TouristSite::where('verification_status', 'pending')
            ->where('collector_name', ExperienceOmanCollector::class)
            ->orderBy('id')
            ->get();

        if ($records->isEmpty()) {
            $this->warn('لا توجد سجلات pending من ExperienceOmanCollector لإرسالها.');
            return self::SUCCESS;
        }

        $chunkSize = max(1, (int) $this->option('chunk'));
        $chunks = $records->chunk($chunkSize)->values();
        $totalBatches = $chunks->count();
        $to = $this->option('to') ?: config('badyah.review_email');

        $this->info(($isSend ? '[SEND] ' : '[DRY-RUN] ')."سيتم تجهيز {$totalBatches} دفعة من أصل {$records->count()} سجل (حجم الدفعة: {$chunkSize})");

        $dryRunDir = $this->option('dry-run-dir') ?: storage_path('app/badyah_review_previews');
        if (!$isSend && !is_dir($dryRunDir)) {
            mkdir($dryRunDir, 0755, true);
        }

        $expiresAt = now()->addHours((int) $this->option('expires-hours'));
        $batchId = (string) Str::uuid();

        foreach ($chunks as $index => $chunk) {
            $batchNumber = $index + 1;
            $rows = $chunk->map(fn (TouristSite $site) => $this->buildRow($site, $expiresAt))->all();

            $mail = new BadyahReviewBatchMail(
                batchLabel: 'محافظة مسقط',
                rows: $rows,
                batchNumber: $batchNumber,
                totalBatches: $totalBatches,
            );

            if (!$isSend) {
                $html = $mail->render();
                $path = "{$dryRunDir}/batch-{$batchNumber}-of-{$totalBatches}.html";
                file_put_contents($path, $html);
                $this->line("  دفعة {$batchNumber}/{$totalBatches}: ".count($rows).' سجل → معاينة محفوظة في: '.$path);
                foreach ($rows as $row) {
                    $this->line("    #{$row['record_id']} {$row['name_ar']} | approve: {$row['approve_url']}");
                }
            } else {
                Mail::to($to)->send($mail);
                $chunk->each(fn (TouristSite $site) => $site->update(['review_requested_at' => now()]));

                // Audit log مقصود: فقط معرّف الدفعة + العدد + الوقت + بريد مموَّه.
                // ممنوع تسجيل محتوى الرسالة أو أي رابط موقّع أو توكن هنا.
                Log::info('badyah.review_batch.sent', [
                    'batch_id' => $batchId,
                    'batch_number' => $batchNumber,
                    'total_batches' => $totalBatches,
                    'record_count' => count($rows),
                    'sent_at' => now()->toIso8601String(),
                    'recipient_masked' => $this->maskEmail($to),
                ]);

                $this->line("  دفعة {$batchNumber}/{$totalBatches}: أُرسلت فعليًا إلى ".$this->maskEmail($to).' ('.count($rows).' سجل)');
            }
        }

        if (!$isSend) {
            $this->newLine();
            $this->info('هذا Dry-Run فقط — لم يُرسل أي بريد فعلي ولم يتغيّر أي سجل بقاعدة البيانات.');
            $this->info("افتح ملفات HTML بمجلد: {$dryRunDir}");
        } else {
            $this->newLine();
            $this->info("batch_id: {$batchId} — تم تسجيله بالـ log (بدون أي محتوى أو توقيعات).");
        }

        return self::SUCCESS;
    }

    /**
     * كل شروط الإرسال الفعلي. يرجع رسالة الخطأ الأولى التي تفشل، أو null إذا كله سليم.
     */
    private function checkSendGuards(): ?string
    {
        $to = $this->option('to') ?: config('badyah.review_email');
        if (!$to) {
            return 'BADYAH_REVIEW_EMAIL فارغ (أو مرّره عبر --to=email). لا يمكن الإرسال الفعلي بدونه.';
        }

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return "البريد المستلم غير صالح: {$to}";
        }

        if (config('mail.default') === 'log') {
            return 'MAIL_MAILER لا يزال "log" — هذا يعني أن أي "إرسال" سيُكتب بملف اللوق فقط، مو بريد فعلي. اضبط MAIL_MAILER=smtp أولًا.';
        }

        $smtp = config('mail.mailers.smtp', []);
        $missing = collect([
            'MAIL_HOST' => $smtp['host'] ?? null,
            'MAIL_PORT' => $smtp['port'] ?? null,
            'MAIL_USERNAME' => $smtp['username'] ?? null,
            'MAIL_PASSWORD' => $smtp['password'] ?? null,
        ])->filter(fn ($v) => empty($v))->keys();

        if ($missing->isNotEmpty()) {
            return 'إعدادات SMTP ناقصة بالـ.env: '.$missing->implode('، ');
        }

        if (empty(config('mail.from.address'))) {
            return 'MAIL_FROM_ADDRESS فارغ بالـ.env.';
        }

        if (config('app.debug') === true) {
            return 'APP_DEBUG=true حاليًا — غير مناسب لإرسال حقيقي (قد تُكشف تفاصيل حساسة بأي خطأ). اضبط APP_DEBUG=false للإرسال الفعلي.';
        }

        return null;
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $visible = mb_substr($local, 0, 1);
        return $visible.str_repeat('*', max(2, mb_strlen($local) - 1))."@{$domain}";
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRow(TouristSite $site, Carbon $expiresAt): array
    {
        // روابط GET للمعاينة فقط — لا تغيّر شيئًا حتى لو فتحها فاحص بريد تلقائي.
        // التغيير الفعلي يتم لاحقًا عبر POST من صفحة التأكيد نفسها.
        $urlFor = fn (string $intent) => URL::temporarySignedRoute('badyah.review.preview', $expiresAt, [
            'recordType' => 'tourist-site',
            'recordId' => $site->id,
            'intent' => $intent,
        ]);

        return [
            'record_id' => $site->id,
            'name_ar' => $site->name_ar,
            'name_en' => $site->name_en,
            'category' => $site->category?->name_en ?? '—',
            'governorate' => $site->governorate?->name_ar ?? '—',
            'wilayat' => $site->wilayat?->name_ar,
            'coordinates' => ($site->latitude && $site->longitude) ? "{$site->latitude}, {$site->longitude}" : null,
            'source_url' => $site->source_url,
            'source_trust_score' => (float) $site->source_trust_score,
            'ai_confidence' => (float) $site->ai_confidence,
            'needs_review_fields' => $site->needs_review_fields ?? [],
            'name_ar_source' => $site->name_ar_source ?? 'ai_translation',
            'description_ar_generated' => (bool) $site->description_ar_generated,
            'collected_at' => optional($site->collected_at)->format('Y-m-d H:i'),
            'approve_url' => $urlFor('approved'),
            'needs_review_url' => $urlFor('needs_review'),
            'reject_url' => $urlFor('rejected'),
        ];
    }
}
