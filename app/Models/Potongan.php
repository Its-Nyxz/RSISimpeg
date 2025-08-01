<?php

namespace App\Models;

use App\Models\GajiBruto;
use App\Models\GajiNetto;
use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    protected $table = "potongan";
    protected $guarded = ['id'];

    /**
     * Relasi ke GajiBruto.
     */
    public function gajiBruto()
    {
        return $this->belongsTo(GajiBruto::class, 'bruto_id');
    }

    /**
     * Relasi ke GajiNetto.
     */
    public function gajiNetto()
    {
        return $this->belongsTo(GajiNetto::class, 't_pot_id');
    }

    public function masterPotongan()
    {
        return $this->belongsTo(MasterPotongan::class, 'master_potongan_id');
    }
}
