<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PJ extends Model
{
    protected $table = 'pjs';
    protected $fillable = ['user_id', 'assigned_at', 'is_pj'];
}
