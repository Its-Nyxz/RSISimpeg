<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverrideLokasi extends Model
{
    /** @use HasFactory<\Database\Factories\OverrideLokasiFactory> */
    use HasFactory;

    protected $table = "override_lokasis";
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalAbsensi::class);
    }
}
