<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recordable_type',
        'recordable_id',
        'import_job_id',
        'action',
        'old_status',
        'new_status',
        'changed_fields',
        'actor_type',
        'actor_name',
        'notes',
    ];

    protected $casts = [
        'changed_fields' => 'array',
    ];

    public function recordable()
    {
        return $this->morphTo();
    }

    public function importJob()
    {
        return $this->belongsTo(ImportJob::class);
    }

    public static function record(
        Model $record,
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        array $changedFields = [],
        string $actorType = 'system',
        ?string $actorName = null,
        ?string $notes = null,
        ?int $importJobId = null,
    ): self {
        return static::create([
            'recordable_type' => $record::class,
            'recordable_id' => $record->getKey(),
            'import_job_id' => $importJobId,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_fields' => $changedFields ?: null,
            'actor_type' => $actorType,
            'actor_name' => $actorName,
            'notes' => $notes,
        ]);
    }
}
