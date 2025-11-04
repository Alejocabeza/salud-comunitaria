<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalResource extends Model
{
    use HasFactory, SoftDeletes, FillsCreatedBy;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'quantity',
        'unit',
        'available_to_public',
        'expiry_date',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'available_to_public' => 'boolean',
        'expiry_date' => 'date',
    ];

    public function outpatientCenter()
    {
        return $this->belongsTo(OutpatientCenter::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
