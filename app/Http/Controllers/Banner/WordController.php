<?php

namespace App\Http\Controllers\Banner;

use App\Models\Words;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Validator;

class WordController extends Controller
{

    public function addDataWord(Request $request)
    {
        $validation = new Helper\ValidationController('bannerWord');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        $return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>$validator->errors(),'input'=>$return_data]);
        }

        $jadwal = date_format(date_create($request->jadwal),"Y/m/d");
        if($jadwal < date("Y/m/d")){
            return response()->json(['message'=>'Failed','info'=>'Tanggal dimulai dari hari ini']);
        }

        $uuid = $validation->data['uuid'];

        $gambar1 = $request->url_pengucapan;
        $uploadedFileUrl1 = $validation->UUidCheck($gambar1,'Word');


        $data = [
            'jadwal'            => $jadwal,
            'hangeul'           => request('hangeul'),
            'pelafalan'         => request('pelafalan'),
            'penjelasan'        => request('penjelasan'),
            'url_pengucapan'    => $uploadedFileUrl1['getSecurePath'],
            'pengucapan_id'     => $uploadedFileUrl1['getPublicId'],
            'uuid'              => $uuid
        ];

        $input = new Helper\InputController('word',$data);


        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil']);
    }

    public function updateDataWord(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validation = new Helper\ValidationController('bannerWord');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;


        $word = Words::where('uuid',$uuid)->first();

        if(!$word){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $gambar1 = $request->url_pengucapan;
        $uploadedFileUrl1 = $validation->UUidCheck($gambar1,'Word');

        $validation->deleteImage($word->pengucapan_id);

        $jadwal = date_format(date_create($request->jadwal),"Y/m/d");

        if($jadwal < date("Y/m/d")){
            return response()->json(['message'=>'Failed','info'=>'Tanggal dimulai dari hari ini']);
        }

        $data = [
            'jadwal'            => $jadwal,
            'hangeul'           => request('hangeul'),
            'pelafalan'         => request('pelafalan'),
            'penjelasan'        => request('penjelasan'),
            'url_pengucapan'    => $uploadedFileUrl1['getSecurePath'],
            'pengucapan_id'     => $uploadedFileUrl1['getPublicId'],
            'uuid'              => $uuid
        ];

        $input = new Helper\UpdateController('word',$data);


        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);

    }

    public function allDataWord(Request $request){

        $word = Words::all(); 
        foreach ($word as $wo) {
            unset($wo['id']);  
            unset($wo['pengucapan_id']);  
        }     

        return response()->json(['message'=>'Success','data'
        => $word]);
    }

    public static function detailDataWord($token){
        if(!$uuid=$token){
            return response()->json(['message' => 'Failed',
            'info'=>"Token Tidak Sesuai"]);
        }
        if(count(Words::where('uuid',$uuid)->get())==0){
            return response()->json(['message' => 'Failed',
            'info'=>"Token Tidak Sesuai"]);
        }

        $word = Words::where('uuid',$uuid)->first();
        unset($word['id']);
        unset($word['pengucapan_id']); 

        return response()->json(['message'=>'Success','data'
        => $word]);
    }
}
