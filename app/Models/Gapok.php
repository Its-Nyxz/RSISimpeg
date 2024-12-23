<?php

namespace App\Models;

use App\Models\User;
use App\Models\MasterGapok;
use App\Models\MasterGolongan;
use Illuminate\Database\Eloquent\Model;

class Gapok extends Model

{
    protected $table = "t_gapok";
    protected $guarded = ['id'];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke MasterGolongan.
     */
    public function golongan()
    {
        return $this->belongsTo(MasterGolongan::class, 'gol_id');
    }

    /**
     * Relasi ke MasterGapok.
     */
    public function gapoks()
    {
        return $this->belongsTo(MasterGapok::class, 'gapok_id');
    }
}
