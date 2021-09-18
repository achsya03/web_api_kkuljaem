<?php

namespace App\Http\Controllers\Classes;

use App\Models;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public function studentVideoAdd(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $video = Models\Video::where('uuid',$uuid)->get();
        if(count($video)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if(count($sisVid = Models\StudentVideo::where('id_video',$video[0]->id)
        ->where('id_user',$request->user()->id)->get())>0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Video Siswa Telah Terdaftar'
            ]);
        }

        $siswa = Models\Student::where('id_class',$video->content->id_class)
                ->where('id_user',$request->user()->id)->get();


        if($video->content->number == 1){
            if(count($siswa)==0){
                # create student
                $validation = new Helper\ValidationController('student');
                $uuid1 = $validation->data['uuid'];

                $data = [
                    'id_user' => $request->user()->id,
                    'id_class' => $video->content->id_class,
                    'register_date' => date(Y/m/d),
                    'jml_pengerjaan' => 0,
                    'uuid' => $uuid1
                ];

                $input = new Helper\InputController('student',$data);

                $siswa = Models\Student::where('uuid',$uuid1)->get();
            }
        }

        # create student video
        $validation1 = new Helper\ValidationController('studentVideo');
        $uuid2 = $validation1->data['uuid'];

        $data = [
            'id_student' => $siswa[0]->id_user,
            'id_video' => $video[0]->id,
            'register_date' => date(Y/m/d),
            'uuid' => $uuid2
        ];
        $input = new Helper\InputController('studentVideo',$data);

        # update student

        $data = [
            'jml_pengerjaan' => $siswa[0]->jml_pengerjaan+1,
            'uuid' => $siswa[0]->uuid
        ];
        $update = new Helper\UpdateController('studentAnswer',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Siswa Video Berhasil'
        ]);
    }

    public function studentQuizAdd(Request $request){
        $this->middleware('auth');
    }
}
