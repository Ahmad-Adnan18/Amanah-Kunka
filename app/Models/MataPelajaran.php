<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelajaran',
        'kategori',
        'duration_jp',
        'requires_special_room',
    ];

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mata_pelajaran');
    }

    public function kurikulumTemplates(): BelongsToMany
    {
        return $this->belongsToMany(KurikulumTemplate::class, 'kurikulum_template_mata_pelajaran');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mata_pelajaran_user');
    }
}

