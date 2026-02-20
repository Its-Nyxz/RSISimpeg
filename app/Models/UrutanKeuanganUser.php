<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrutanKeuanganUser extends Model
{
    //
    // app/Models/UrutanKeuanganUser.php

    protected $table = 'urutan_keuangan_user';
    protected $fillable = ['user_id', 'urutan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
