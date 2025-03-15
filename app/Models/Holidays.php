<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    /** @use HasFactory<\Database\Factories\HolidaysFactory> */
    use HasFactory;

    protected $table = "holidays";
    protected $guarded = [
        'id',
    ];

}
