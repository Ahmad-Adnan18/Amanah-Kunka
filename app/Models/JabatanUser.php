<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JabatanUser extends Model
{
    use HasFactory;
    protected $table = 'jabatan_user';
    protected $fillable = ['user_id', 'kelas_id', 'jabatan_id', 'tahun_ajaran'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }
}
