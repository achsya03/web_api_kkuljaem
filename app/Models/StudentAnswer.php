<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;
    protected $table = 'student_answer';
    public $timestamps=false;
    protected $fillable = [
        'id_student',
        'id_question',
        'jawaban',
        'uuid'
    ];
    public function question()
    {
        return $this->belongsTo(Question::class,'id_question','id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class,'id_student','id');
    }
}
