<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoripph extends Model
{
    /** @use HasFactory<\Database\Factories\KategoripphFactory> */
    use HasFactory;
    protected $table = "kategoripphs";
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Kategoripph::class, 'parent_id');
    }

    // Relasi children
    public function children()
    {
        return $this->hasMany(Kategoripph::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(users::class, 'kategori_id');
    }

    public function taxBrackets()
    {
        return $this->hasMany(TaxBracket::class);
    }
}
