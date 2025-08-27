<?php
// app/Models/CatatanHarian.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanHarian extends Model
{
    use HasFactory;
    protected $fillable = ['santri_id', 'tanggal', 'catatan', 'dicatat_oleh_id'];
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