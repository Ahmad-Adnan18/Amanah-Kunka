<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherUnavailability extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'day_of_week', 'time_slot'];

    /**
     * Get the user (teacher) that owns the unavailability.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

