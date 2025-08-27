<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    use HasFactory;
    protected $fillable = ['santri_id', 'nama_prestasi', 'poin', 'tanggal', 'keterangan', 'dicatat_oleh_id'];
    protected $casts = ['tanggal' => 'date'];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh_id');
    }
}