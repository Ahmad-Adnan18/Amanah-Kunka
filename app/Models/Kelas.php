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

    protected $fillable = ['nama_kelas', 'kurikulum_template_id'];

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function penanggungJawab(): HasMany
    {
        return $this->hasMany(JabatanUser::class);
    }
    
    /**
     * [PERBAIKAN] Menambahkan ->withPivot('user_id')
     * Ini memberitahu Laravel bahwa kita ingin mengambil kolom 'user_id'
     * dari tabel penghubung 'kelas_mata_pelajaran'.
     */
    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'kelas_mata_pelajaran')->withPivot('user_id');
    }

    public function kurikulumTemplate(): BelongsTo
    {
        return $this->belongsTo(KurikulumTemplate::class);
    }
}

