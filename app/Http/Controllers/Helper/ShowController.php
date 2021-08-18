<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use App\Http\Controllers;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    #=========================Home===========================

    public function home(Request $request){
        $result = [];

        $tglSekarang = date('Y/m/d');

        $banner = Models\Banner::all();
        $videos = Models\Videos::where('jadwal',$tglSekarang)->get();
        $words = Models\Words::where('jadwal',$tglSekarang)->get();
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
                'uuid_banner' => $banner[$i]->uuid
            ];
        }

        $vid = [];
        for($i = 0;$i < count($videos); $i++){
            $vid[$i] = [
                'url_video' => $videos[$i]->url_video,
                'uuid_video' => $videos[$i]->uuid
            ];
        }

        $wor = [];
        for($i = 0;$i < count($words); $i++){
            $wor[$i] = [
                'hangeul' => $words[$i]->hangeul,
                'pelafalan' => $words[$i]->pelafalan,
                'uuid_kata' => $words[$i]->uuid
            ];
        }

        $cls = [];
        for($i = 0;$i < count($class); $i++){
            $cl = Models\Teacher::where('id_class',$class[$i]->id)->first();
            $cls[$i] = [
                'nama_kelas' => $class[$i]->nama,
                'nama_mentor' => $cl->user->nama,
                'url_web' => $class[$i]->url_web,
                'url_mobile' => $class[$i]->url_mobile,
                'jml_materi' => $class[$i]->jml_materi,
                'uuid_kelas' => $class[$i]->uuid
            ];
        }

        $th = [];
        for($i = 0;$i < count($theme); $i++){
            $th[$i] = [
                'topik' => $theme[$i]->judul,
                'uuid_topik' => $theme[$i]->uuid
            ];
        }

        $pos = Controllers\Post\PostController::getPost($post);

        $result['banner'] = $ban;
        $result['video'] = $vid;
        $result['word'] = $wor;
        $result['class'] = $cls;
        $result['theme'] = $th;
        $result['post'] = $pos;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function banner(Request $request){
        $banner = Controllers\Banner\BannerController::detailData($request->token);
        return $banner;
    }

    public function word(Request $request){
        $word = Controllers\Banner\WordController::detailDataWord($request->token);
        return $word;
    }

    public function video(Request $request){
        $video = Controllers\Banner\VideoController::detailDataVideo($request->token);
        return $video;
    }

    public function search(Request $request){
        if(!$key=$request->keyword){
            return response()->json(['message' => 'Failed',
            'info'=>"Keyword Tidak Sesuai"]);
        }
        $result  = [];

        $post = Models\Post::where('stat_post',0)
        ->where('jenis','forum')
        ->where('judul','like','%'.$key.'%')
        ->get();

        $qna = Models\Post::where('stat_post',0)
        ->where('jenis','qna')
        ->where('judul','like','%'.$key.'%')
        ->get();

        $pos = Controllers\Post\PostController::getPost($post);
        $qn = Controllers\Post\PostController::getPost($qna);

        $result = [
            'jml_forum' => count($pos),
            'forum' => $pos,
            'jml_qna' => count($qn),
            'qna' => $qn
        ];

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    #=========================Home===========================
    #=========================Classroom===========================

    private function userCheck($uuid,$date){
        $stUsr = "Non-Member";
        if($date >= date('Y/m/d')){
            $stUsr = "Member";
        }

        return $stUsr;
    }

    public function classroom(Request $request){
        $result = [];
        if(!$uuidUser = $request->header('user_uuid')){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $usr = Models\User::where('uuid',$uuidUser)->first();
        // $date = date_format(date_create($usr->tgl_langganan_akhir),"Y/m/d");
       
        // $result['stat_pengguna'] = $this->userCheck($uuidUser,$date);

        $category = Models\ClassesCategory::all();
        $arr0 = [];
        for($i = 0;$i < count($category);$i++){
            $arr = [];
            $class = Models\Classes::where('id_class_category',$category[$i]->id)
                ->where('status_tersedia',1)->limit(6)->get();
            $classes = [];
            for($j = 0;$j < count($class);$j++){
                $arr1 = [];
                $arr1['class_nama'] = $class[$j]->nama;
                $arr1['url_web'] = $class[$j]->url_web;
                $arr1['url_mobile'] = $class[$j]->url_mobile;
                $arr1['jml_materi'] = $class[$j]->jml_materi;
                $arr1['class_uuid'] = $class[$j]->uuid;
                $teacher = Models\Teacher::where('id_class',$class[$j]->id)->first();
                if($teacher != null){
                    $test = Models\Teacher::find($teacher->id);
                    $arr1['mentor_nama'] = $test->user->nama;
                    $arr1['mentor_uuid'] = $test->user->uuid;
                }
                $classes[$j] = $arr1;
            }


            $arr['category'] = $category[$i]->nama;
            $arr['category_uuid'] = $category[$i]->uuid;
            $arr['classroom'] = $classes;
            $arr0[$i] = $arr;
        }
        $result['class_list'] = $arr0;
        $result['class_terdaftar'] = $this->classroomRegistered($uuidUser);

        return response()->json([
            'message' => 'Success',
            'data'    => $uuidUser
        ]);
    }

    public function classroomByCategory(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $category = Models\ClassesCategory::where('uuid',$uuid)->get();
        if(count($category)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $arr = [];
        $class = Models\Classes::where('id_class_category',$category[0]->id)
            ->where('status_tersedia',1)->get();
        $classes = [];
        for($j = 0;$j < count($class);$j++){
            $arr1 = [];
            $arr1['class_nama'] = $class[$j]->nama;
            $arr1['url_web'] = $class[$j]->url_web;
            $arr1['url_mobile'] = $class[$j]->url_mobile;
            $arr1['jml_materi'] = $class[$j]->jml_materi;
            $arr1['class_uuid'] = $class[$j]->uuid;
            $teacher = Models\Teacher::where('id_class',$class[$j]->id)->first();
            if($teacher != null){
                $tcr = Models\Teacher::find($teacher->id);
                $usr = Models\User::find($teacher->id_user);
                $arr['mentor_nama'] = $tcr->user->nama;
                #$arr['mentor-foto'] = $usr->detailMentor[0]->url_foto;
                $arr['mentor_uuid'] = $tcr->user->uuid;
            }
            $classes[$j] = $arr1;
        }    

        $arr['category'] = $category[0]->nama;
        $arr['category_uuid'] = $category[0]->uuid;
        $arr['classroom'] = $classes;

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    //Masih Plain belum ada validasi member
    public function classroomDetail(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $uuidUser = $request->header('user_uuid');
        $usr = Models\User::where('uuid',$uuidUser)->first();
        $date = date_format(date_create($usr->tgl_langganan_akhir),"Y/m/d");
       

        $classes = Models\Classes::where('uuid',$uuid)
            ->where('status_tersedia',1)->get();
        
        // if(!$user = Models\User::where('uuid',$uuid)->get()){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'User Token tidak sesuai'
        //     ]);
        // }
        if(count($classes)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $arr = [];
        $cont = [];
        $content = Models\Content::where('id_class',$classes[0]->id)->orderBy('number', 'ASC')->get();
        $count_vid = 0;
        $count_quiz = 0;
        $arr['stat_pengguna'] = $this->userCheck($uuidUser,$date);

        for($i = 0;$i < count($content);$i++){
            $arr1 = [];
            if($content[$i]->type == 'video'){
                $count_vid += 1;
                $content_video = Models\Video::where('id_content',$content[$i]->id)->get();
                $arr1 = [
                    'urutan' => $content[$i]->number,
                    'judul' => $content_video[0]->judul,
                    'type' => $content[$i]->type,
                    'jml_latihan' => $content_video[0]->jml_pertanyaan,
                    'jml_shadowing' => $content_video[0]->jml_shadowing,
                    'content_uuid' => $content_video[0]->uuid
                ];
            }elseif($content[$i]->type == 'quiz'){
                $count_quiz += 1;
                $content_quiz = Models\Quiz::where('id_content',$content[$i]->id)->get();
                $arr1 = [
                    'urutan' => $content[$i]->number,
                    'judul' => $content_quiz[0]->judul,
                    'type' => $content[$i]->type,
                    'jml_soal' => $content_quiz[0]->jml_pertanyaan,
                    'content_uuid' => $content_quiz[0]->uuid
                ];
            }
            $cont[$i] = $arr1;
        }

        $arr['class_nama'] = $classes[0]->nama;
        $arr['class_desc'] = $classes[0]->deskripsi;
        $arr['url_web'] = $classes[0]->url_web;
        $arr['url_mobile'] = $classes[0]->url_mobile;
        $arr['class_uuid'] = $classes[0]->uuid;
        $arr['jml_video'] = $count_vid;
        $arr['jml_quiz'] = $count_quiz;

        $teacher = Models\Teacher::where('id_class',$classes[0]->id)->first();
        if($teacher != null){
            $tcr = Models\Teacher::find($teacher->id);
            $usr = Models\User::find($teacher->id_user);
            $arr['mentor_nama'] = $tcr->user->nama;
            $arr['mentor_foto'] = $usr->detailMentor[0]->url_foto;
            $arr['mentor_uuid'] = $tcr->user->uuid;
        }
        $arr['content'] = $cont;

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomMentorDetail(Request $request){
        $result = [];
        #uuid mentor
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $uuidUser = $request->user_uuid;

        $user = Models\User::where('uuid',$uuid)->get();
        
        if(count($user)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $teacher = Models\Teacher::where('id_user',$user[0]->id)->get();
        $usr = Models\User::find($user[0]->id);

        $arr['mentor_nama'] = $user[0]->nama;
        $arr['mentor_bio'] = $usr->detailMentor[0]->bio;
        $arr['mentor_foto'] = $usr->detailMentor[0]->url_foto;
        $arr['mentor_uuid'] = $user[0]->uuid;
        
        $cls = [];
        for($h=0;$h<count($teacher);$h++){
            $classes = Models\Classes::where('id',$teacher[$h]->id_class)
                ->where('status_tersedia',1)->get();
            $arr0 = [];
            for($i=0;$i<count($classes);$i++){
                $arr1 = [
                    'class_nama' => $classes[$i]->nama,
                    'class_url_web' => $classes[$i]->url_web,
                    'class_url_mobile' => $classes[$i]->url_mobile,
                    'class_jml_materi' => $classes[$i]->jml_materi,
                    'class_uuid' => $classes[$i]->uuid
                ];
                $arr0[$i] = $arr1;
            }
            $cls[$h] = $arr0[0];
        }
        $arr['classroom'] = $cls;

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomRegistered($token){
        $result = [];
        $uuid = $token;
        $uuidUser = $uuid;

        $user = Models\User::where('uuid',$uuid)->get();
        
        if(count($user)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $usr = Models\User::find($user[0]->id);

        $classes = $usr->student;
        $arr = [];
        $reg_id = [];
        for($i=0;$i<count($classes);$i++){
            $arr0 = [];
            $class = Models\Classes::find($classes[$i]->id_class);
            $usr = Models\User::find($class->teacher[0]->id_user);
            $arr0 = [
                'class_nama' => $class->nama,
                'class_url-web' => $class->url_web,
                'class_url-mobile' => $class->url_mobile,
                'mentor_nama' => $usr->nama,
                'class_jml_materi' => $class->jml_materi,
                'class_prosentase' => ($classes[$i]->jml_pengerjaan / $class->jml_materi) * 100,
                'class_uuid' => $class->uuid
            ];
            $arr[$i] = $arr0;
            $reg_id[$i] = $classes[$i]->id_class;
        }
        $result['class_terdaftar'] = $arr;

        $arr = [];
        $class = Models\Classes::whereNotIn('id',$reg_id)
            ->where('status_tersedia',1)->get();
        for($i=0;$i<count($class);$i++){
            $arr0 = [];
            $tcr = Models\Teacher::where('id_class',$class[$i]->id)->first();
            $usr = Models\User::find($tcr->id_user);
            $arr0 = [
                'class_nama' => $class[$i]->nama,
                'class_url_web' => $class[$i]->url_web,
                'class_url_mobile' => $class[$i]->url_mobile,
                'mentor_nama' => $usr->nama,
                'class_jml_materi' => $class[$i]->jml_materi,
                #'class_prosentase' => ($classes[$i]->jml_pengerjaan / $class->jml_materi) * 100,
                'class_uuid' => $class[$i]->uuid
            ];
            $arr[$i] = $arr0;
        }
        $result['class_tidak_terdaftar'] = $arr;

        return  $result;
    }
    #=========================Classroom===========================

    #=========================Video===========================
    public function classroomVideoDetail(Request $request){
        #video token
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $result = [];

        $class = Models\Classes::where('uuid',$uuid)->first();

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Video===========================

    #=========================Classroom===========================
    public function testimoni(Request $request){
        $result = [];

        $testimoni = Models\Testimoni::limit(10)->get();
        for($i = 0;$i < count($testimoni);$i++){
            $arr = [];

            $user = Models\Testimoni::find($testimoni[$i]->id);
            $arr['nama'] = $user->user->nama;
            $arr['kelas'] = $user->classes->nama;
            $arr['testimoni'] = $testimoni[$i]->testimoni;
            $result[$i] = $arr;
        }


        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Classroom===========================
}
