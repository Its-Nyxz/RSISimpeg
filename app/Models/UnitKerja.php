<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    /** @use HasFactory<\Database\Factories\UnitKerjaFactory> */
    use HasFactory;

    protected $table = "unit_kerjas";
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id');
    }
}
