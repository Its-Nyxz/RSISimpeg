<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Gapok;
use App\Models\GajiBruto;
use App\Models\UnitKerja;
use App\Models\MasterTrans;
use App\Models\Penyesuaian;
use App\Models\MasterFungsi;
use App\Models\MasterKhusus;
use App\Models\JadwalAbsensi;
use App\Models\MasterJabatan;
use App\Models\KategoriJabatan;
use App\Models\MasterPendidikan;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke MasterFungsi.
     */
    // public function fungsi()
    // {
    //     return $this->belongsTo(MasterFungsi::class, 'fungsi_id');
    // }
    // public function jabatan()
    // {
    //     return $this->belongsTo(MasterJabatan::class, 'jabatan_id');
    // }
    // public function umums()
    // {
    //     return $this->belongsTo(MasterUmum::class, 'umum_id');
    // }
    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'jabatan_id');
    }
    public function trans()
    {
        return $this->belongsTo(MasterTrans::class, 'trans_id');
    }
    public function khusus()
    {
        return $this->belongsTo(MasterKhusus::class, 'khusus_id');
    }
    public function pendAwal()
    {
        return $this->belongsTo(MasterPendidikan::class, 'pend_awal');
    }
    public function pendidikanUser()
    {
        return $this->belongsTo(MasterPendidikan::class, 'pendidikan');
    }
    public function jenis()
    {
        return $this->belongsTo(JenisKaryawan::class, 'jenis_id');
    }
    public function penyesuaians()
    {
        return $this->hasMany(Penyesuaian::class, 'user_id');
    }
    /**
     * Relasi ke GajiBruto.
     */
    public function gajiBruto()
    {
        return $this->hasMany(GajiBruto::class, 'user_id');
    }

    public function kategoriPPH()
    {
        return $this->belongsTo(Kategoripph::class, 'user_id');
    }

    public function historyPendidikan()
    {
        return $this->hasMany(HistoryPendidikan::class, 'user_id');
    }

    public function gapok()
    {
        return $this->hasMany(Gapok::class, 'user_id');
    }

    public function jadwalabsensi()
    {
        return $this->hasMany(JadwalAbsensi::class, 'user_id');
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
}
