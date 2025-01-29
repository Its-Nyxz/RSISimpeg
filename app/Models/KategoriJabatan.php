<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriJabatan extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriJabatanFactory> */
    use HasFactory;

    protected $table = "kategori_jabatans";
    protected $guarded = ['id'];
}
