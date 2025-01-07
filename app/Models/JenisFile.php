<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisFile extends Model
{
    /** @use HasFactory<\Database\Factories\JenisFileFactory> */
    use HasFactory;

    protected $table = "jenis_files";
    protected $guarded = ['id'];

    public function files()
    {
        return $this->morphMany(SourceFile::class, 'fileable');
    }
}
