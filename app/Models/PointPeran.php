<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointPeran extends Model
{
    /** @use HasFactory<\Database\Factories\PointPeranFactory> */
    use HasFactory;

    protected $table = "point_perans";
    protected $guarded = ['id'];
    public function peransable()
    {
        return $this->morphTo();
    }
}
