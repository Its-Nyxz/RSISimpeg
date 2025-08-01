<?php

namespace App\Models;

use App\Models\User;
use App\Models\Potongan;
use Illuminate\Database\Eloquent\Model;

class GajiBruto extends Model
{
    protected $table = "gaji_bruto";
    protected $guarded = ['id'];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Potongan.
     */
    public function potongan()
    {
        return $this->hasMany(Potongan::class, 'bruto_id');
    }

    public function netto()
    {
        return $this->hasOne(GajiNetto::class, 'bruto_id');
    }
}
