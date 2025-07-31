<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GapokKontrak extends Model
{
    /** @use HasFactory<\Database\Factories\GapokKontrakFactory> */
    use HasFactory;

    protected $table = "gapok_kontraks";
    protected $guarded = ['id'];

    public function kategoriJabatan()
    {
        return $this->belongsTo(KategoriJabatan::class);
    }

    public function pendidikan()
    {
        return $this->belongsTo(MasterPendidikan::class, 'pendidikan_id');
    }

    public function penyesuaian()
    {
        return $this->hasMany(GapokKontrakPenyesuaian::class);
    }

    // Nominal aktif berdasarkan tanggal saat ini
    public function getNominalAktifAttribute()
    {
        $penyesuaian = $this->penyesuaian()
            ->where('tanggal_berlaku', '<=', Carbon::now())
            ->orderByDesc('tanggal_berlaku')
            ->first();

        return $penyesuaian?->nominal_baru ?? $this->nominal;
    }
}
