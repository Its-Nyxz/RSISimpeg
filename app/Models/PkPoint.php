<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkPoint extends Model
{
    /** @use HasFactory<\Database\Factories\PkPointFactory> */
    use HasFactory;

    protected $table = 'pk_points';

    protected $guarded = ['id'];
}
