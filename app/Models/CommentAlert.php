<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAlert extends Model
{
    use HasFactory;
    protected $table = 'comment_alert';
    public $timestamps=false;
    protected $fillable = [
        'id_user',
        'id_comment',
        'komentar',
        'alert_status',
        'uuid',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class,'id_post','id');
    }
}
