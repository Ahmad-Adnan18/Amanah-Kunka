<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    // [PERUBAHAN] Tambahkan 'room_id' agar bisa diisi secara massal
    protected $fillable = ['nama_kelas', 'kurikulum_template_id', 'room_id'];

    // [RELASI BARU] Mendefinisikan hubungan ke model Room
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function penanggungJawab(): HasMany
    {
        return $this->hasMany(JabatanUser::class);
    }
    
    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'kelas_mata_pelajaran')->withPivot('user_id');
    }

    public function kurikulumTemplate(): BelongsTo
    {
        return $this->belongsTo(KurikulumTemplate::class);
    }
}