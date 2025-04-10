<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory;

    protected $table = "shifts";
    protected $guarded = [
        'id',
    ];

    public function jadwalabsensi()
    {
        return $this->hasMany(JadwalAbsensi::class, 'shift_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
}
