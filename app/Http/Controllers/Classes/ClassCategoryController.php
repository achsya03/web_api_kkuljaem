<?php

namespace App\Http\Controllers\Classes;

use App\Models;
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

    public function deleteData(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Classescategory::where('uuid',$uuid)->get();
        if(count($post)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete comment
        $delete = Models\Classescategory::where('uuid',$uuid)->delete();
    

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Category Kelas Berhasil'
        ]);
    }

    public function allData(Request $request){

        $class_cat = ClassesCategory::all();
        for($i=0;$i<count($class_cat);$i++) {
            $classes = Models\Classes::where('id_class_category',$class_cat[$i]->id)->get();
            unset($class_cat[$i]['id']);
            $class_cat[$i]['jml_kelas'] = count($classes);
        }
        

        return response()->json(['message'=>'Success','data'
        => $class_cat]);
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
