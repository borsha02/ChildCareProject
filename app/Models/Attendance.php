<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'child_id',
        'caregiver_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
