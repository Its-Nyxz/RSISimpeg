<?php

namespace App\Models;

use App\Models\MasterFungsi;
use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    protected $table = "master_potongan";
    protected $guarded = ['id'];

    /**pmas
     * Relasi ke MasterFungsi.
     */
    public function master()
    {
        return $this->belongsTo(MasterPotongan::class, 'master_potongan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAktifTerurut($query)
    {
        return $query
            ->where(function ($q) {
                $q->where('is_active', 1)->orWhereNull('is_active');
            })
            ->orderByRaw('CASE WHEN no_urut IS NULL THEN 1 ELSE 0 END')
            ->orderBy('no_urut', 'asc')
            ->orderBy('id', 'asc');
    }
}
