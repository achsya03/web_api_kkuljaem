<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payment';
    public $timestamps=false;
    protected $fillable = [
        'id_subs',
        'stat_pembayaran',
        'snap_token',
        'tgl_pembayaran',
        'uuid'
    ];
    public function subs()
    {
        return $this->belongsTo(Subs::class,'id_subs','id');
    }
}
