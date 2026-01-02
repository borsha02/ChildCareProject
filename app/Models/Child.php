<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'blood_group',
        'allergies',
        'medical_notes',
        'emergency_contact',
        'class',
        'package',
        'duration',
        'status',
        'enrollment_date',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function checkups()
    {
        return $this->hasMany(Checkup::class);
    }
    public function caregivers()
    {
        return $this->belongsToMany(User::class, 'child_assignments', 'child_id', 'caregiver_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
