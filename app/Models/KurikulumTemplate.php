<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KurikulumTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['nama_template'];

    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'kurikulum_template_mata_pelajaran');
    }
}
