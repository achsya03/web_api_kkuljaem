<?php

namespace App\Http\Controllers\Classes;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShadowingController extends Controller
{
    public function checkData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        if(count($task = Models\Video::where('uuid',$uuid)->get())==0){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        $task = Models\Shadowing::where('id_video',$task[0]->id)->get();
        $result['nomor_shadowing'] = count($task)+1;
    
        return response()->json(['message'=>'Success','data'
        => $result]);
    }
}
