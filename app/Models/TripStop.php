<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripStop extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'location', 'order_index'];
}
