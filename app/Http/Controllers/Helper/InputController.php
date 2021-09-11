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
        }elseif($pos=='teacher'){
            $this->teacher(Models\Teacher::class,$data);
        }elseif($pos=='testimoni'){
            $this->testimoni(Models\Testimoni::class,$data);
        }
    }

    private function authUser($model,$data){
        $model::create([
            #'nama' => request('nama'),
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
            'pengucapan_id'   => $data['pengucapan_id'],
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
            'nama'                         => $data['nama'],
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
            'file_id'               => $data['file_id'],
            'uuid'                  => $data['uuid']
        ]);
    }

    private function question($model,$data){
        $model::create([
            'pertanyaan_teks'       => $data['pertanyaan_teks'],
            'url_gambar'            => $data['url_gambar'],
            'gambar_id'             => $data['gambar_id'],
            'url_file'              => $data['url_file'],
            'file_id'               => $data['file_id'],
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

    private function teacher($model,$data){
        $model::create([
            'id_user'            => $data['id_user'],
            'id_class'           => $data['id_class'],
            'uuid'               => $data['uuid']
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
}

