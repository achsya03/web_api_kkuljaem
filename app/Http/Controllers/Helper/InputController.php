<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InputController extends Controller
{
    public function __construct($pos,$data)
    {
        $this->pos = $pos;
        if($pos=='authUser'){
            $this->authUser(Models\User::class,$data);
        }elseif($pos=='banner'){
            $this->banner(Models\Banner::class,$data);
        }elseif($pos=='word'){
            $this->word(Models\Words::class,$data);
        }elseif($pos=='video'){
            $this->video(Models\Videos::class,$data);
        }elseif($pos=='classCategory'){
            $this->classCategory(Models\ClassesCategory::class,$data);
        }elseif($pos=='classes'){
            $this->classes(Models\Classes::class,$data);
        }elseif($pos=='content'){
            $this->content(Models\Content::class,$data);
        }elseif($pos=='contentQuiz'){
            $this->contentQuiz(Models\Quiz::class,$data);
        }elseif($pos=='contentVideo'){
            $this->contentVideo(Models\Video::class,$data);
        }elseif($pos=='option'){
            $this->option(Models\Option::class,$data);
        }elseif($pos=='question'){
            $this->question(Models\Question::class,$data);
        }elseif($pos=='task'){
            $this->task(Models\Task::class,$data);
        }elseif($pos=='exam'){
            $this->exam(Models\Exam::class,$data);
        }elseif($pos=='shadowing'){
            $this->shadowing(Models\Shadowing::class,$data);
        }elseif($pos=='teacher'){
            $this->teacher(Models\Teacher::class,$data);
        }elseif($pos=='theme'){
            $this->theme(Models\Theme::class,$data);
        }elseif($pos=='videoTheme'){
            $this->videoTheme(Models\VideoTheme::class,$data);
        }elseif($pos=='post'){
            $this->post(Models\Post::class,$data);
        }elseif($pos=='postImage'){
            $this->postImage(Models\PostImage::class,$data);
        }elseif($pos=='comment'){
            $this->comment(Models\Comment::class,$data);
        }elseif($pos=='postAlert'){
            $this->postAlert(Models\PostAlert::class,$data);
        }elseif($pos=='postLike'){
            $this->postLike(Models\PostLike::class,$data);
        }elseif($pos=='commentAlert'){
            $this->commentAlert(Models\CommentAlert::class,$data);
        }elseif($pos=='testimoni'){
            $this->testimoni(Models\Testimoni::class,$data);
        }elseif($pos=='student'){
            $this->student(Models\Student::class,$data);
        }elseif($pos=='studentVideo'){
            $this->studentVideo(Models\StudentVideo::class,$data);
        }elseif($pos=='studentQuiz'){
            $this->studentQuiz(Models\StudentQuiz::class,$data);
        }elseif($pos=='studentAnswer'){
            $this->studentAnswer(Models\StudentAnswer::class,$data);
        }
    }

    private function authUser($model,$data){
        $model::create([
            'nama'           => $data['nama'],
            'email'          => $data['email'],
            'password'       => $data['password'],
            'web_token'      => $data['web_token'],
            'jenis_pengguna' => $data['jenis_pengguna'],
            'jenis_akun'     => $data['jenis_akun'],
            'uuid'           => $data['uuid']
        ]);
    }

    private function banner($model,$data){
        $model::create([
            'judul_banner'       => $data['judul_banner'],
            'url_web'            => $data['url_web'],
            'web_id'             => $data['web_id'],
            'url_mobile'         => $data['url_mobile'],
            'mobile_id'          => $data['mobile_id'],
            'deskripsi'          => $data['deskripsi'],
            'label'              => $data['label'],
            'link'               => $data['link'],
            'uuid'               => $data['uuid']
        ]);
    }

    private function word($model,$data){
        $model::create([
            'jadwal'          => $data['jadwal'],
            'hangeul'         => $data['hangeul'],
            'pelafalan'       => $data['pelafalan'],
            'penjelasan'      => $data['penjelasan'],
            'url_pengucapan'  => $data['url_pengucapan'],
            //'pengucapan_id'   => $data['pengucapan_id'],
            'uuid'            => $data['uuid']
        ]);
    }

    private function video($model,$data){
        $model::create([
            'jadwal'          => $data['jadwal'],
            'url_video'       => $data['url_video'],
            'uuid'            => $data['uuid']
        ]);
    }

    private function classCategory($model,$data){
        $model::create([
            'nama'            => $data['nama'],
            'deskripsi'       => $data['deskripsi'],
            'uuid'            => $data['uuid']
        ]);
    }

    private function classes($model,$data){
        $model::create([
            'id_class_category'            => $data['id_class_category'],
            'nama'                          => $data['nama'],
            'deskripsi'                    => $data['deskripsi'],
            'url_web'                      => $data['url_web'],
            'web_id'                       => $data['web_id'],
            'url_mobile'                   => $data['url_mobile'],
            'mobile_id'                    => $data['mobile_id'],
            'jml_video'                    => $data['jml_video'],
            'jml_kuis'                     => $data['jml_kuis'],
            'status_tersedia'              => $data['status_tersedia'],
            'uuid'                         => $data['uuid']
        ]);
    }

    private function content($model,$data){
        $model::create([
            'id_class'                     => $data['id_class'],
            'number'                       => $data['number'],
            'type'                         => $data['type'],
            'uuid'                         => $data['uuid']
        ]);
    }

    private function contentQuiz($model,$data){
        $model::create([
            'id_content'                  => $data['id_content'],
            'judul'                        => $data['judul'],
            'keterangan'                   => $data['keterangan'],
            'jml_pertanyaan'               => $data['jml_pertanyaan'],
            'uuid'                         => $data['uuid']
        ]);
    }

    private function contentVideo($model,$data){
        $model::create([
            'id_content'                     => $data['id_content'],
            #'id_quiz'                      => $data['id_quiz'],
            'judul'                        => $data['judul'],
            'keterangan'                    => $data['keterangan'],
            'url_video'                    => $data['url_video'],
            'jml_latihan'                   => $data['jml_latihan'],
            'jml_shadowing'                => $data['jml_shadowing'],
            'uuid'                         => $data['uuid']
        ]);
    }

    private function option($model,$data){
        $model::create([
            'id_question'           => $data['id_question'],
            'jawaban_id'            => $data['jawaban_id'],
            'jawaban_teks'          => $data['jawaban_teks'],
            'url_gambar'            => $data['url_gambar'],
            'gambar_id'             => $data['gambar_id'],
            'url_file'              => $data['url_file'],
            //'file_id'               => $data['file_id'],
            'uuid'                  => $data['uuid']
        ]);
    }

    private function question($model,$data){
        $model::create([
            'pertanyaan_teks'       => $data['pertanyaan_teks'],
            'url_gambar'            => $data['url_gambar'],
            'gambar_id'             => $data['gambar_id'],
            'url_file'              => $data['url_file'],
            //'file_id'               => $data['file_id'],
            'jawaban'               => $data['jawaban'],
            'uuid'                  => $data['uuid']
        ]);
    }

    private function task($model,$data){
        $model::create([
            'id_question'            => $data['id_question'],
            'id_video'               => $data['id_video'],
            'number'                 => $data['number'],
            'uuid'                   => $data['uuid']
        ]);
    }

    private function exam($model,$data){
        $model::create([
            'id_question'            => $data['id_question'],
            'id_quiz'                => $data['id_quiz'],
            'number'                 => $data['number'],
            'uuid'                   => $data['uuid']
        ]);
    }

    private function shadowing($model,$data){
        $model::create([
            'id_word'                => $data['id_word'],
            'id_video'               => $data['id_video'],
            'number'                 => $data['number'],
            'uuid'                   => $data['uuid']
        ]);
    }

    private function teacher($model,$data){
        $model::create([
            'id_user'            => $data['id_user'],
            'id_class'           => $data['id_class'],
            'uuid'               => $data['uuid']
        ]);
    }

    private function theme($model,$data){
        $model::create([
            'judul'              => $data['judul'],
            'jml_post'           => $data['jml_post'],
            'jml_like'           => $data['jml_like'],
            'jml_comment'        => $data['jml_comment'],
            'uuid'               => $data['uuid']
        ]);
    }

    private function videoTheme($model,$data){
        $model::create([
            'id_video'           => $data['id_video'],
            'id_theme'           => $data['id_theme'],
            'uuid'               => $data['uuid']
        ]);
    }

    private function post($model,$data){
        $model::create([
            'id_user'             => $data['id_user'],
            'id_theme'            => $data['id_theme'],
            'judul'               => $data['judul'],
            'jenis'               => $data['jenis'],
            'deskripsi'           => $data['deskripsi'],
            'jml_like'            => $data['jml_like'],
            'jml_komen'           => $data['jml_komen'],
            'stat_post'           => $data['stat_post'],
            'uuid'                => $data['uuid']
        ]);
    }

    private function postImage($model,$data){
        $model::create([
            'id_post'           => $data['id_post'],
            'url_gambar'        => $data['url_gambar'],
            'gambar_id'         => $data['gambar_id'],
            'uuid'              => $data['uuid']
        ]);
    }

    private function comment($model,$data){
        $model::create([
            'id_user'           => $data['id_user'],
            'id_post'           => $data['id_post'],
            'comment'           => $data['comment'],
            'stat_comment'      => $data['stat_comment'],
            'uuid'              => $data['uuid']
        ]);
    }

    private function postAlert($model,$data){
        $model::create([
            'id_user'           => $data['id_user'],
            'id_post'           => $data['id_post'],
            'komentar'          => $data['komentar'],
            'alert_status'      => $data['alert_status'],
            'uuid'              => $data['uuid']
        ]);
    }

    private function postLike($model,$data){
        $model::create([
            'id_user'           => $data['id_user'],
            'id_post'           => $data['id_post'],
            'uuid'              => $data['uuid']
        ]);
    }

    private function commentAlert($model,$data){
        $model::create([
            'id_user'           => $data['id_user'],
            'id_comment'        => $data['id_comment'],
            'komentar'          => $data['komentar'],
            'alert_status'      => $data['alert_status'],
            'uuid'              => $data['uuid']
        ]);
    }

    private function testimoni($model,$data){
        $model::create([
            'id_class'            => $data['id_class'],
            'id_user'             => $data['id_user'],
            'tgl_testimoni'       => $data['tgl_testimoni'],
            'testimoni'           => $data['testimoni'],
            'uuid'                => $data['uuid']
        ]);
    }

    private function student($model,$data){
        $model::create([
            'id_user'               => $data['id_user'],
            'id_class'              => $data['id_class'],
            'register_date'         => $data['register_date'],
            'jml_pengerjaan'        => $data['jml_pengerjaan'],
            'uuid'                  => $data['uuid']
        ]);
    }


    private function studentVideo($model,$data){
        $model::create([
            'id_student'            => $data['id_student'],
            'id_video'              => $data['id_video'],
            'register_date'         => $data['register_date'],
            'uuid'                  => $data['uuid']
        ]);
    }


    private function studentQuiz($model,$data){
        $model::create([
            'id_student'            => $data['id_student'],
            'id_quiz'               => $data['id_quiz'],
            'register_date'         => $data['register_date'],
            'nilai'                 => $data['nilai'],
            'uuid'                  => $data['uuid']
        ]);
    }


    private function studentAnswer($model,$data){
        $model::create([
            'id_student_quiz'          => $data['id_student_quiz'],
            //'id_question'         => $data['id_question'],
            'jawaban'             => $data['jawaban'],
            'uuid'                => $data['uuid']
        ]);
    }
}

