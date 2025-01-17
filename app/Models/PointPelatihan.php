<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointPelatihan extends Model
{
    /** @use HasFactory<\Database\Factories\PointPelatihanFactory> */
    use HasFactory;
    protected $table = 'point_pelatihans';

    protected $guarded = ['id'];
}
