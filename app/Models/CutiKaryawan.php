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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function statusCuti()
    {
        return $this->belongsTo(StatusCuti::class);
    }

    public function riwayatApprovals()
    {
        return $this->hasMany(RiwayatApproval::class, 'cuti_id');
    }
}
