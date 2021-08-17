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
                $arr1['uuid_gambar'] = $post[$i]->postImage[$j]->uuid;
            }
            $pos[$i] = [
                'judul' => $post[$i]->judul,
                'deskripsi' => $post[$i]->deskripsi,
                'tema' => $post[$i]->theme->judul,
                'nama_pengirim' => $post[$i]->user->nama,
                'tgl_post' => $post[$i]->created_at,
                'jml_like' => $post[$i]->jml_like,
                'jml_komen' => $post[$i]->jml_komen,
                'gambar' => $arr1,
                'uuid_post' => $post[$i]->uuid
            ];
        }

        return $pos;
    }
}
