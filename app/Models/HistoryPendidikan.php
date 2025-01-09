<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPendidikan extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryPendidikanFactory> */
    use HasFactory;

    protected $table = "history_pendidikans";
    protected $guarded = [
        'id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
