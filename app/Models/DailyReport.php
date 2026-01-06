<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
use App\Models\User;

class DailyReport extends Model
{
    protected $fillable = [
        'child_id',
        'caregiver_id',
        'report_date',
        'mood',
        'meals',
        'nap_duration',
        'nap_quality',
        'activities',
        'notes',
        'medications_included',
        'status',
    ];

    protected $casts = [
        'report_date' => 'date',
        'meals' => 'array',
        'activities' => 'array',
        'medications_included' => 'array',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
