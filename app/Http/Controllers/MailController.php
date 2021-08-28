<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailController extends Controller
{
    public function __invoke(Request $request)
    {
    }
    public static function sendEmail($info_penggunas,$stat){
            $judul="";
            $path="";
            if($stat=="verify"){
                $judul="Thank you for signing up with Kkuljaem - Please verify your email address";
                $path="verify-mail";
            }elseif ($stat=="forgot-pass") {
                $judul="Reset Password";
                $path="change-password";
            }
            $info_pengguna=[
                'nama' => "There",
                'email' => $info_penggunas['email'],
                'url' => env('APP_URL', "https://kkuljaem.xyz").env('APP_PORT', "").'/api/auth/'.$path.'?token='.$info_penggunas['web_token'],
            ];

            try{
                $kirim_email=Mail::to($info_pengguna['email'])
                ->send(new SendMail($judul,$info_pengguna,$stat));
            }catch(\Exception $e) {
                return ['message' => 'Send Again'];
            }
            if(empty($kirim_email)){
                return ['message'
                => 'Mail Sended'];
            }else{
                return ['message' => 'Failed'];
            }
    }
}
