<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAbsensi extends Model
{
    /** @use HasFactory<\Database\Factories\JadwalAbsensiFactory> */
    use HasFactory;

    protected $table = "jadwal_absensis";
    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absen::class, 'jadwal_id');
    }


    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function opsi()
    {
        return $this->belongsTo( OpsiAbsen::class,'opsi_id');
    }
}
