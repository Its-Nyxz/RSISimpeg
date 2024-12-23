<?php

namespace App\Models;

use App\Models\User;
use App\Models\MasterPotongan;
use Illuminate\Database\Eloquent\Model;

class MasterFungsi extends Model
{
    //
    protected $table = "master_fungsi";
    protected $guarded = ["id"];
    /**
     * Relasi ke User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'fungsi_id');
    }

    /**
     * Relasi ke MasterPotongan.
     */
    public function potongans()
    {
        return $this->hasMany(MasterPotongan::class, 'fungsi_id');
    }
}
