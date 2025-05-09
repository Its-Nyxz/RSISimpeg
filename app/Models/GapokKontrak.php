<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GapokKontrak extends Model
{
    /** @use HasFactory<\Database\Factories\GapokKontrakFactory> */
    use HasFactory;

    protected $table = "gapok_kontraks";
    protected $guarded = ['id'];

    public function kategoriJabatan()
    {
        return $this->belongsTo(KategoriJabatan::class);
    }
}
