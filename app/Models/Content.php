<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $table = 'content';
    public $timestamps=false;
    protected $fillable = [
        'id_class',
        'number',
        'uuid'
    ];
}
