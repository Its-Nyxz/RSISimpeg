<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = "absensi";
    protected $guarded = ['id'];

    // Ubah tipe data ke timestamp otomatis (diubah ke Carbon instance)
    protected $casts = [
        'time_in' => 'timestamp',
        'time_out' => 'timestamp',
    ];

    public function statusAbsen()
    {
        return $this->belongsTo(StatusAbsen::class, 'status_absen_id');
    }
    public function jadwalAbsen()
    {
        return $this->belongsTo(JadwalAbsensi::class, 'jadwal_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
