<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['name', 'status', 'last_checkup'];

    protected $casts = [
        'last_checkup' => 'datetime',
    ];
}
