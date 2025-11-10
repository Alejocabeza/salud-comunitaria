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
        'created_by',
    ];

    protected $casts = [
        'patient_id' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function events()
    {
        return $this->hasMany(MedicalHistoryEvent::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
