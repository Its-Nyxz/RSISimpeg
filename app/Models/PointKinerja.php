<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointKinerja extends Model
{
    /** @use HasFactory<\Database\Factories\PointKinerjaFactory> */
    use HasFactory;

    protected $table = "point_kinerjas";
    protected $guarded = ['id'];

}
