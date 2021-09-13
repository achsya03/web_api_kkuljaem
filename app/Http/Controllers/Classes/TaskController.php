<?php

namespace App\Http\Controllers\Classes;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function checkData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        if(count($task = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        $task = Models\Task::where('id_video',$task[0]->id)->get();
        $result['nomor_soal'] = count($task)+1;
    
        return response()->json(['message'=>'Success','data'
        => $result]);
    }

    public function addData(Request $request)
    {
        $validation = new Helper\ValidationController('task');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        $return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $validation1 = new Helper\ValidationController('question');
        $this->rules = $validation1->rules;
        $this->messages = $validation1->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        $return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        $validation2 = new Helper\ValidationController('option');
        $this->rules = $validation2->rules;
        $this->messages = $validation2->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        $return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','id'=>$i,'info'=>$validator->errors()]);
        }
        
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        if(count($video = Video::where('uuid',$uuid)->get())==0){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $uuid1 = $validation1->data['uuid'];

        $uploadedFileUrl1 = [
            'getSecurePath' => '',
            'getPublicId' => '',
        ];

        $uploadedFileUrl2 = $uploadedFileUrl1;

        if($gambar1 = $request->gambar_pertanyaan){
            $uploadedFileUrl1 = $validation1->UUidCheck($gambar1,'Question/Gambar');
        }

        // if($gambar2 = $request->file_pertanyaan){
        //     $uploadedFileUrl2 = $validation1->UUidCheck($gambar2,'Question/File');
        // }
        $data = [
            'pertanyaan_teks'             => $request->nama,
            'url_gambar'                  => $uploadedFileUrl1['getSecurePath'],
            'gambar_id'                   => $uploadedFileUrl1['getPublicId'],
            'url_file'                    => $request->file_pertanyaan,
            //'file_id'                     => $uploadedFileUrl2['getPublicId'],
            'jawaban'                     => $request->jawaban,
            'uuid'                        => $uuid1
        ];

        $input = new Helper\InputController('question',$data);

        $question = Models\Question::where('uuid',$uuid1)->first();

        for($i=0;$i<4;$i++){

            $uuid2 = $validation2->data['uuid'];

            $uploadedFileUrl1 = [
                'getSecurePath' => '',
                'getPublicId' => '',
            ];

            $uploadedFileUrl2 = $uploadedFileUrl1;

            if($gambar1 = $request->gambar_opsi[$i]){
                $uploadedFileUrl1 = $validation2->UUidCheck($gambar1,'Option/Gambar');
            }

            // if($gambar2 = $request->file_opsi[$i]){
            //     $uploadedFileUrl2 = $validation2->UUidCheck($gambar2,'Option/File');
            // }
            $data = [
                'id_question'                 => $question->id,
                'jawaban_id'                  => $request->jawaban_id,
                'jawaban_teks'                => $request->jawaban_teks,
                'url_gambar'                  => $uploadedFileUrl1['getSecurePath'],
                'gambar_id'                   => $uploadedFileUrl1['getPublicId'],
                'url_file'                    => $request->file_pertanyaan,
                //'file_id'                     => $uploadedFileUrl2['getPublicId'],
                'uuid'                        => $uuid2
            ];

            $input = new Helper\InputController('question',$data);
        }

        $uuid1 = $validation->data['uuid'];

        $data = [
            'id_question'               => $question->id,
            'id_video'                  => $video[0]->id,
            'number'                    => $request->number,
            'uuid'                      => $uuid1
        ];

        $input = new Helper\InputController('task',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil']);
    }
}
