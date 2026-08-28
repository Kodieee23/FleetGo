<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'purpose_id',
        'department_id',
        'destination',
        'time_out',
        'time_returned',
        'other_purpose_description',
        'is_offline_entry',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function purpose()
    {
        return $this->belongsTo(TripPurpose::class, 'purpose_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function stops()
    {
        return $this->hasMany(TripStop::class);
    }

    public function getDisplayPurposeAttribute()
    {
        $purposeName = $this->purpose->name ?? 'Unknown';
        if (strtolower($purposeName) === 'other' && !empty($this->other_purpose_description)) {
            return $this->other_purpose_description;
        }
        return $purposeName;
    }
}
