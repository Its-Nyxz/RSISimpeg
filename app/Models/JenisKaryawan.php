<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKaryawan extends Model
{
    /** @use HasFactory<\Database\Factories\JenisKaryawanFactory> */
    use HasFactory;

    protected $table = "jenis_karyawans";
    protected $guarded = ['id'];
}
