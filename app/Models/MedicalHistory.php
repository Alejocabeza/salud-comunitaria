<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistory extends Model
{
    use FillsCreatedBy, HasFactory, SoftDeletes;

    protected $fillable = [
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
