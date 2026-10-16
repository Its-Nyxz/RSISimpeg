<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kpi_penilaians';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function atasanPenilai()
    {
        return $this->belongsTo(User::class, 'atasan_penilai_id');
    }

    public function items()
    {
        return $this->hasMany(KpiItem::class, 'kpi_id');
    }
}
