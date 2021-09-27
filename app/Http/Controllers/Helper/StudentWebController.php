<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class StudentWebController extends Controller
{
    public function homeWeb(Request $request){
        $result = [];

        $tglSekarang = date('Y/m/d');

        $banner = Models\Banner::all();
        $videos = Models\Videos::where('jadwal',$tglSekarang)->get();
        if(count($videos)==0){
            $videos = Models\Videos::where('jadwal','2001-01-01')->get();
        }
        $words = Models\Words::where('jadwal',$tglSekarang)->get();
        if(count($words)==0){
            $words = Models\Words::where('jadwal','2001-01-01')->get();
        }
        $class = Models\Classes::orderBy('created_at','DESC')
            ->where('status_tersedia',1)->limit(6)->get();
        $theme = Models\Theme::orderBy('jml_post','DESC')->limit(6)->get();
        $post = Models\Post::where('stat_post',0)->where('jenis','forum')
        ->orderBy('jml_like','DESC')->limit(10)->get();

        $ban = [];
        for($i = 0;$i < count($banner); $i++){
            $ban[$i] = [
                'judul_banner' => $banner[$i]->judul_banner,
                'url_web' => $banner[$i]->url_web,
                'url_mobile' => $banner[$i]->url_mobile,
                'deskripsi' => $banner[$i]->deskripsi,
                'label' => $banner[$i]->label,
                'link' => $banner[$i]->link,
                'banner_uuid' => $banner[$i]->uuid
            ];
        }

        $token = bin2hex(random_bytes(32));
        Session::put($token, $token);
        Session::save();
        
        //return Session::get('aa');
        //$video_session = RedirectVideoController::generateSession($token);
        //return Session::get('uuid_user');
        $vid = [];
        for($i = 0;$i < count($videos); $i++){
            $vid[$i] = [
                'url_video' => env('APP_URL').'/video/redirect?token='.$videos[$i]->uuid.'&id='.$token,
                //'url_video_web' => $videos[$i]->url_video_web,
                'video_uuid' => $videos[$i]->uuid
            ];
        }

        $wor = [];
        for($i = 0;$i < count($words); $i++){
            $wor[$i] = [
                'hangeul' => $words[$i]->hangeul,
                'pelafalan' => $words[$i]->pelafalan,
                'penjelasan' => $words[$i]->penjelasan,
                'url_pengucapan' => $words[$i]->url_pengucapan,
                'kata_uuid' => $words[$i]->uuid
            ];
        }

        $cls = [];
        for($i = 0;$i < count($class); $i++){
            $cl = Models\Teacher::where('id_class',$class[$i]->id)->first();
            
            
            $cls[$i]['nama_kelas'] = $class[$i]->nama;
            $cls[$i]['nama_deskripsi'] = $class[$i]->deskripsi;
            if($cl != null){
                $cls[$i]['nama_mentor'] = $cl->user->nama;
            }
            $cls[$i]['url_web'] = $class[$i]->url_web;
            $cls[$i]['url_mobile'] = $class[$i]->url_mobile;
            $cls[$i]['jml_materi'] = $class[$i]->jml_video+$class[$i]->jml_kuis;
            $cls[$i]['kelas_uuid'] = $class[$i]->uuid;
        }

        $result['banner'] = $ban;
        $result['video'] = $vid;
        $result['word'] = $wor;
        $result['class'] = $cls;

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'data'    => $result
        ]);
    }
}
