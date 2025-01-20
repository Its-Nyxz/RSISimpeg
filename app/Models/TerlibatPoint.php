<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerlibatPoint extends Model
{
    /** @use HasFactory<\Database\Factories\TerlibatPointFactory> */
    use HasFactory;

    protected $table = 'terlibat_points';

    protected $guarded = ['id'];
}
