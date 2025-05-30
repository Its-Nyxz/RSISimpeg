<?php

namespace App\Models;

use App\Models\MasterPenyesuaian;
use Illuminate\Database\Eloquent\Model;

class Penyesuaian extends Model
{
    protected $table = "t_penyesuaian";

    protected $guarded = ['id'];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke MasterPenyesuaian.
     */
    public function penyesuaian()
    {
        return $this->belongsTo(MasterPenyesuaian::class, 'penyesuaian_id');
    }
    public function golonganAkhir()
    {
        return $this->belongsTo(MasterGolongan::class, 'gol_id_akhir');
    }
    public function golonganAwal()
    {
        return $this->belongsTo(MasterGolongan::class, 'gol_id_awal');
    }
}
