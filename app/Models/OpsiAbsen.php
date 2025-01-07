<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiAbsen extends Model
{
    /** @use HasFactory<\Database\Factories\OpsiAbsenFactory> */
    use HasFactory;

    protected $table = "opsi_absens";
    protected $guarded = ['id'];

}
