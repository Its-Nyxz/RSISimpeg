<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilaipph extends Model
{
    /** @use HasFactory<\Database\Factories\NilaipphFactory> */
    use HasFactory;

    protected $table = "nilaipphs";
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategoripph::class, 'kategori_id');
    }
}
