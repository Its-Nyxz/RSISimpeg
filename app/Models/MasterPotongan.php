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
    public function master()
    {
        return $this->belongsTo(MasterPotongan::class, 'master_potongan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
