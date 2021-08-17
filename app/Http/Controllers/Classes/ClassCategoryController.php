<?php

namespace App\Http\Controllers\Classes;

use App\Models\ClassesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Helper;

class ClassCategoryController extends Controller
{

    public function addData(Request $request)
    {
        $validation = new Helper\ValidationController('classCategory');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $uuid = $validation->data['uuid'];

        $data = [
            'nama'                  => request('nama'),
            'deskripsi'             => request('deskripsi'),
            'uuid'                  => $uuid
        ];

        $input = new Helper\InputController('classCategory',$data);


        return response()->json(['message'=>'Success','info'
        => 'Proses Input Berhasil']);
    }

    public function updateData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validation = new Helper\ValidationController('classCategory');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;


        $class_cat = ClassesCategory::where('uuid',$uuid)->first();

        if(!$class_cat){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        
        $data = [
            'nama'                  => request('nama'),
            'deskripsi'             => request('deskripsi'),
            'uuid'                  => $uuid
        ];

        $input = new Helper\UpdateController('classCategory',$data);

        return response()->json(['message'=>'Success','info'
        => 'Proses Update Berhasil']);
    }

    public function allData(Request $request){

        $class_cat = ClassesCategory::all();
        foreach ($class_cat as $ct) {
            unset($ct['id']);
        }
        

        return response()->json(['message'=>'Success','data'
        => $class_cat],200);
    }

    public function detailData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        if(count(ClassesCategory::where('uuid',$uuid)->get())==0){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $class_cat = ClassesCategory::where('uuid',$uuid)->first();
        unset($class_cat['id']);

        return response()->json(['message'=>'Success','data'
        => $class_cat]);
    }
}
