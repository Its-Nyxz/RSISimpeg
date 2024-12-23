<?php

namespace App\Models;

use App\Models\User;
use App\Models\MasterUmum;
use Illuminate\Database\Eloquent\Model;

class Umum extends Model
{
    protected $table = "t_umum";
    protected $guarded = ['id'];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke MasterUmum.
     */
    public function masterUmum()
    {
        return $this->belongsTo(MasterUmum::class, 'umum_id');
    }
}
