<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatJabatan extends Model
{
    protected $table = "riwayat_jabatans";
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(KategoriJabatan::class, 'kategori_jabatan_id');
    }
}
