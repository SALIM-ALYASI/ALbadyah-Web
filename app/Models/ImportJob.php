<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ImportJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_uuid',
        'workflow_name',
        'record_type',
        'governorate_id',
        'wilayat_id',
        'status',
        'started_at',
        'finished_at',
        'total_fetched',
        'total_created',
        'total_updated',
        'total_duplicates',
        'total_rejected',
        'total_failed',
        'error_message',
        'meta',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $job) {
            if (empty($job->job_uuid)) {
                $job->job_uuid = (string) Str::uuid();
            }
        });
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class);
    }

    public function verificationLogs()
    {
        return $this->hasMany(VerificationLog::class);
    }

    public function markRunning(): void
    {
        $this->update(['status' => 'running', 'started_at' => now()]);
    }

    public function markFinished(bool $failed = false, ?string $errorMessage = null): void
    {
        $this->update([
            'status' => $failed ? 'failed' : 'completed',
            'finished_at' => now(),
            'error_message' => $errorMessage,
        ]);
    }
}
