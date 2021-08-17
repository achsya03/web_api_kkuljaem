<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    use HasFactory;
    protected $table = 'student_quiz';
    public $timestamps=false;
    protected $fillable = [
        'id_student',
        'id_content_quiz',
        'register_date',
        'answer',
        'uuid'
    ];
}
