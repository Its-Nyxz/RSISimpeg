<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MasterKhusus extends Model
{
    protected $table = "master_khusus";
    protected $guarded = ['id'];

    /**
     * Relasi ke User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'khusus_id');
    }
}
