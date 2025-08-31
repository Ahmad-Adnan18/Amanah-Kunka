<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourPriority extends Model
{
    use HasFactory;

    protected $fillable = ['subject_category', 'day_of_week', 'time_slot', 'is_allowed'];
}

