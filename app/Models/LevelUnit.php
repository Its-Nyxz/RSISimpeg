<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UnitKerja;
use App\Models\LevelPoint;

class LevelUnit extends Model
{
    /** @use HasFactory<\Database\Factories\LevelUnitFactory> */
    use HasFactory;

    protected $table = "level_units";
    protected $guarded = ['id'];

    public function unitkerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    public function levelpoint()
    {
        return $this->belongsTo(LevelPoint::class, 'level_id');
    }

}
