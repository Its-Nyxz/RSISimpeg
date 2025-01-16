<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointJabatan extends Model
{
    /** @use HasFactory<\Database\Factories\PointJabatanFactory> */
    use HasFactory;

    protected $table = "point_jabatans";

    protected $guarded = ['id'];

    public function pointable()
    {
        return $this->morphTo();
    }
}
