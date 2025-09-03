<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatApproval extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['approve_at'];

    public function cuti() 
    {
        return $this->belongsTo(CutiKaryawan::class, 'cuti_id');
    }

    public function approver() 
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
