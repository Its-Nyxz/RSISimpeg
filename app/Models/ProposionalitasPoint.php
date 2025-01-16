<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposionalitasPoint extends Model
{
    /** @use HasFactory<\Database\Factories\ProposionalitasPointFactory> */
    use HasFactory;
    protected $table = "proposionalitas_points";
    protected $guarded = ['id'];

    public function proposable()
    {
        return $this->morphTo();
    }
}
