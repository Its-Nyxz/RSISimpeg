<?php

namespace App\Models;

use App\Models\Penyesuaian;
use App\Models\MasterPendidikan;
use Illuminate\Database\Eloquent\Model;

class MasterPenyesuaian extends Model
{
    protected $table = "master_penyesuaian";
    protected $guarded = ['id'];

    /**
     * Relasi ke TPenyesuaian.
     */
    public function penyesuaians()
    {
        return $this->hasMany(Penyesuaian::class, 'penyesuaian_id');
    }

    /**
     * Relasi ke MasterPendidikan untuk pendidikan_awal.
     */
    public function pendidikanAwal()
    {
        return $this->belongsTo(MasterPendidikan::class, 'pendidikan_awal');
    }

    /**
     * Relasi ke MasterPendidikan untuk pendidikan_penyesuaian.
     */
    public function pendidikanPenyesuaian()
    {
        return $this->belongsTo(MasterPendidikan::class, 'pendidikan_penyesuaian');
    }
}
