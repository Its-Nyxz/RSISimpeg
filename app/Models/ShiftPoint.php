<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPoint extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftPointFactory> */
    use HasFactory;

    protected $table = 'shift_points';

    protected $guarded = ['id'];
}
