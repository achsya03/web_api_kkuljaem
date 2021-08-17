<?php

namespace App\Http\Controllers\Classes;

use App\Models\Classes;
use App\Models\ClassesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Validator;

class ClassController extends Controller
{

    public function addData(Request $request)
    {
        $validation = new Helper\ValidationController('classes');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        $return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        $id_class_category = ClassesCategory::where('uuid',$request->id_class_category)->first();
        if(!$id_class_category){
            return response()->json(['message'=>'Failed','info'=>'Category ID not valid']);
        }

        $gambar1 = $request->url_web;
        $uploadedFileUrl1 = $validation->UUidCheck($gambar1,'Class/Web');

        $gambar2 = $request->url_mobile;
        $uploadedFileUrl2 = $validation->UUidCheck($gambar2,'Class/Mobile');

        $uuid = $validation->data['uuid'];
        
        $data = [
            'id_class_category'          => $id_class_category->id,
            'nama'                       => request('nama'),
            'deskripsi'                  => request('deskripsi'),
            'url_web'                    => $uploadedFileUrl1['getSecurePath'],
            'web_id'                     => $uploadedFileUrl1['getPublicId'],
            'url_mobile'                 => $uploadedFileUrl2['getSecurePath'],
            'mobile_id'                  => $uploadedFileUrl2['getPublicId'],
            'jml_materi'                 => 0,
            'jml_kuis'                   => 0,
            'status_tersedia'            => request('status_tersedia'),
            'uuid'                       => $uuid
        ];

        $input = new Helper\InputController('classes',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil'],200);
    }

    public function updateData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validation = new Helper\ValidationController('classes');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;


        $classes = Classes::where('uuid',$uuid)->first();

        if(!$classes){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $id_class_category = ClassesCategory::where('uuid',$request->id_class_category)->first();
        if(!$id_class_category){
            return response()->json(['message'=>'Failed','info'=>'ID Category tidak valid']);
        }

        $gambar1 = $request->url_web;
        $uploadedFileUrl1 = $validation->UUidCheck($gambar1,'Banner/Web');

        $gambar2 = $request->url_mobile;
        $uploadedFileUrl2 = $validation->UUidCheck($gambar2,'Banner/Mobile');

        $validation->deleteImage($classes->web_id);
        $validation->deleteImage($classes->mobile_id);

        $data = [
            'id_class_category'          => $id_class_category->id,
            'nama'                       => request('nama'),
            'deskripsi'                  => request('deskripsi'),
            'url_web'                    => $uploadedFileUrl1['getSecurePath'],
            'web_id'                     => $uploadedFileUrl1['getPublicId'],
            'url_mobile'                 => $uploadedFileUrl2['getSecurePath'],
            'mobile_id'                  => $uploadedFileUrl2['getPublicId'],
            'status_tersedia'            => request('status_tersedia'),
            'uuid'              => $uuid
        ];

        $input = new Helper\UpdateController('classes',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);
    }

    // public function allData(Request $request){

    //     $banner = Classes::all();
    //     foreach ($banner as $bann) {
    //         unset($bann['id']);
    //         unset($bann['web_id']);
    //         unset($bann['mobile_id']);
    //         unset($bann['id_class_category']);
    //     }
        

    //     return response()->json(['message'=>'Success','data'
    //     => $banner]);
    // }

    // public function detailData(Request $request){
    //     if(!$uuid=$request->token){
    //         return response()->json(['message'=>'Failed','info'=>"Token Not Valid"]);
    //     }
    //     if(count(Classes::where('uuid',$uuid)->get())==0){
    //         return response()->json(['message'=>'Failed','info'=>"Token Not Valid"]);
    //     }

    //     $banner = Classes::where('uuid',$uuid)->first();
    //     unset($banner['id']);
    //     unset($banner['web_id']);
    //     unset($banner['mobile_id']);
    //     unset($banner['id_class_category']);

    //     return response()->json(['message'
    //     => $banner],200);
    // }
}
