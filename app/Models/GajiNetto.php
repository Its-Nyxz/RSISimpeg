<?php

namespace App\Models;

use App\Models\Potongan;
use Illuminate\Database\Eloquent\Model;

class GajiNetto extends Model
{
    protected $table = "gaji_netto";
    protected $guarded = ['id'];

    /**
     * Relasi ke Potongan.
     */

    public function bruto()
    {
        return $this->belongsTo(GajiBruto::class, 'bruto_id');
    }
}
