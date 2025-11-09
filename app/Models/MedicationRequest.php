<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicationRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'patient_id',
        'outpatient_center_id',
        'medical_resource_id',
        'quantity',
        'status',
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'processed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalResource()
    {
        return $this->belongsTo(MedicalResource::class);
    }

    public function outpatientCenter()
    {
        return $this->belongsTo(OutpatientCenter::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Approve the request and decrement stock atomically.
     *
     * @param  int|null  $processorId
     * @return void
     */
    public function approve(?int $processorId = null): void
    {
        DB::transaction(function () use ($processorId) {
            $resource = $this->medicalResource()->lockForUpdate()->first();

            if (!$resource) {
                throw new \RuntimeException('Medical resource not found');
            }

            if ($resource->quantity < $this->quantity) {
                throw new \RuntimeException('Insufficient stock to approve request');
            }

            $resource->quantity -= $this->quantity;
            $resource->save();

            $this->status = self::STATUS_APPROVED;
            $this->processed_by = $processorId;
            $this->processed_at = now();
            $this->save();
        });
    }

    public function reject(string $reason = null, ?int $processorId = null): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->notes = $reason ?? $this->notes;
        $this->processed_by = $processorId;
        $this->processed_at = now();
        $this->save();
    }

    public function fulfill(?int $processorId = null): void
    {
        $this->status = self::STATUS_FULFILLED;
        $this->processed_by = $processorId;
        $this->processed_at = now();
        $this->save();
    }

    /**
     * Ensure outpatient_center_id is set before creating to avoid DB not-null violations.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (! isset($model->outpatient_center_id) || $model->outpatient_center_id === null) {
                // Try to populate from the selected medical resource
                if (! empty($model->medical_resource_id)) {
                    $resource = \App\Models\MedicalResource::find($model->medical_resource_id);
                    $model->outpatient_center_id = $resource?->outpatient_center_id;
                }

                // Fallback to patient's outpatient_center
                if (empty($model->outpatient_center_id) && ! empty($model->patient_id)) {
                    $patient = \App\Models\Patient::find($model->patient_id);
                    $model->outpatient_center_id = $patient?->outpatient_center_id;
                }

                // If still missing, throw a clearer error rather than letting the DB produce a generic not-null violation.
                if (empty($model->outpatient_center_id)) {
                    throw new \RuntimeException('Outpatient center is required for medication requests. Ensure the patient has an outpatient center assigned or select one in the form.');
                }
            }
        });
    }
}
