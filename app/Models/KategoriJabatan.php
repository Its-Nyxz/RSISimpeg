<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriJabatan extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriJabatanFactory> */
    use HasFactory;

    protected $table = "kategori_jabatans";
    protected $guarded = ['id'];

    public function masterjabatan()
    {
        return $this->hasMany(MasterJabatan::class, 'katjab_id');
    }
    public function masterfungsi()
    {
        return $this->hasMany(MasterFungsi::class, 'katjab_id');
    }
    public function masterumum()
    {
        return $this->hasMany(MasterUmum::class, 'katjab_id');
    }
    public function masterpotongan()
    {
        return $this->hasMany(MasterPotongan::class, 'katjab_id');
    }
    public function getNominalAttribute()
    {
        return match ($this->tunjangan) {
            'jabatan' => $this->masterjabatan->first()?->nominal,
            'umum' => $this->masterumum->first()?->nominal,
            'fungsional' => $this->masterfungsi->first()?->nominal,
            default => 0,
        };
    }

    public function users()
    {
        return $this->hasMany(User::class, 'jabatan_id');
    }
}
