<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = "absensi";
    protected $guarded = ['id'];

    public function statusAbsen()
    {
        return $this->belongsTo(StatusAbsen::class, 'status_absen_id');
    }
    public function jadwalAbsen()
    {
        return $this->belongsTo(JadwalAbsensi::class, 'jadwal_id');
    }
}
