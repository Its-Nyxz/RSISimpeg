<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GapokKontrakPenyesuaian extends Model
{
    /** @use HasFactory<\Database\Factories\GapokKontrakPenyesuaianFactory> */
    use HasFactory;

    protected $table = "gapok_kontrak_penyesuaians";
    protected $guarded = ['id'];

    // Relasi ke gapok kontrak
    public function gapokKontrak()
    {
        return $this->belongsTo(GapokKontrak::class);
    }

    public function penyesuaianTerbaru()
    {
        return $this->hasOne(GapokKontrakPenyesuaian::class)
            ->where('tanggal_berlaku', '<=', now())
            ->latestOfMany('tanggal_berlaku');
    }
}
