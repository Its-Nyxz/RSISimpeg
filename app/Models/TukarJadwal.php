<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TukarJadwal extends Model
{
    /** @use HasFactory<\Database\Factories\TukarJadwalFactory> */
    use HasFactory;

    protected $table = 'tukar_jadwals';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
