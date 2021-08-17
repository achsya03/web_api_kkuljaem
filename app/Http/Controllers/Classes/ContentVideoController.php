<?php

namespace App\Http\Controllers\Classes;

use App\Models\ContentVideo;
use App\Models\Classes;
use App\Models\ContentQuiz;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Validator;

class ContentVideoController extends Controller
{
    public function addData(Request $request)
    {
        $validation = new Helper\ValidationController('contentVideo');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        #$id_quiz = ContentQuiz::where('uuid',$request->id_quiz)->first();
        #if($id_quiz==null){
        #    return response()->json(['message'=>'ID Quiz Tidak Sesuai','input'=>$return_data]);
        #}

        $id_class = Classes::where('uuid',$request->id_class)->first();
        if($id_class==null){
            return response()->json(['message'=>'Failed','info'=>'ID Class Tidak Sesuai']);
        }

        $uuid = $validation->data['uuid'];
       
        $data = [
            'id_class'      => $id_class->id,
            #'id_quiz'       => $id_quiz->id,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'url_video'     => $request->url_video,
            'like_count'    => 0,
            'comment_count' => 0,
            'uuid'          => $uuid
        ];

        $input = new Helper\InputController('contentVideo',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil']);
    }

    public function updateData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validation = new Helper\ValidationController('contentVideo');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $object = ContentVideo::where('uuid',$uuid)->first();

        if(!$object){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        #$id_quiz = ContentQuiz::where('uuid',$request->id_quiz)->first();
        #if($id_quiz==null){
        #    return response()->json(['message'=>'ID Quiz Tidak Sesuai','input'=>$return_data]);
        #}

        $id_class = Classes::where('uuid',$request->id_class)->first();
        if($id_class==null){
            return response()->json(['message'=>'Failed','info'=>'ID Class Tidak Sesuai']);
        }
       
        $data = [
            'id_class'      => $id_class->id,
            #'id_quiz'       => $id_quiz->id,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'url_video'     => $request->url_video,
            'uuid'          => $uuid
        ];

        $input = new Helper\UpdateController('contentVideo',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);
    }
}
