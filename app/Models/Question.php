<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable = [
        'pertanyaan_teks',
        'url_gambar',
        'gambar_id',
        'url_file',
        'file_id',
        'jawaban',
        'jenis_jawaban',
        'uuid',
    ];
}
