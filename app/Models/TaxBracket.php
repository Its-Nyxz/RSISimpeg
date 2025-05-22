<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    /** @use HasFactory<\Database\Factories\TaxBracketsFactory> */
    use HasFactory;

    protected $table = "tax_brackets";
    protected $guarded = [
        'id',
    ];

    public function kategoripph()
    {
        return $this->belongsTo(Kategoripph::class, 'kategoripph_id');
    }
}
