<?php

namespace App\Models;

use App\Models\Gapok;
use App\Models\MasterGolongan;
use Illuminate\Database\Eloquent\Model;

class MasterGapok extends Model
{
    protected $table = "master_gapok";
    protected $guarded = ['id'];

    /**
     * Relasi ke MasterGolongan.
     */
    public function golongan()
    {
        return $this->belongsTo(MasterGolongan::class, 'gol_id');
    }

    /**
     * Relasi ke Gapok.
     */
    public function gapoks()
    {
        return $this->hasMany(Gapok::class, 'gapok_id');
    }
}
