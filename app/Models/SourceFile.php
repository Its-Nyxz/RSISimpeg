<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceFile extends Model
{
    /** @use HasFactory<\Database\Factories\SourceFileFactory> */
    use HasFactory;


    protected $table = "source_files";
    protected $guarded = ['id'];
    public function fileable()
    {
        return $this->morphTo();
    }

    public function jenisFile()
    {
        return $this->belongsTo(JenisFile::class, 'jenis_file_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
