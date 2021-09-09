<?php

namespace App\Http\Controllers\Classes;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getData(Request $request){
        if(!$uuid=$request->token){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }

        $id_video = Models\Video::where('uuid',$uuid)->first();

        if($id_video==null){
            return response()->json(['message'=>'Failed','info'=>"Token Tidak Sesuai"]);
        }
        $result = [];

        $res = [
            'judul' => $id_video->judul,
            'keterangan' => $id_video->keterangan,
            'url_video' => $id_video->url_video,
            'jml_latihan' => $id_video->jml_latihan,
            'jml_shadowing' => $id_video->jml_shadowing,
            'video_uuid' => $id_video->uuid,
        ];

        $task = Models\Task::where('id_video',$id_video->id)->get();
        $arr = [];
        for($i=0;$i<count($task);$i++){
            $task1 = [
                'nomor' => $task[$i]->number,
                'pertanyaan' => $task[$i]->question->pertanyaan_teks,
                'jawaban' => $task[$i]->question->jawaban,
                'pertanyaan_uuid' => $task[$i]->question->uuid,
            ];
            $arr[$i] = $task1;
        }

        $res['task'] = $arr;

        $shadowing = Models\Shadowing::where('id_video',$id_video->id)->get();
        $arr = [];
        for($i=0;$i<count($shadowing);$i++){
            $shadowing1 = [
                'nomor' => $shadowing[$i]->number,
                'hangeul' => $shadowing[$i]->word->hangeul,
                'pelafalan' => $shadowing[$i]->word->pelafalan,
                'url_pengucapan' => $shadowing[$i]->word->url_pengucapan,
                'shadowing_uuid' => $shadowing[$i]->word->uuid,
            ];
            $arr[$i] = $shadowing1;
        }

        $res['shadowing'] = $arr;

        $result = $res;

        return response()->json(['message'=>'Success','data'
        => $result]);
    }
}
