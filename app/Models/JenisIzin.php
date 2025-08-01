<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisIzin extends Model
{
    use HasFactory;

    protected $table = "jenis_izins";
    protected $guarded = ['id'];
}
