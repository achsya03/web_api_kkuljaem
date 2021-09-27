<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class RedirectVideoController extends Controller
{


    public static function generateSession($uuid_user){
        if(!session_id()) session_start();
        $_SESSION[$uuid_user] = $uuid_user;
    }

    public static function getVideo(Request $request){
        //return Session::get('aa');
        if(!$uuid_video = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai token'
            ]);
        }
        $uuid_user = $request->id;
        if($uuid_user != Session::get($uuid_user)){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai session'
            ]);
        }else{
            if(!$video = Models\Videos::where('uuid',$uuid_video)->first()){
                return response()->json([
                    'message' => 'Failed',
                    'error' => 'Token tidak sesuai video'
                ]);
            }
            //unset($_SESSION[$uuid_user]);Session::forget('key');
            //Session::forget($uuid_user);
            $ctype = "video/mp4";
            header("Content-Type: ".$ctype);
            $id = "12kB1Y3UxFl5BeKr1FlpXqXl-6avGoNAf";
            //$file_path_name = 'https://drive.google.com/uc?export=preview&id='.$id;
            $file_path_name = $video->url_video;
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
            
            echo $header;
        }
    }
}
