<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'vaccine_name',
        'description',
        'administered_date',
        'scheduled_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'administered_date' => 'date',
        'scheduled_date' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
