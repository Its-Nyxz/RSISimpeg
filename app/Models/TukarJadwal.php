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

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function statusCuti()
    {
        return $this->belongsTo(StatusCuti::class);
    }
}
