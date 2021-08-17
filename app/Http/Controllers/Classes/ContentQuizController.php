<?php

namespace App\Http\Controllers\Classes;

use App\Models\Question;
use App\Models\ContentQuiz;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Validator;

class ContentQuizController extends Controller
{

    public function addData(Request $request)
    {
        $validation = new Helper\ValidationController('contentQuiz');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        $id_question = Question::where('uuid',$request->id_question)->first();
        if($id_question==null){
            return response()->json(['message'=>'Failed','info'=>'ID Quiz Tidak Sesuai']);
        }

        $uuid = $validation->data['uuid'];
       
        $data = [
            'id_question'       => $id_question->id,
            'judul'             => $request->judul,
            'keterangan'        => $request->keterangan,
            'jml_pertanyaan'    => 0,
            'uuid'              => $uuid
        ];

        $input = new Helper\InputController('teacher',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil']);
    }

    public function updateData(Request $request){
        $validation = new Helper\ValidationController('contentQuiz');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $object = ContentQuiz::where('uuid',$uuid)->first();

        if(!$object){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        $id_question = Question::where('uuid',$request->id_question)->first();
        if($id_question==null){
            return response()->json(['message'=>'Failed','info'=>'ID Quiz Tidak Sesuai']);
        }
   
        $data = [
            'id_question'       => $id_question->id,
            'judul'             => $request->judul,
            'keterangan'        => $request->keterangan,
            'jml_pertanyaan'    => $object->jml_pertanyaan,
            'uuid'              => $uuid
        ];

        $input = new Helper\UpdateController('contentQuiz',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);
    }
}
