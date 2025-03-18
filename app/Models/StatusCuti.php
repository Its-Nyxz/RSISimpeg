<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCuti extends Model
{
    /** @use HasFactory<\Database\Factories\StatusCutiFactory> */
    use HasFactory;

    protected $table = "status_cutis";
    protected $guarded = [
        'id',
    ];
}
