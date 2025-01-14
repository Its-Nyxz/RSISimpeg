<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelUnit extends Model
{
    /** @use HasFactory<\Database\Factories\LevelUnitFactory> */
    use HasFactory;

    protected $table = "level_units";
    protected $guarded = ['id'];

}
