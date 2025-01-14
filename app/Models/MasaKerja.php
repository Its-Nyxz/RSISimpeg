<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasaKerja extends Model
{
    /** @use HasFactory<\Database\Factories\MasaKerjaFactory> */
    use HasFactory;
    protected $table = "masa_kerjas";
    protected $guarded = ['id'];
}
