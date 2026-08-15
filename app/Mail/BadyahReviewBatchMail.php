<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * دفعة مراجعة بريدية لمحرك البادية: تحتوي حتى 5 سجلات معلّقة (pending)،
 * كل سجل معه كامل بيانات المصدر/الثقة/المراجعة + 3 روابط قرار موقّعة
 * (Approved / Needs Review / Rejected) تُحدّث نفس السجل عبر record_id،
 * بدون إنشاء أي سجل جديد.
 */
class BadyahReviewBatchMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<int, array<string, mixed>> $rows كل صف = بيانات السجل + روابط القرار
     */
    public function __construct(
        public string $batchLabel,
        public array $rows,
        public int $batchNumber,
        public int $totalBatches,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[البادية الذكية] مراجعة بيانات — {$this->batchLabel} (دفعة {$this->batchNumber}/{$this->totalBatches})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.badyah.review-batch',
            with: [
                'rows' => $this->rows,
                'batchLabel' => $this->batchLabel,
                'batchNumber' => $this->batchNumber,
                'totalBatches' => $this->totalBatches,
            ],
        );
    }
}
