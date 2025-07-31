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
        return $this->hasMany(User::class, foreignKey: 'kategori_pendidikan');
    }
    // Relasi ke tabel golongan untuk kolom minim_gol
    public function minimGolongan()
    {
        return $this->belongsTo(MasterGolongan::class, 'minim_gol');
    }
    // Relasi ke tabel golongan untuk kolom maxim_gol
    public function maximGolongan()
    {
        return $this->belongsTo(MasterGolongan::class, 'maxim_gol');
    }
}
