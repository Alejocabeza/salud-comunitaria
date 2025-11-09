<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use FillsCreatedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'dni',
        'weight',
        'age',
        'blood_type',
        'is_active',
        'outpatient_center_id',
    ];

    protected $hidden = [
        'created_by',
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function medicationRequests()
    {
        return $this->hasMany(MedicationRequest::class);
    }

    public function outpatientCenter(): BelongsTo
    {
        return $this->belongsTo(OutpatientCenter::class);
    }

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'patient_disease')
            ->withPivot(['diagnosed_at', 'status', 'notes'])
            ->withTimestamps();
    }

    public function lesions()
    {
        return $this->hasMany(Lesion::class);
    }
}
