<?php

namespace App\Models;

use App\Models\MasterGapok;
use Illuminate\Database\Eloquent\Model;

class MasterGolongan extends Model
{
    protected $table = "master_golongan";

    protected $guarded = ['id'];


    /**
     * Relasi ke MasterGapok.
     */
    public function gapoks()
    {
        return $this->hasMany(MasterGapok::class, 'gol_id');
    }

    public function pendidikanMin()
    {
        return $this->hasMany(MasterPendidikan::class, 'minim_gol');
    }

    public function pendidikanMax()
    {
        return $this->hasMany(MasterPendidikan::class, 'maxim_gol');
    }
}
