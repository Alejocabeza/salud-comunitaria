<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistoryEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medical_history_events';

    protected $fillable = [
        'medical_history_id',
        'patient_id',
        'doctor_id',
        'type',
        'date',
        'summary',
        'notes',
        'related_diseases',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'related_diseases' => 'array',
        'date' => 'datetime',
    ];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
