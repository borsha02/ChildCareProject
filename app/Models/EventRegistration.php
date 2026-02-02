<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'child_id',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
