<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkup extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'checkup_type',
        'checkup_date',
        'doctor_name',
        'doctor_specialty',
        'weight',
        'height',
        'bmi',
        'notes',
    ];

    protected $casts = [
        'checkup_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
