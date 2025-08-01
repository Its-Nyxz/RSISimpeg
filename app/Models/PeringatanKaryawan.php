<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeringatanKaryawan extends Model
{
    /** @use HasFactory<\Database\Factories\PeringatanKaryawanFactory> */
    use HasFactory;

    protected $table = "peringatan_karyawans";

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
