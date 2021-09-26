<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Cloudinary;

class ValidationController extends Controller
{
    public $rules = [];
    public $messages = [];
    public $data = [];
    private $pos = -1;

    public function __construct($pos)
    {
        $this->pos = $pos;
        if($pos=='authUser'){
            $this->authUser();
            $this->data = [
                'web_token' => $this->randomToken(144,Models\User::class),
                'uuid'      => $this->getUuid(Models\User::class)
            ];
        }elseif($pos == 'changePassUser'){
            $this->changePassUser();
            $this->data = [
                'web_token' => $this->randomToken(144,Models\User::class)
            ];
        }elseif($pos == 'verifyUser'){
            $this->verifyUser();
            $this->data = [
                'web_token' => $this->randomToken(144,Models\User::class)
            ];
        }elseif($pos == 'forgetPassUser'){
            $this->forgetPassUser();
            $this->data = [
                'web_token' => $this->randomToken(144,Models\User::class)
            ];
        }elseif($pos == 'login'){
            $this->login();
        }elseif($pos == 'banner'){
            $this->banner();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Banner::class)
            ];
        }elseif($pos == 'bannerWord'){
            $this->bannerWord();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Words::class)
            ];
        }elseif($pos == 'bannerVideo'){
            $this->bannerVideo();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Videos::class)
            ];
        }elseif($pos == 'classCategory'){
            $this->classCategory();
            $this->data = [
                'uuid'      => $this->getUuid(Models\ClassesCategory::class)
            ];
        }elseif($pos=='classes'){
            $this->classes();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Classes::class)
            ];
        }elseif($pos=='content'){
            $this->content();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Content::class)
            ];
        }elseif($pos=='contentQuiz'){
            $this->contentQuiz();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Quiz::class)
            ];
        }elseif($pos=='contentVideo'){
            $this->contentVideo();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Video::class)
            ];
        }elseif($pos=='option'){
            $this->option();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Option::class)
            ];
        }elseif($pos=='question'){
            $this->question();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Question::class)
            ];
        }elseif($pos=='task'){
            $this->task();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Task::class)
            ];
        }elseif($pos=='exam'){
            $this->exam();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Exam::class)
            ];
        }elseif($pos=='shadowing'){
            $this->shadowing();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Shadowing::class)
            ];
        }elseif($pos=='word'){
            $this->word();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Words::class)
            ];
        }elseif($pos=='teacher'){
            $this->teacher();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Teacher::class)
            ];
        }elseif($pos=='theme'){
            //$this->theme();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Theme::class)
            ];
        }elseif($pos=='videoTheme'){
            //$this->videoTheme();
            $this->data = [
                'uuid'      => $this->getUuid(Models\VideoTheme::class)
            ];
        }elseif($pos=='post'){
            $this->post();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Post::class)
            ];
        }elseif($pos=='postLike'){
            //$this->post();
            $this->data = [
                'uuid'      => $this->getUuid(Models\PostLike::class)
            ];
        }elseif($pos=='comment'){
            $this->comment();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Comment::class)
            ];
        }elseif($pos=='postAlert'){
            $this->postAlert();
            $this->data = [
                'uuid'      => $this->getUuid(Models\PostAlert::class)
            ];
        }elseif($pos=='commentAlert'){
            $this->commentAlert();
            $this->data = [
                'uuid'      => $this->getUuid(Models\CommentAlert::class)
            ];
        }elseif($pos=='postImage'){
            //$this->commentAlert();
            $this->data = [
                'uuid'      => $this->getUuid(Models\PostImage::class)
            ];
        }elseif($pos=='student'){
            //$this->commentAlert();
            $this->data = [
                'uuid'      => $this->getUuid(Models\Student::class)
            ];
        }elseif($pos=='studentVideo'){
            //$this->commentAlert();
            $this->data = [
                'uuid'      => $this->getUuid(Models\StudentVideo::class)
            ];
        }elseif($pos=='studentQuiz'){
            $this->studentQuiz();
            $this->data = [
                'uuid'      => $this->getUuid(Models\StudentQuiz::class)
            ];
        }elseif($pos=='studentAnswer'){
            $this->studentAnswer();
            $this->data = [
                'uuid'      => $this->getUuid(Models\StudentAnswer::class)
            ];
        }
        
        // elseif($pos=='testimoni'){
        //     $this->testimoni();
        //     $this->data = [
        //         'uuid'      => $this->getUuid(Models\Testimoni::class)
        //     ];
        // }
    }

    #============================================================

    private function authUser(){
        $this->rules = [
            'email'                          => 'required|email|unique:users,email',
            'password'                       => 'required|confirmed',
            'password_confirmation'          => 'required'
        ];
    
        $this->messages = [
            'email.required'                  => 'Email wajib diisi',
            'email.email'                     => 'Email tidak valid',
            'email.unique'                    => 'Email sudah terdaftar',
            'password.required'               => 'Password wajib diisi',
            'password.confirmed'              => 'Password tidak sama dengan konfirmasi password',
            'password_confirmation.required'  => 'Konfirmasi password wajib diisi',
        ];
    }

    private function changePassUser(){
        $this->rules = ['password'                       => 'required|confirmed',
                        'password_confirmation'          => 'required'
    ];
        $this->messages = [ 'password.confirmed'              => 'Password tidak sama dengan konfirmasi password',
                            'password_confirmation.required'  => 'Konfirmasi password wajib diisi'
                        ];
    }

    private function forgetPassUser(){
        $this->rules = ['email'                    => 'required|email'];
        $this->messages = ['email.required'        => 'Email wajib diisi'];
    }


    private function verifyUser(){
        $this->rules = ['token'               => 'required'];
        $this->messages = ['token.required'   => 'Token wajib diisi'];
    }

    private function login(){
        $this->rules = [
            'email'                 => 'required|email',
            'password'              => 'required',
            'device_id'             => 'required',
            'lokasi'                => 'required'
        ];
        $this->messages = [
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'password.required'     => 'Password wajib diisi',
            'password.string'       => 'Password harus berupa string',
            'device_id.required'    => 'Device_ID wajib diisi',
            'lokasi.required'       => 'Lokasi wajib diisi'
        ];
    }

    private function banner(){
        $this->rules = [
            'judul_banner'                  => 'required',
            'url_web'                       => 'required|image',
            'url_mobile'                    => 'required|image',
            'deskripsi'                     => 'required',
            'label'                         => 'required',
            'link'                          => 'required'
        ];
    
        $this->messages = [
            'judul_banner.required'         => 'Judul wajib diisi',
            'url_web.image'                 => 'Ekstensi file yang didukung jpeg dan png',
            'url_web.required'              => 'Banner Web wajib diisi',
            'url_mobile.image'              => 'Ekstensi file yang didukung jpeg dan png',
            'url_mobile.required'           => 'Banner Mobile wajib diisi',
            'deskripsi.required'            => 'Deskripsi wajib diisi',
            'label.required'                => 'Label wajib diisi',
            'link.required'                 => 'Link wajib diisi'
        ];
    }

    private function bannerWord(){
        $this->rules = [
            'jadwal'                        => 'required|date',
            'hangeul'                       => 'required',
            'pelafalan'                     => 'required',
            'penjelasan'                    => 'required',
            'url_pengucapan'                => 'required'
            //|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav
        ];
    
        $this->messages = [
            'jadwal.required'              => 'Jadwal video wajib diisi',
            'jadwal.date'                  => 'Format tanggal tidak valid',
            'hangeul.required'             => 'Hangeul wajib diisi',
            'pelafalan.required'           => 'Pelafalan wajib diisi',
            'penjelasan.required'          => 'Penjelasan wajib diisi',
            'url_pengucapan.required'      => 'Pengucapan wajib diisi',
            //'url_pengucapan.mimes'         => 'Ekstensi file yang didukung mpeg,mpga,mp3,wav'
        ];
    }

    private function bannerVideo(){
        $this->rules = [
            'jadwal'                       => 'required|date',
            'url_video'                    => 'required'
        ];
    
        $this->messages = [
            'jadwal.required'              => 'Jadwal video wajib diisi',
            'jadwal.date'                  => 'Format tanggal tidak valid',
            'url_video.required'           => 'Url video wajib diisi'
        ];
    }

    private function classCategory(){
        $this->rules = [
            'nama'                      => 'required',
            'deskripsi'                 => 'required'
        ];
    
        $this->messages = [
            'nama.required'                => 'Nama wajib diisi',
            'deskripsi.required'           => 'Deskripsi wajib diisi'
        ];
    }

    private function classes(){
        $this->rules = [
            //'id_class_category'                  => 'required',
            //'id_user'                            => 'required',
            'judul'                               => 'required',
            'deskripsi'                          => 'required',
            'url_web'                            => 'required|image',
            'url_mobile'                         => 'required|image',
            'status_tersedia'                    => 'required'
        ];
    
        $this->messages = [
            //'id_class_category.required'         => 'ID Kategori wajib diisi',
            //'id_user.required'                   => 'ID User wajib diisi',
            'judul.required'                      => 'Judul wajib diisi',
            'deskripsi.required'                 => 'Deskripsi wajib diisi',
            'url_web.required'                   => 'Banner Web wajib diisi',
            'url_web.image'                      => 'Ekstensi file yang didukung jpeg dan png',
            'url_mobile.required'                => 'Banner Mobile wajib diisi',
            'url_mobile.image'                   => 'Ekstensi file yang didukung jpeg dan png',
            'status_tersedia.required'           => 'Status Kelas wajib diisi'
        ];
    }

    private function content(){
        $this->rules = [
            //'id_class'                            => 'required',
            #'id_content_quiz'                    => 'required',
            'nomor'                               => 'required',
            #'tgl_testimoni'                      => 'required',
            #'tipe'                                => 'required'
        ];
    
        $this->messages = [
            //'id_class.required'                 => 'ID Kelas wajib diisi',
            #'id_content_quiz.required'         => 'ID Kuis wajib diisi',
            'nomor.required'                    => 'Nomor wajib diisi',
            #'tgl_testimoni.required'           => 'Tanggal wajib diisi',
            #'tipe.required'                     => 'Tipe wajib diisi'
        ];
    }

    private function contentQuiz(){
        $this->rules = [
            #'id_content'                            => 'required',
            #'id_content_quiz'                       => 'required',
            'judul'                                  => 'required',
            #'tgl_testimoni'                         => 'required',
            'keterangan'                             => 'required'
        ];
    
        $this->messages = [
            #'id_content.required'                 => 'ID Content wajib diisi',
            #'id_content_quiz.required'            => 'ID Kuis wajib diisi',
            'judul.required'                       => 'ID Judul wajib diisi',
            #'tgl_testimoni.required'              => 'Tanggal wajib diisi',
            'keterangan.required'                  => 'Keterangan wajib diisi'
        ];
    }

    private function contentVideo(){
        $this->rules = [
            #'id_content'                              => 'required',
            #'id_content_quiz'                      => 'required',
            #'id_quiz'                               => 'required',
            #'tgl_testimoni'                        => 'required',
            'judul'                                 => 'required',
            'keterangan'                             => 'required',
            'url_video'                             => 'required'
        ];
    
        $this->messages = [
            #'id_content.required'                     => 'ID Content wajib diisi',
            #'id_content_quiz.required'             => 'ID Kuis wajib diisi',
            #'id_quiz.required'                      => 'ID Kuis wajib diisi',
            #'tgl_testimoni.required'               => 'Tanggal wajib diisi',
            'judul.required'                        => 'Judul wajib diisi',
            'keterangan.required'                    => 'Keterangan wajib diisi',
            'url_video.required'                    => 'URL Video wajib diisi'
        ];
    }

    private function option(){
        $this->rules = [
            //'id_question'                              => 'required'
        ];
    
        $this->messages = [
            //'id_question.required'                      => 'ID Pertanyaan wajib diisi',
        ];
    }

    private function question(){
        $this->rules = [
            'pertanyaan_teks'                     => 'required',
            'jawaban'                             => 'required',
            #'jenis_jawaban'                       => 'required'
        ];
    
        $this->messages = [
            'pertanyaan_teks.required'            => 'Pertanyaan wajib diisi',
            'jawaban.required'                    => 'Jawaban wajib diisi',
            #'jenis_jawaban.required'              => 'Jenis Jawaban wajib diisi'
        ];
    }

    private function task(){
        $this->rules = [
            // 'id_question'                          => 'required',
            // 'id_video'                             => 'required',
            'nomor'                               => 'required',
            #'jenis_jawaban'                       => 'required'
        ];
    
        $this->messages = [
            // 'id_question.required'                 => 'ID Pertanyaan wajib diisi',
            // 'id_video.required'                    => 'ID Video wajib diisi',
            'nomor.required'                      => 'Nomor wajib diisi',
            #'jenis_jawaban.required'              => 'Jenis Jawaban wajib diisi'
        ];
    }

    private function exam(){
        $this->rules = [
            // 'id_question'                          => 'required',
            // 'id_video'                             => 'required',
            'nomor'                               => 'required',
            #'jenis_jawaban'                       => 'required'
        ];
    
        $this->messages = [
            // 'id_question.required'                 => 'ID Pertanyaan wajib diisi',
            // 'id_video.required'                    => 'ID Video wajib diisi',
            'nomor.required'                      => 'Nomor wajib diisi',
            #'jenis_jawaban.required'              => 'Jenis Jawaban wajib diisi'
        ];
    }

    private function shadowing(){
        $this->rules = [
            // 'id_question'                          => 'required',
            // 'id_video'                             => 'required',
            'nomor'                               => 'required',
            #'jenis_jawaban'                       => 'required'
        ];
    
        $this->messages = [
            // 'id_question.required'                 => 'ID Pertanyaan wajib diisi',
            // 'id_video.required'                    => 'ID Video wajib diisi',
            'nomor.required'                      => 'Nomor wajib diisi',
            #'jenis_jawaban.required'              => 'Jenis Jawaban wajib diisi'
        ];
    }

    private function word(){
        $this->rules = [
            //'jadwal'                        => 'required|date',
            'hangeul'                       => 'required',
            'pelafalan'                     => 'required',
            'penjelasan'                    => 'required',
            'url_pengucapan'                => 'required'
            //|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav
        ];
    
        $this->messages = [
            //'jadwal.required'              => 'Jadwal video wajib diisi',
            //'jadwal.date'                  => 'Format tanggal tidak valid',
            'hangeul.required'             => 'Hangeul wajib diisi',
            'pelafalan.required'           => 'Pelafalan wajib diisi',
            'penjelasan.required'          => 'Penjelasan wajib diisi',
            'url_pengucapan.required'      => 'Pengucapan wajib diisi',
            //'url_pengucapan.mimes'         => 'Ekstensi file yang didukung mpeg,mpga,mp3,wav'
        ];
    }

    private function teacher(){
        $this->rules = [   
            'id_user'                     => 'required',
            'id_class'                    => 'required'
        ];
    
        $this->messages = [
            'id_user.required'            => 'ID User wajib diisi',
            'id_class.required'           => 'ID Classroom wajib diisi'
        ];
    }

    private function post(){
        $this->rules = [   
            'judul'                        => 'required',
            'deskripsi'                    => 'required'
        ];
    
        $this->messages = [
            'judul.required'               => 'Judul wajib diisi',
            'deskripsi.required'           => 'Deskripsi wajib diisi'
        ];
    }

    private function comment(){
        $this->rules = [   
            'komentar'                        => 'required',
            //'deskripsi'                    => 'required'
        ];
    
        $this->messages = [
            'komentar.required'               => 'Komentar wajib diisi',
            //'deskripsi.required'           => 'Deskripsi wajib diisi'
        ];
    }
    private function postAlert(){
        $this->rules = [   
            'komentar'                        => 'required',
            //'deskripsi'                    => 'required'
        ];
    
        $this->messages = [
            'komentar.required'               => 'Komentar wajib diisi',
            //'deskripsi.required'           => 'Deskripsi wajib diisi'
        ];
    }
    private function commentAlert(){
        $this->rules = [   
            'komentar'                        => 'required',
            //'deskripsi'                    => 'required'
        ];
    
        $this->messages = [
            'komentar.required'               => 'Komentar wajib diisi',
            //'deskripsi.required'           => 'Deskripsi wajib diisi'
        ];
    }

    private function testimoni(){
        $this->rules = [
            'id_class'                              => 'required',
            #'id_content_quiz'                      => 'required',
            'id_user'                               => 'required',
            #'tgl_testimoni'                        => 'required',
            'testimoni'                             => 'required'
        ];
    
        $this->messages = [
            'id_class.required'                      => 'ID Kelas wajib diisi',
            #'id_content_quiz.required'              => 'ID Kuis wajib diisi',
            'id_user.required'                       => 'ID User wajib diisi',
            #'tgl_testimoni.reqired'                 => 'Tanggal wajib diisi',
            'testimoni.required'                     => 'Testimoni wajib diisi'
        ];
    }

    private function studentAnswer(){
        $this->rules = [
            'jawaban'                               => 'required',
        ];
    
        $this->messages = [
            'jawaban.required'                      => 'Jawaban wajib diisi'
        ];
    }

    private function studentQuiz(){
        $this->rules = [
            'nilai'                               => 'required',
        ];
    
        $this->messages = [
            'nilai.required'                      => 'Nilai wajib diisi'
        ];
    }
    #============================================================

    private function getUuid($model){
        $uuid = (string) str_replace('-','',Str::uuid());

        $uuid_exist = count($model::where('uuid',$uuid)->get());
        while ($uuid_exist > 0) {
            $uuid = (string) str_replace('-','',Str::uuid());
            $uuid_exist = count($model::where('uuid',$uuid)->get());
        }

        return $uuid;
    }

    private function randomToken($number,$model){
        $web_token = Str::random($number);

        while(count($model::where('web_token',$web_token)->get())>0){
            $web_token = Str::random(144);
            #return response(User::where('web_token',"177Z2jb4RfdgDYAGp04lDBuqPLFeseGb")->get());
        }
        return $web_token;
    }

    public function UUidCheck($gambar,$path){
        if(!$gambar){
            return response()->json(['message'=>"Only One Image Every Data"],401);
        }

        if(!$uploadedFileUrl = Cloudinary::uploadFile($gambar->getRealPath(),[
            'folder' => /*date("Y-m-d")."/".*/'Testing'.$path,
            'use_filename' => 'True',
            'filename_override' => date('mdYhis')
        ])){
            return response()->json(['message'=>'Image Upload Failed','input'=>$return_data]);
        }

        $uploadResponse = [
            'getSecurePath'   =>  $uploadedFileUrl->getSecurePath(),
            'getPublicId'     =>  $uploadedFileUrl->getPublicId()
        ];

        return $uploadResponse;
    }

    public function deleteImage($getPublicId){
        Cloudinary::destroy($getPublicId);
    }

    public function deleteFile($getPublicId){
        Cloudinary::destroy($getPublicId, array("resource_type"=>"video"));
    }

}
