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

        // if(!$request->user()){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'data'    => 'Dimohon Untuk Login Terlebih Dahulu'
        //     ]);
        // }
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
                'banner_uuid' => $banner[$i]->uuid
            ];
        }

        $vid = [];
        for($i = 0;$i < count($videos); $i++){
            $vid[$i] = [
                'url_video' => $videos[$i]->url_video,
                'video_uuid' => $videos[$i]->uuid
            ];
        }

        $wor = [];
        for($i = 0;$i < count($words); $i++){
            $wor[$i] = [
                'hangeul' => $words[$i]->hangeul,
                'pelafalan' => $words[$i]->pelafalan,
                'kata_uuid' => $words[$i]->uuid
            ];
        }

        $cls = [];
        for($i = 0;$i < count($class); $i++){
            $cl = Models\Teacher::where('id_class',$class[$i]->id)->first();
            
            
            $cls[$i]['nama_kelas'] = $class[$i]->nama;
            if($cl != null){
                $cls[$i]['nama_mentor'] = $cl->user->nama;
            }
            $cls[$i]['url_web'] = $class[$i]->url_web;
            $cls[$i]['url_mobile'] = $class[$i]->url_mobile;
            $cls[$i]['jml_materi'] = $class[$i]->jml_video+$class[$i]->jml_kuis;
            $cls[$i]['kelas_uuid'] = $class[$i]->uuid;
        }

        $th = [];
        for($i = 0;$i < count($theme); $i++){
            $th[$i] = [
                'topik' => $theme[$i]->judul,
                'topik_uuid' => $theme[$i]->uuid
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
        if(!$uuidUser = $request->user()){
            return response()->json([
                'message' => 'Failed',
                'info'    => 'Dimohon Untuk Login Terlebih Dahulu'
            ]);
        }
        $result = [];
        
        // if(!$uuidUser = $request->header('user-uuid')){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'UUID tidak sesuai'
        //     ]);
        // }
        
        $usr = Models\User::where('uuid',$uuidUser->uuid)->first();
        $date = date_format(date_create($usr->tgl_langganan_akhir),"Y/m/d");
       
        $result['stat_pengguna'] = $this->userCheck($uuidUser,$date);

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
                $arr1['jml_materi'] = $class[0]->jml_video+$class[0]->jml_kuis;
                $arr1['class_uuid'] = $class[$j]->uuid;
                $teacher = Models\Teacher::where('id_class',$class[$j]->id)->first();
                if($teacher != null){
                    $test = Models\Teacher::find($teacher->id);
                    $arr1['mentor_nama'] = $test->user->nama;
                    $arr1['mentor_uuid'] = $test->uuid;
                }
                $classes[$j] = $arr1;
            }


            $arr['category'] = $category[$i]->nama;
            $arr['category_detail'] = $category[$i]->deskripsi;
            $arr['category_uuid'] = $category[$i]->uuid;
            $arr['classroom'] = $classes;
            $arr0[$i] = $arr;
        }
        $result['class_list'] = $arr0;
        #$result['class_terdaftar'] = $this->classroomRegistered($uuidUser);

        return response()->json([
            'message' => 'Success',
            'data'    => $result
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
        $arr0 = [];
        for($j = 0;$j < count($class);$j++){
            $arr1 = [];
            $arr1['class_nama'] = $class[$j]->nama;
            $arr1['url_web'] = $class[$j]->url_web;
            $arr1['url_mobile'] = $class[$j]->url_mobile;
            $arr1['jml_materi'] = $class[$j]->jml_video+$class[$j]->jml_kuis;
            $arr1['class_uuid'] = $class[$j]->uuid;
            $teacher = Models\Teacher::where('id_class',$class[$j]->id)->first();
            if($teacher != null){
                $tcr = Models\Teacher::find($teacher->id);
                $usr = Models\User::find($teacher->id_user);
                $arr1['mentor_nama'] = $tcr->user->nama;
                #$arr['mentor-foto'] = $usr->detailMentor[0]->url_foto;
                $arr1['mentor_uuid'] = $tcr->uuid;
            }
            $classes[$j] = $arr1;
        }    

        $arr['category'] = $category[0]->nama;
        $arr['deskripsi'] = $category[0]->deskripsi;
        $arr['category_uuid'] = $category[0]->uuid;
        $arr ['class']= $classes;

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

        if(!$uuidUser = $request->user()->uuid){
            return response()->json([
                'message' => 'Failed',
                'info'    => 'Dimohon Untuk Login Terlebih Dahulu'
            ]);
        }
        // if(!$uuidUser = $request->header('user-uuid')){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'UUID tidak sesuai'
        //     ]);
        // }
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
                    'jml_latihan' => $content_video[0]->jml_latihan,
                    'jml_shadowing' => $content_video[0]->jml_shadowing,
                    'content_video_uuid' => $content_video[0]->uuid
                ];
            }elseif($content[$i]->type == 'quiz'){
                $count_quiz += 1;
                $content_quiz = Models\Quiz::where('id_content',$content[$i]->id)->get();
                $arr1 = [
                    'urutan' => $content[$i]->number,
                    'judul' => $content_quiz[0]->judul,
                    'type' => $content[$i]->type,
                    'jml_soal' => $content_quiz[0]->jml_pertanyaan,
                    'content_quiz_uuid' => $content_quiz[0]->uuid
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
            $arr['mentor_uuid'] = $tcr->uuid;
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

        $teacher = Models\Teacher::where('uuid',$uuid)->get();
        
        if(count($teacher)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        //$teacher = Models\Teacher::where('id_user',$user[0]->id)->get();
        $usr = Models\User::where('id',$teacher[0]->id_user)->first();
        $teacher = Models\Teacher::where('id_user',$usr->id)->get();
        
        //return $usr->detailMentor;
        $arr['mentor_nama'] = $usr->nama;
        $arr['mentor_lama'] = date_format(date_create($usr->created_at),"Y");
        $arr['mentor_bio'] = $usr->detailMentor[0]->bio;
        if($usr->url_foto!=null){
            $arr['mentor_foto'] = $usr->url_foto;
        }
        $arr['mentor_uuid'] = $uuid;
        $cls = [];$co=0;
        //$tc = Models\Teacher::where('id_user',$teacher[0]->id_user)->get();
        for($i=0;$i<count($teacher);$i++){
            if(count($classes = Models\Classes::where('id',$teacher[$i]->id_class)
                ->where('status_tersedia',1)->get())==0){
                    continue;
                }
            $arr0 = [];
            $arr0 = [
                    'class_nama' => $classes[0]->nama,
                    'class_url_web' => $classes[0]->url_web,
                    'class_url_mobile' => $classes[0]->url_mobile,
                    'class_jml_materi' => $classes[0]->jml_video+$classes[0]->jml_kuis,
                    'class_uuid' => $classes[0]->uuid
                ];
            $cls[$co] = $arr0;
            $co++;
        }
        $arr['classroom'] = $cls;

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomRegistered(Request $request){
        $result = [];
        // if(!$uuidUser = $request->header('user-uuid')){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'UUID tidak sesuai'
        //     ]);
        // }

        if(!$uuidUser = $request->user()->uuid){
            return response()->json([
                'message' => 'Failed',
                'info'    => 'Dimohon Untuk Login Terlebih Dahulu'
            ]);
        }
        $uuid = $uuidUser;

        // $user = Models\User::where('uuid',$uuid)->get();
        
        // if(count($user)==0){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'Token tidak sesuai'
        //     ]);
        // }

        $usr = Models\User::find($request->user()->id);

        $classes = $usr->student;
        $arr = [];
        $reg_id = [];
        for($i=0;$i<count($classes);$i++){
            $arr0 = [];
            $class = Models\Classes::find($classes[$i]->id_class);
            $usr['nama'] = [];
            if(count($class->teacher)>0){
                $usr = Models\User::where('id',$class->teacher[0]->id_user)->first();
            }
            $arr0['class_nama'] = $class->nama;
            $arr0['class_url-web'] = $class->url_web;
            $arr0['class_url-mobile'] = $class->url_mobile;
            if($usr->nama != null){
                $arr0['mentor_nama'] = $usr->nama;
            }
            $arr0['class_jml_materi'] = $class->jml_video+$class->jml_kuis;
            $arr0['class_tersedia'] = $class->status_tersedia;
            $arr0['class_prosentase'] = ($class->jml_pengerjaan / ($class->jml_video+$class->jml_kuis)) * 100;
            $arr0['class_uuid'] = $class->uuid;
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
            $usr['nama'] = [];
            if($tcr != null){
                $usr = Models\User::where('id',$tcr->id_user)->first();
            }
            // $arr0 = [
            //     'class_nama' => $class[$i]->nama,
            //     'class_url_web' => $class[$i]->url_web,
            //     'class_url_mobile' => $class[$i]->url_mobile,
            //     'mentor_nama' => $usr->nama,
            //     'class_jml_materi' => $class[$i]->jml_video+$class[$i]->jml_kuis,
            //     #'class_prosentase' => ($classes[$i]->jml_pengerjaan / $class->jml_materi) * 100,
            //     'class_uuid' => $class[$i]->uuid
            // ];
            $arr0['class_nama'] = $class[$i]->nama;
            $arr0['class_url_web'] = $class[$i]->url_web;
            $arr0['class_url_mobile'] = $class[$i]->url_mobile;
            if($usr->nama != null){
                $arr0['mentor_nama'] = $usr->nama;
            }
            $arr0['class_jml_materi'] = $class[$i]->jml_video+$class[$i]->jml_kuis;
            //$arr0['class_tersedia'] = $class->status_tersedia;
            //$arr0['class_prosentase'] = ($class->jml_pengerjaan / ($class->jml_video+$class->jml_kuis)) * 100;
            $arr0['class_uuid'] = $class[$i]->uuid;
            $arr[$i] = $arr0;
        }
        $result['class_tidak_terdaftar'] = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Classroom===========================

    #=========================Classroom-Content===========================
    public function classroomVideoDetail(Request $request){
        #video token
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($video = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $result = [];

        $arr = [
            'judul' => $video[0]->judul,
            'keterangan' => $video[0]->keterangan,
            'url_video' => $video[0]->url_video,
            'video_uuid' => $video[0]->uuid,
        ];

        $content = Models\Content::where('id_class',$video[0]->content->id_class)->get();
        #return $content;
        $cont = [];
        for($i = 0;$i < count($content);$i++){
            $arr1 = [];
            if($content[$i]->type == 'video'){
                $content_video = Models\Video::where('id_content',$content[$i]->id)->get();
                $arr1 = [
                    'urutan' => $content[$i]->number,
                    'judul' => $content_video[0]->judul,
                    'type' => $content[$i]->type,
                    'jml_latihan' => $content_video[0]->jml_latihan,
                    'jml_shadowing' => $content_video[0]->jml_shadowing,
                    'content_video_uuid' => $content_video[0]->uuid
                ];
            }elseif($content[$i]->type == 'quiz'){
                $content_quiz = Models\Quiz::where('id_content',$content[$i]->id)->get();
                $arr1 = [
                    'urutan' => $content[$i]->number,
                    'judul' => $content_quiz[0]->judul,
                    'type' => $content[$i]->type,
                    'jml_soal' => $content_quiz[0]->jml_pertanyaan,
                    'content_quiz_uuid' => $content_quiz[0]->uuid
                ];
            }
            $cont[$i] = $arr1;
        }

        $arr['content'] = $cont;
        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomQuizDetail(Request $request){
        #video token
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($quiz = Models\Quiz::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $result = [];
        //return $quiz[0]->id;
        $exam = Models\Exam::where('id_quiz',$quiz[0]->id)
        ->orderBy('number','ASC')->get();

        
        $arr = [];
        for($i = 0;$i < count($exam);$i++){
            $arr1 = [];
            $question = Models\Question::where('id',$exam[$i]->id_question)
                ->get();
            $option = Models\Option::where('id_question',$question[0]->id)
                ->get();

            if($question[0]->pertanyaan_teks != null){$arr1['pertanyaan_teks'] = $question[0]->pertanyaan_teks;}
            if($question[0]->url_gambar != null){$arr1['url_gambar'] = $question[0]->url_gambar;}
            if($question[0]->url_file != null){$arr1['url_file'] = $question[0]->url_file;}
            $arr1['jawaban'] = $question[0]->jawaban;
            $arr1['question_uuid'] = $question[0]->uuid;
            $arr0 = [];

            for($j = 0;$j < count($option);$j++){
                $arr2 = [];
                if($option[$j]->jawaban_teks != null){$arr2['jawaban_teks'] = $option[$j]->jawaban_teks;}
                if($option[$j]->url_gambar != null){$arr2['url_gambar'] = $option[$j]->url_gambar;}
                if($option[$j]->url_file != null){$arr2['url_file'] = $option[$j]->url_file;}
                $arr2['jawaban_id'] = $option[$j]->jawaban_id;
                $arr2['option_uuid'] = $option[$j]->uuid;
                $arr0[$j] = $arr2;
            }
            $arr1['option'] = $arr0;
            $arr[$i] = $arr1;
        }

        //$arr['content'] = $cont;
        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Classroom-Video-More===========================
    public function classroomVideoMore(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($quiz = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $result = [];

        $task = Models\Task::where('id_video',$quiz[0]->id)->get();
        $shadowing = Models\Shadowing::where('id_video',$quiz[0]->id)->get();

        $result['jml_latihan'] = count($task);
        $result['jml_shadowing'] = count($shadowing);
        $result['video_uuid'] = $uuid;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomVideoTask(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($quiz = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $result = [];

        $task = Models\Task::where('id_video',$quiz[0]->id)->get();

        $arr = [];
        for($i = 0;$i < count($task);$i++){
            $arr1 = [];
            $question = Models\Question::where('id',$task[$i]->id_question)
                ->get();
            $option = Models\Option::where('id_question',$question[0]->id)
                ->get();

            if($question[0]->pertanyaan_teks != null){$arr1['pertanyaan_teks'] = $question[0]->pertanyaan_teks;}
            if($question[0]->url_gambar != null){$arr1['url_gambar'] = $question[0]->url_gambar;}
            if($question[0]->url_file != null){$arr1['url_file'] = $question[0]->url_file;}
            $arr1['jawaban'] = $question[0]->jawaban;
            $arr1['question_uuid'] = $question[0]->uuid;
            $arr0 = [];

            for($j = 0;$j < count($option);$j++){
                $arr2 = [];
                if($option[$j]->jawaban_teks != null){$arr2['jawaban_teks'] = $option[$j]->jawaban_teks;}
                if($option[$j]->url_gambar != null){$arr2['url_gambar'] = $option[$j]->url_gambar;}
                if($option[$j]->url_file != null){$arr2['url_file'] = $option[$j]->url_file;}
                $arr2['jawaban_id'] = $option[$j]->jawaban_id;
                $arr2['option_uuid'] = $option[$j]->uuid;
                $arr0[$j] = $arr2;
            }
            $arr1['option'] = $arr0;
            $arr[$i] = $arr1;
        }

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function classroomVideoShadowing(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($quiz = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $result = [];

        $shadowing = Models\Shadowing::where('id_video',$quiz[0]->id)->get();
        $arr = [];
        for($i=0;$i<count($shadowing);$i++){
            $arr1 = [];
            $word = Models\Words::where('id',$shadowing[$i]->id_word)->first();
            $arr1['hangeul'] =  $word->hangeul;
            $arr1['pelafalan'] =  $word->pelafalan;
            $arr1['url_pengucapan'] =  $word->url_pengucapan;
            $arr1['uuid'] =  $word->uuid;
            $arr[$i] = $arr1;
        }

        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Classroom-Video-More===========================
    #=========================Classroom-Content===========================

    #=========================QnA===========================
    public function qna(Request $request){
        $result = [];
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        #$theme = Models\Theme::orderBy('jml_post','DESC')->limit(3)->get();

        $post = Models\Post::where('stat_post',0)->where('jenis','qna')
        ->orderBy('jml_like','DESC')->get();

        $arr = [];

        // $post = Controllers\Post\PostController::getPost($qna);
        for($i=0;$i<count($post);$i++){
            $arr1 = [];
            $idTheme = $post[$i]->theme->id;
            $videoTheme = Models\VideoTheme::where('id_theme',$idTheme)->first();
            $video = $videoTheme->video;
            $arr1 = [
                'deskripsi' => $post[$i]->deskripsi,
                'nama_pengirim' => $post[$i]->user->nama
            ];
                if($post[$i]->user->foto != null){
                    $arr1 += [
                        'foto_pengirim' => $post[$i]->user->foto,
                    ];
                }
            $arr1 += [
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
                'video_judul' => $video->judul,
                'video_uuid' => $video->uuid,
                'post_uuid' => $post[$i]->uuid
            ];
            $arr[$i] = $arr1;
        }

        #$result['theme'] = $arr;
        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function qnaByVideo(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($video = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $result = [];

        $arr = [];

        $idTheme = $video[0]->videoTheme[0]->id_theme;
        $post = Models\Post::where('id_theme',$idTheme)->where('jenis','qna')
        ->orderBy('created_at','DESC')->get();

        for($i=0;$i<count($post);$i++){
            $arr1 = [];
            $arr1 = [
                'deskripsi' => $post[$i]->deskripsi,
                'nama_pengirim' => $post[$i]->user->nama
            ];
                if($post[$i]->user->foto != null){
                    $arr1 += [
                        'foto_pengirim' => $post[$i]->user->foto,
                    ];
                }
            $arr1 += [
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
                'video_judul' => $video[0]->judul,
                'video_uuid' => $video[0]->uuid,
                'post_uuid' => $post[$i]->uuid
            ];
            $arr[$i] = $arr1;
        }

        #$result['theme'] = $arr;
        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    public function qnaByUser(Request $request){
        $result = [];

        
        if(!$uuidUser = $request->user()->uuid){
            return response()->json([
                'message' => 'Failed',
                'info'    => 'Dimohon Untuk Login Terlebih Dahulu'
            ]);
        }
        
        $uuid = $uuidUser;

        if(count($user = Models\User::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        #$theme = Models\Theme::orderBy('jml_post','DESC')->limit(3)->get();

        $post = Models\Post::where('stat_post',0)
        ->where('id_user',$user[0]->id)
        ->where('jenis','qna')
        ->orderBy('created_at','DESC')->get();

        $arr = [];

        // $post = Controllers\Post\PostController::getPost($qna);
        for($i=0;$i<count($post);$i++){
            $arr1 = [];
            $idTheme = $post[$i]->theme->id;
            $videoTheme = Models\VideoTheme::where('id_theme',$idTheme)->first();
            $video = $videoTheme->video;
            $arr1 = [
                'deskripsi' => $post[$i]->deskripsi,
                'nama_pengirim' => $post[$i]->user->nama
            ];
                if($post[$i]->user->foto != null){
                    $arr1 += [
                        'foto_pengirim' => $post[$i]->user->foto,
                    ];
                }
            $arr1 += [
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
                'video_judul' => $video->judul,
                'video_uuid' => $video->uuid,
                'post_uuid' => $post[$i]->uuid
            ];
            $arr[$i] = $arr1;
        }

        #$result['theme'] = $arr;
        $result = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    public function qnaDetail(Request $request){
        $result = [];

        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($post = Models\Post::where('stat_post',0)
        ->where('uuid',$uuid)
        ->where('jenis','qna')
        ->orderBy('jml_like','DESC')->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        #$theme = Models\Theme::orderBy('jml_post','DESC')->limit(3)->get();

        $arr0 = [];

        // $post = Controllers\Post\PostController::getPost($qna);
        for($i=0;$i<count($post);$i++){
            $arr1 = [];
            $idTheme = $post[$i]->theme->id;
            $videoTheme = Models\VideoTheme::where('id_theme',$idTheme)->first();
            $video = $videoTheme->video;
            $arr1 = [
                'deskripsi' => $post[$i]->deskripsi,
                'nama_pengirim' => $post[$i]->user->nama
            ];
                if($post[$i]->user->foto != null){
                    $arr1 += [
                        'foto_pengirim' => $post[$i]->user->foto,
                    ];
                }
            $arr1 += [
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
                'video_judul' => $video->judul,
                'video_uuid' => $video->uuid,
                'post_uuid' => $post[$i]->uuid
            ];
            $arr0[$i] = $arr1;
        }

        $comment = Models\Comment::where('id_post',$post[0]->id)
            ->orderBy('created_at','DESC')->get();

        $arr = [];        
        for($j=0;$j<count($comment);$j++){
            $arr1 = [];
            $user = Models\User::where('id',$comment[$j]->id_user)
                ->first();
            #return $user;
            $arr1['comment_nama'] = $user->nama;
            $arr1['comment_foto'] = $user->url_foto;
            $arr1['comment_isi'] = $comment[$j]->comment;
            $arr1['comment_tgl'] = $comment[$j]->created_at;
            $arr1['comment_uuid'] = $comment[$j]->uuid;
            $arr[$j] = $arr1;
        }

        $result['posting'] = $arr0;
        $result['comment'] = $arr;

        #$result['theme'] = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    #=========================QnA===========================

    #=========================Forum===========================
    public function forum(Request $request){
        $result = [];
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        $theme = Models\Theme::orderBy('jml_post','DESC')->limit(3)->get();

        $forum = Models\Post::where('stat_post',0)->where('jenis','forum')
        ->orderBy('jml_like','DESC')->get();

        $arr = [];
        for($i=0;$i<count($theme);$i++){
            $arr1=[];
            $arr1 = [
                'urutan' => $i+1,
                'judul' => $theme[$i]->judul,
                'jml_post' => $theme[$i]->jml_post,
                'theme_uuid' => $theme[$i]->uuid
            ];
            $arr[$i] = $arr1;
        }

        $pos = Controllers\Post\PostController::getPost($forum);

        $result['theme'] = $arr;
        $result['forum'] = $pos;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);

    }

    public function forumDetail(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        $result = [];
        if(!$forum = Models\Post::where('jenis','forum')->where('uuid',$uuid)
        ->where('jenis','forum')
        ->where('stat_post','0')->get()){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        if(count($forum)==0){
            return response()->json([
                'message' => 'Success',
                'data'    => $result
            ]);
        }

        $pos = Controllers\Post\PostController::getPost($forum);

        $comment = Models\Comment::where('id_post',$forum[0]->id)
            ->orderBy('created_at','DESC')->get();

        $arr = [];        
        for($j=0;$j<count($comment);$j++){
            $arr1 = [];
            $user = Models\User::where('id',$comment[$j]->id_user)
                ->first();
            #return $user;
            $arr1['comment_nama'] = $user->nama;
            $arr1['comment_foto'] = $user->url_foto;
            $arr1['comment_isi'] = $comment[$j]->comment;
            $arr1['comment_tgl'] = $comment[$j]->created_at;
            $arr1['comment_uuid'] = $comment[$j]->uuid;
            $arr[$j] = $arr1;
        }

        $result['posting'] = $pos;
        $result['comment'] = $arr;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function forumByThemePop(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        $result = [];
        if(count($theme = Models\Theme::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        $result = [];
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        $forum = Models\Post::where('id_theme',$theme[0]->id)
        ->where('jenis','forum')
        ->orderBy('jml_like','DESC')->get();
        $arr1 = [
            'judul' => $theme[0]->judul,
            'theme_uuid' => $theme[0]->uuid
        ];
        $result['theme'] = $arr1;

        $pos = Controllers\Post\PostController::getPost($forum);

        $result['forum'] = $pos;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }

    public function forumByThemeNew(Request $request){
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        $result = [];
        if(count($theme = Models\Theme::where('uuid',$uuid)->get())==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'UUID tidak sesuai'
            ]);
        }
        $result = [];
        #$forum = Models\Post::where('jenis','forum')->where('stat_post','0')->get();
        
        $forum = Models\Post::where('id_theme',$theme[0]->id)
        ->where('jenis','forum')
        ->where('stat_post',0)
        ->orderBy('created_at','DESC')->get();
        $arr1 = [
            'judul' => $theme[0]->judul,
            'theme_uuid' => $theme[0]->uuid
        ];
        $result['theme'] = $arr1;

        $pos = Controllers\Post\PostController::getPost($forum);

        $result['forum'] = $pos;

        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    public function forumByUser(Request $request){
        $result = [];
        // if(!$uuidUser = $request->header('user-uuid')){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'UUID tidak sesuai'
        //     ]);
        // }

        if(!$uuidUser = $request->user()->uuid){
            return response()->json([
                'message' => 'Failed',
                'info'    => 'Dimohon Untuk Login Terlebih Dahulu'
            ]);
        }
        
        $uuid = $uuidUser;

        $user = Models\User::where('uuid',$uuid)->get();
        
        if(count($user)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $forum = Models\Post::where('id_user',$user[0]->id)
        ->where('jenis','forum')
        ->where('stat_post',0)
        ->orderBy('created_at','DESC')->get();

        $pos = Controllers\Post\PostController::getPost($forum);

        $result = $pos;
        return response()->json([
            'message' => 'Success',
            'data'    => $result
        ]);
    }
    #=========================Forum===========================
    #=========================QnA===========================
    #=========================QnA===========================
    #=========================Testimoni===========================
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
    #=========================Testimoni===========================
}
