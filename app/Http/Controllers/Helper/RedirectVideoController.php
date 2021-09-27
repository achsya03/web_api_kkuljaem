<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectVideoController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public static function generateSession($uuid_user){
        if(!session_id()) session_start();
        $_SESSION[$uuid_user] = bin2hex(random_bytes(32));
    }

    public static function getVideo(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $uuid_user = $request->user()->uuid;
        if($token != $_SESSION[$uuid_user]){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }else{
            unset($_SESSION[$uuid_user]);
            $ctype = "video/mp4";
            header("Content-Type: ".$ctype);
            $id = "12kB1Y3UxFl5BeKr1FlpXqXl-6avGoNAf";
            $file_path_name = 'https://drive.google.com/uc?export=preview&id='.$id;
            $ops = array(
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT        => 120
            );
            $ch = curl_init($file_path_name);
            curl_setopt_array($ch, $ops);
            $out = curl_exec($ch);
            curl_close($ch);
            $header['content'] = $out;
            return $header;
        }
    }
}
