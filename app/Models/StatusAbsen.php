<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAbsen extends Model
{
    /** @use HasFactory<\Database\Factories\StatusAbsenFactory> */
    use HasFactory;

    protected $table = "status_absens";
    protected $guarded = ['id'];

    public function absensi()
    {
        return $this->hasMany(Absen::class, 'status_absen_id');
    }
}
