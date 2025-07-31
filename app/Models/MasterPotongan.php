<?php

namespace App\Models;

use App\Models\MasterFungsi;
use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    protected $table = "master_potongan";
    protected $guarded = ['id'];

    /**pmas
     * Relasi ke MasterFungsi.
     */
    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'katjab_id');
    }
}
