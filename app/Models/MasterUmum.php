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
    public function users()
    {
        return $this->hasMany(User::class, 'umum_id');
    }

    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'katjab_id');
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

    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'katjab_id');
    }
    // public function parent()
    // {
    //     return $this->belongsTo(MasterUmum::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(MasterUmum::class, 'parent_id');
    // }
}
