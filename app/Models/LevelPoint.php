<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LevelUnit;

class LevelPoint extends Model
{
    /** @use HasFactory<\Database\Factories\LevelPointFactory> */
    use HasFactory;

    protected $table = "level_points";
    protected $guarded = ['id'];

    public function levelunit()
    {
        return $this->hasMany(LevelUnit::class, 'level_id');
    }
}
