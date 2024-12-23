<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MasterPendidikan extends Model
{
    protected $table = "master_pendidikan";
    protected $guarded = ['id'];
    
    /**
     * Relasi ke User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'pend_awal');
    }
}
