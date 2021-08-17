<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 'video';
    public $timestamps=false;
    protected $fillable = [
        'judul',
        'keterangan',
        'jml_latihan',
        'jml_shadowing',
        'jml_pertanyaan',
        'url_video',
        'uuid'
    ];
}
