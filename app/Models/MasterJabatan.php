<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MasterJabatan extends Model
{
    protected $table = "master_jabatan";
    protected $guarded = ['id'];

     /**
     * Relasi ke User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'jabatan_id');
    }
}
