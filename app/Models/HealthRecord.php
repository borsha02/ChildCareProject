<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'weight',
        'height',
        'record_date',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
