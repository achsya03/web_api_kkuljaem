<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function addPost(Request $request)
    {

    }

    public function addComment(Request $request)
    {

    }

    public function updatePost(Request $request){

    }

    public static function getPost($post){
        $pos = [];
        for($i = 0;$i < count($post); $i++){
            $arr1 = [];
            for($j = 0;$j < count($post[$i]->postImage);$j++){
                $arr1['url_gambar'] = $post[$i]->postImage[$j]->url_gambar;
                $arr1['gambar_uuid'] = $post[$i]->postImage[$j]->uuid;
            }
            $pos[$i] = [
                'judul' => $post[$i]->judul,
                'deskripsi' => $post[$i]->deskripsi,
                'tema' => $post[$i]->theme->judul,
                'nama_pengirim' => $post[$i]->user->nama,
            ];
                if($post[$i]->user->foto != null){
                    $pos[$i] += [
                        'foto_pengirim' => $post[$i]->user->foto,
                    ];
                }
            $pos[$i] += [
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
            ];
                if($arr1 != null){
                    $pos[$i] += [
                        'gambar' => $arr1,
                    ];
                }
            $pos[$i] += [
                'post_uuid' => $post[$i]->uuid
            ];
        }

        return $pos;
    }
}
