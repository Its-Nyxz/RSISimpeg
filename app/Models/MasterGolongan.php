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
}
