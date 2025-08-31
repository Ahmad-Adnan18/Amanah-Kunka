<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'teacher_code', // DITAMBAHKAN untuk penjadwalan
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Santri: Setiap user dengan role 'wali_santri' memiliki satu data santri.
     */
    public function santri(): HasOne
    {
        return $this->hasOne(Santri::class, 'wali_id');
    }

    public function jabatans(): HasMany
    {
        return $this->hasMany(JabatanUser::class);
    }

    // --- FUNGSI BARU UNTUK PENJADWALAN ---

    /**
     * Scope a query to only include users that are teachers.
     */
    public function scopeIsTeacher($query)
    {
        return $query->where('role', '!=', 'wali_santri');
    }

    /**
     * Get the schedules for the teacher.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the unavailability records for the teacher.
     */
    public function unavailabilities(): HasMany
    {
        return $this->hasMany(TeacherUnavailability::class);
    }
}

