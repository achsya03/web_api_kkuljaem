<?php

namespace App\Http\Controllers\Classes;

use App\Models;
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
        $validation1 = new Helper\ValidationController('content');
        $this->rules = $validation1->rules;
        $this->messages = $validation1->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $validation = new Helper\ValidationController('contentVideo');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }


        $id_class = Classes::where('uuid',$request->id_class)->first();
        if($id_class==null){
            return response()->json(['message'=>'Failed','info'=>'ID Class Tidak Sesuai']);
        }

        $uuid1 = $validation1->data['uuid'];
       
        $data = [
            'id_class'      => $id_class->id,
            'number'        => $request->nomor,
            'type'          => 'video',
            'uuid'          => $uuid1
        ];

        $input = new Helper\InputController('content',$data);

        $uuid = $validation->data['uuid'];
        $id_content = Models\Content::where('uuid',$uuid1)->first();
        $data = [
            'id_content'      => $id_content->id,
            'judul'           => $request->judul,
            'keterangan'      => $request->keterangan,
            'jml_latihan'     => 0,
            'jml_shadowing'   => 0,
            'url_video'       => $request->url_video,
            'uuid'            => $uuid
        ];

        $input = new Helper\InputController('contentVideo',$data);

        $id_class1 = Models\Classes::where('id',$id_class->id)->first();
        $data = [
            'jml_video'              => $id_class1->jml_video+1,
            'uuid'                   => $id_class1->uuid
        ];

        $update = new Helper\UpdateController('classes',$data);

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

        $id_video = Models\Video::where('uuid',$uuid)->first();

        if($id_video==null){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

       
        $data = [
            'judul'           => $request->judul,
            'keterangan'      => $request->keterangan,
            'url_video'       => $request->url_video,
            'uuid'            => $uuid
        ];

        $input = new Helper\UpdateController('contentVideo',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);
    }

    public function detailData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $object = Models\Video::where('uuid',$uuid)->first();

        if(!$object){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        #unset($object->id);

        $result = [
            'nomor'         => $object->content->number,
            'judul'         => $object->judul,
            'keterangan'    => $object->keterangan,
            'url_video'     => $object->url_video,
            'uuid'          => $object->uuid,
        ];

        return response()->json(['message'=>'Success','data'
        => $result]);
    }
}
