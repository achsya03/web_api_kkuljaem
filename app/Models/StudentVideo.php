<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentVideo extends Model
{
    use HasFactory;
    protected $table = 'student_video';
    public $timestamps=false;
    protected $fillable = [
        'id_student',
        'id_content_video',
        'register_date',
        'uuid'
    ];
}
