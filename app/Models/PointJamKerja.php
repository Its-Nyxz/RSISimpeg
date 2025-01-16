<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointJamKerja extends Model
{
    /** @use HasFactory<\Database\Factories\PointJamKerjaFactory> */
    use HasFactory;

    protected $table = "point_jam_kerjas";
    protected $guarded = ['id'];
    
}
