<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanPotonganSetting extends Model
{
    use HasFactory;

    protected $table = "jabatan_potongan_settings";
    protected $guarded = ['id'];
}
