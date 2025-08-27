<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
}
