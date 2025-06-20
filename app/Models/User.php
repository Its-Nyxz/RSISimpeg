<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Gapok;
use App\Models\GajiBruto;
use App\Models\UnitKerja;
use App\Models\MasterTrans;
use App\Models\Penyesuaian;
use Illuminate\Support\Str;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        // Jika ingin memperbarui slug saat nama berubah:
        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function getNamaBersihAttribute()
    {
        $name = $this->name;

        // Hapus awalan gelar
        $name = preg_replace('/^(drg\.?|dr\.?|drs\.?|drh\.?)\s+/i', '', $name);

        // Hapus gelar belakang berbasis spasi atau singkatan tanpa koma
        $name = preg_replace('/\s+(Sp\.\w+|M\.\w+|S\.\w+|MKes|M\.Kes|SKp)$/i', '', $name);

        // Hapus gelar dengan koma di akhir, fleksibel terhadap titik dan spasi
        $name = preg_replace(
            '/,\s*(A(\.?)(Md)?\.?\s*Kep\.?|Amd\.?Kep\.?|SE|S\.Sos|Sp\.An|Sp\.Rad|Sp\s*U|Sp\s*PK|S\.KM|S\.Farm\s*Apt)\.?/i',
            '',
            $name
        );

        return trim($name);
    }

    /**
     * Relasi ke MasterFungsi.
     */
    public function golongan()
    {
        return $this->belongsTo(MasterGolongan::class, 'gol_id');
    }
    public function fungsi()
    {
        return $this->belongsTo(MasterFungsi::class, 'fungsi_id');
    }
    public function jabatan()
    {
        return $this->belongsTo(MasterJabatan::class, 'jabatan_id');
    }
    public function umums()
    {
        return $this->belongsTo(MasterUmum::class, 'umum_id');
    }
    public function kategorijabatan()
    {
        return $this->belongsTo(KategoriJabatan::class, 'jabatan_id');
    }
    public function kategorifungsional()
    {
        return $this->belongsTo(KategoriJabatan::class, 'fungsi_id');
    }
    public function kategoriumum()
    {
        return $this->belongsTo(KategoriJabatan::class, 'umum_id');
    }
    public function trans()
    {
        return $this->belongsTo(MasterTrans::class, 'trans_id');
    }
    public function khusus()
    {
        return $this->belongsTo(MasterKhusus::class, 'khusus_id');
    }

    public function pendidikanUser()
    {
        return $this->belongsTo(MasterPendidikan::class, 'kategori_pendidikan');
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
        return $this->belongsTo(Kategoripph::class, 'kategori_id');
    }
    public function kategoriPphInduk()
    {
        return $this->kategoriPPH?->parent ?? $this->kategoriPPH;
    }

    public function historyPendidikan()
    {
        return $this->hasMany(HistoryPendidikan::class, 'user_id');
    }

    public function historyGapok()
    {
        return $this->hasMany(Gapok::class, 'user_id');
    }

    public function pendingGolonganGapok()
    {
        return $this->hasOne(Gapok::class)
            ->where('jenis_kenaikan', 'golongan')
            ->where('status', false)
            ->latestOfMany();
    }

    public function jadwalabsensi()
    {
        return $this->hasMany(JadwalAbsensi::class, 'user_id');
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
    public function absen()
    {
        return $this->hasMany(Absen::class, 'user_id');
    }

    public function izinKaryawan()
    {
        return $this->hasMany(IzinKaryawan::class, 'user_id');
    }

    public function cutiKaryawan()
    {
        return $this->hasMany(CutiKaryawan::class, 'user_id');
    }

    public function sisaCutiTahunan()
    {
        return $this->hasOne(SisaCutiTahunan::class);
    }

    public function potonganBulanan()
    {
        return $this->hasMany(Potongan::class);
    }

    public function riwayatJabatan()
    {
        return $this->hasMany(RiwayatJabatan::class, 'user_id');
    }

    public function sourceFiles()
    {
        return $this->hasMany(SourceFile::class);
    }
}
