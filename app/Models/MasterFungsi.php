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
    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'katjab_id');
    }

    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'katjab_id');
    }
    /**
     * Relasi ke MasterPotongan.
     */
    public function potongans()
    {
        return $this->hasMany(MasterPotongan::class, 'fungsi_id');
    }

    public function points()
    {
        return $this->morphMany(PointJabatan::class, 'pointable');
    }

    public function proposionalitasPoints()
    {
        return $this->morphMany(ProposionalitasPoint::class, 'proposable');
    }
    public function peranPoints()
    {
        return $this->morphMany(PointPeran::class, 'peransable');
    }
}
