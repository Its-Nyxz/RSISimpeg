<?php

namespace App\Models;

use App\Models\Umum;
use Illuminate\Database\Eloquent\Model;

class MasterUmum extends Model
{
    protected $table = "master_umum";
    protected $guarded = ['id'];

    /**
     * Relasi ke TUmum.
     */
    public function Umums()
    {
        return $this->hasMany(Umum::class, 'umum_id');
    }
}
