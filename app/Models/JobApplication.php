<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'address',
        'position',
        'resume_path',
        'status',
    ];
}
