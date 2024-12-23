<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MasterTrans extends Model
{
    protected $table = "master_trans";
    protected $guarded = ["id"];

    /**
     * Relasi ke User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'trans_id');
    }
}
