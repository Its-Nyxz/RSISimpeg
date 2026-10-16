<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiItem extends Model
{
    use HasFactory;

    protected $table = 'kpi_items';
    protected $guarded = ['id'];

    public function kpi()
    {
        return $this->belongsTo(KpiPenilaian::class, 'kpi_id');
    }
}
