<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IzinKaryawan extends Model
{
    use HasFactory;

    protected $table = "izin_karyawans";
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisIzin()
    {
        return $this->belongsTo(JenisIzin::class, 'jenis_izin_id');
    }

    public function statusIzin()
    {
        return $this->belongsTo(StatusCuti::class, 'status_izin_id');
    }
}
