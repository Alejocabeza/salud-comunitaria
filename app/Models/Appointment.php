<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'outpatient_center_id',
        'requested_date',
        'scheduled_date',
        'status',
        'patient_notes',
        'doctor_notes',
        'reason',
        'created_by',
        'updated_by',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'scheduled_date' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relaciones
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function outpatientCenter(): BelongsTo
    {
        return $this->belongsTo(OutpatientCenter::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // Métodos de ayuda
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeAcceptedBy(User $user): bool
    {
        return $this->isPending() && $this->doctor_id === $user->id;
    }

    public function accept(User $user, string $scheduledDate, ?string $notes = null): bool
    {
        if (! $this->canBeAcceptedBy($user)) {
            return false;
        }

        $this->update([
            'status' => 'accepted',
            'scheduled_date' => $scheduledDate,
            'doctor_notes' => $notes,
            'updated_by' => $user->id,
            'accepted_at' => now(),
        ]);

        return true;
    }

    public function reject(User $user, ?string $notes = null): bool
    {
        if (! $this->canBeAcceptedBy($user)) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'doctor_notes' => $notes,
            'updated_by' => $user->id,
        ]);

        return true;
    }

    public function complete(User $user): bool
    {
        if (! $this->isAccepted() || $this->doctor_id !== $user->id) {
            return false;
        }

        $this->update([
            'status' => 'completed',
            'updated_by' => $user->id,
            'completed_at' => now(),
        ]);

        return true;
    }

    public function cancel(User $user, ?string $notes = null): bool
    {
        if ($this->isCompleted()) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'doctor_notes' => $notes,
            'updated_by' => $user->id,
        ]);

        return true;
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->patient->full_name . ' - ' . $this->doctor->full_name;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'accepted' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'accepted' => 'Aceptada',
            'rejected' => 'Rechazada',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => 'Desconocido',
        };
    }
}
