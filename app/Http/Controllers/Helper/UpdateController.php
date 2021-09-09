<?php

namespace App\Http\Controllers\Helper;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __construct($pos,$data)
    {
        $this->pos = $pos;
        if($pos=='verifyUser'){
            $this->verifyUser(Models\User::class,$data);
        }elseif($pos=='changePassUser'){
            $this->changePassUser(Models\User::class,$data);
        }elseif($pos=='login'){
            $this->login(Models\User::class,$data);
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
        }elseif($pos=='teacher'){
            $this->teacher(Models\Teacher::class,$data);
        }elseif($pos=='testimoni'){
            $this->testimoni(Models\Testimoni::class,$data);
        }
    }

    private function changePassUser($model,$data){
        $model::where('web_token',$data['old_web_token'])
        ->update([
            'web_token'      => $data['web_token'],
            'password'       => $data['password']
        ]);
    }

    private function verifyUser($model,$data){
        $model::where('web_token',$data['old_web_token'])
        ->update([
            'email_verified_at'  => DB::raw('CURRENT_TIMESTAMP'),
            'web_token'          => $data['web_token']
        ]);
    }

    private function login($model,$data){
        $model::where('email',$data['email'])
        ->update([
            'device_id'       => $data['device_id'],
            'lokasi'          => $data['lokasi']
        ]);
    }

    private function banner($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'judul_banner'       => $data['judul_banner'],
            'url_web'            => $data['url_web'],
            'web_id'             => $data['web_id'],
            'url_mobile'         => $data['url_mobile'],
            'mobile_id'          => $data['mobile_id'],
            'deskripsi'          => $data['deskripsi'],
            'label'              => $data['label'],
            'link'               => $data['link']
        ]);
    }

    private function word($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'jadwal'          => $data['jadwal'],
            'hangeul'         => $data['hangeul'],
            'pelafalan'       => $data['pelafalan'],
            'penjelasan'      => $data['penjelasan'],
            'url_pengucapan'  => $data['url_pengucapan'],
            'pengucapan_id'   => $data['pengucapan_id']
        ]);
    }

    private function video($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'jadwal'          => $data['jadwal'],
            'url_video'       => $data['url_video']
        ]);
    }

    private function classCategory($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'nama'            => $data['nama'],
            'deskripsi'       => $data['deskripsi']
        ]);
    }

    private function classes($model,$data){
        $field = [
            'id_class_category',
            'nama',
            'deskripsi',
            'url_web',
            'web_id',
            'url_mobile',
            'mobile_id',
            'jml_video',
            'jml_kuis',
            'status_tersedia',
            'uuid',
        ];
        for($i=0;$i<count($field)-1;$i++){
            if(isset($data[$field[$i]])){
                $model::where('uuid',$data['uuid'])
                ->update([
                    $field[$i]            => $data[$field[$i]]
                ]);
            }
        }
        
    }
    
    private function contentQuiz($model,$data){
        $field = [
            'judul',
            'keterangan',
            'jml_pertanyaan',
            'uuid',
        ];
        for($i=0;$i<count($field)-1;$i++){
            if(isset($data[$field[$i]])){
                $model::where('uuid',$data['uuid'])
                ->update([
                    $field[$i]            => $data[$field[$i]]
                ]);
            }
        }
    }

    private function contentVideo($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'judul'                        => $data['judul'],
            'keterangan'                    => $data['keterangan'],
            'url_video'                    => $data['url_video']
        ]);
    }

    private function option($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'id_question'           => $data['id_question'],
            'jawaban_teks'          => $data['jawaban_teks'],
            'url_gambar'            => $data['url_gambar'],
            'gambar_id'             => $data['gambar_id'],
            'url_file'              => $data['url_file'],
            'file_id'               => $data['file_id']
        ]);
    }

    private function question($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'pertanyaan_teks'       => $data['pertanyaan_teks'],
            'url_gambar'            => $data['url_gambar'],
            'gambar_id'             => $data['gambar_id'],
            'url_file'              => $data['url_file'],
            'file_id'               => $data['file_id'],
            'jawaban'               => $data['jawaban']
        ]);
    }

    private function teacher($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'id_user'             => $data['id_user'],
            'id_class'            => $data['id_class']
        ]);
    }

    private function testimoni($model,$data){
        $model::where('uuid',$data['uuid'])
        ->update([
            'id_class'           => $data['id_class'],
            'id_user'            => $data['id_user'],
            'tgl_testimoni'      => $data['tgl_testimoni'],
            'testimoni'          => $data['testimoni']
        ]);
    }
}
