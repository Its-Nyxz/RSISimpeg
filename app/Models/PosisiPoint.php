<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosisiPoint extends Model
{
    /** @use HasFactory<\Database\Factories\PosisiPointFactory> */
    use HasFactory;
    protected $table = 'posisi_points';

    protected $guarded = ['id'];
}
