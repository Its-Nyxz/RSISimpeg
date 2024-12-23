<?php

namespace App\Models;

use App\Models\MasterFungsi;
use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    protected $table = "master_potongan";
    protected $guarded = ['id'];

    /**
     * Relasi ke MasterFungsi.
     */
    public function fungsi()
    {
        return $this->belongsTo(MasterFungsi::class, 'fungsi_id');
    }
}
