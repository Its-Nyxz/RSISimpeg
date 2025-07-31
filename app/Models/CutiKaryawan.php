<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiKaryawan extends Model
{
    /** @use HasFactory<\Database\Factories\CutiKaryawanFactory> */
    use HasFactory;

    protected $table = "cuti_karyawans";
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function statusCuti()
    {
        return $this->belongsTo(StatusCuti::class);
    }
}
