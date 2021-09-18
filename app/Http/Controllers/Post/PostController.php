<?php

namespace App\Http\Controllers\Post;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Validator;

class PostController extends Controller
{

    public function __construct(Request $request){
        $this->middleware('auth');
    }
    
    private function checkPostNum($user,$type)
    {
        $post = Models\Post::where('id_user',$user)
                ->whereDate('created_at',date('Y/m/d'))
                ->where('type',$type)->get();
        return count($post);
    }
    private function checkCommentNum($user,$type)
    {
        $comment = Models\Comment::join('post','post.id','=','comment.id_post')
                ->where('post.id_user',$user)
                ->whereDate('post.created_at',date('Y/m/d'))
                ->where('post.jenis',$type)->get();
        return count($comment);
    }
    public function addQnAPost(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $video = Models\Video::where('uuid',$uuid)->get();
        if(count($video)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $test = $this->checkPostNum($request->user()->id,'qna');

        // if($test >= 5){
        //     return response()->json([
        //         'message' => 'Failed',
        //         'error' => 'Anda sudah posting 5 kali hari ini'
        //     ]);
        // }

        $theme = Models\Theme::where('judul',$video[0]->uuid)->get();
        //return $theme;
        if(count($theme) == 0){
            #input theme
            $validation = new Helper\ValidationController('theme');
            $uuid1 = $validation->data['uuid'];

            $data = [
                'judul'         => $video[0]->uuid,
                'jml_post'      => 0,
                'jml_like'      => 0,
                'jml_comment'   => 0,
                'uuid'          => $uuid1
            ];

            $input = new Helper\InputController('theme',$data);

            $theme = Models\Theme::where('uuid',$uuid1)->get();

            $validation2 = new Helper\ValidationController('videoTheme');
            $uuid2 = $validation2->data['uuid'];
            #input video theme
            $data = [
                'id_video'  => $video[0]->id,
                'id_theme'  => $theme[0]->id,
                'uuid'      => $uuid2
            ];
            $input = new Helper\InputController('videoTheme',$data);
        }

        $validation3 = new Helper\ValidationController('post');
        
        $this->rules = $validation3->rules;
        $this->messages = $validation3->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        //$return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        $uuid3 = $validation3->data['uuid'];

        #input post
        $data = [
            'id_user' => $request->user()->id,
            'id_theme' => $theme[0]->id,
            'judul' => $request->judul,
            'jenis' => 'qna',
            'deskripsi' => $request->deskripsi,
            'jml_like' => 0,
            'jml_komen' => 0,
            'stat_post' => 0,
            'uuid' => $uuid3
        ];
        $input = new Helper\InputController('post',$data);

        #update theme
        $data = [
            'jml_post'  => $theme[0]->jml_post+1,
            'uuid'      => $theme[0]->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);


        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input QnA Berhasil'
        ]);
    }

    public function addForumPost(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $theme = Models\Theme::where('uuid',$uuid)->get();
        if(count($theme)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        $test = $this->checkPostNum($request->user()->id,'forum');

        if($test >= 5){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Anda sudah posting 5 kali hari ini'
            ]);
        }
        if(isset($request->post_image)){
            if(count($request->post_image)>3){
                return response()->json([
                    'message' => 'Failed',
                    'error' => 'Gambar Yang di upload maksimal 3'
                ]);
            }
        }

        $validation3 = new Helper\ValidationController('post');
        
        $this->rules = $validation3->rules;
        $this->messages = $validation3->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        //$return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }
        $uuid3 = $validation3->data['uuid'];

        #input post
        $data = [
            'id_user' => $request->user()->id,
            'id_theme' => $theme[0]->id,
            'judul' => $request->judul,
            'jenis' => 'forum',
            'deskripsi' => $request->deskripsi,
            'jml_like' => 0,
            'jml_komen' => 0,
            'stat_post' => 0,
            'uuid' => $uuid3
        ];

        $input = new Helper\InputController('post',$data);

        $post = Models\Post::where('uuid',$uuid3)->first();
        if(isset($request->post_image)){
            for($i=0;$i<count($request->post_image);$i++){
                #input post
                $validation4 = new Helper\ValidationController('postImage');
                $uuid4 = $validation4->data['uuid'];

                $gambar1 = $request->post_image[$i];
                $uploadedFileUrl1 = $validation4->UUidCheck($gambar1,'Post/Forum');
                $data = [
                    'id_post'       => $post->id,
                    'url_gambar'    => $uploadedFileUrl1['getSecurePath'],
                    'gambar_id'     => $uploadedFileUrl1['getPublicId'],
                    'uuid'          => $uuid4
                ];
                $input = new Helper\InputController('postImage',$data);
            }
        }
        #update theme
        $data = [
            'jml_post'  => $theme[0]->jml_post+1,
            'uuid'      => $theme[0]->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Post Forum Berhasil'
        ]);
    }

    public function deletePost(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete comment
        $delete = Models\Post::where('uuid',$uuid)->delete();
        
        #update theme
        $data = [
            'jml_post'  => $post[0]->theme->jml_post-1,
            'uuid'      => $post[0]->theme->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Post Berhasil'
        ]);
    }

    public function addComment(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        if($post[0]->jenis=='forum'){
            $comment = $this->checkCommentNum($request->user()->id,'forum');
            if($comment>=5){               
                return response()->json([
                    'message' => 'Failed',
                    'error' => 'Anda sudah memberi komentar pada forum 5 kali hari ini'
                ]);
            }
        }

        $validation = new Helper\ValidationController('comment');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        //$return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $uuid1 = $validation->data['uuid'];
        #input comment
        $data = [
            'id_user' => $request->user()->id,
            'id_post' => $post[0]->id,
            'comment' => $request->komentar,
            'stat_comment' => 0,
            'uuid' => $uuid1
        ];
        
        $input = new Helper\InputController('comment',$data);


        #update post
        $data = [
            'jml_komen'  => $post[0]->jml_komen+1,
            'uuid'       => $post[0]->uuid
        ];
        $update = new Helper\UpdateController('post',$data);


        #update theme
        $data = [
            'jml_comment'  => $post[0]->theme->jml_comment+1,
            'uuid'         => $post[0]->theme->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);
        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Komentar Berhasil'
        ]);
    }

    public function deleteComment(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $comment = Models\Comment::where('uuid',$uuid)->get();
        if(count($comment)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete comment
        $delete = Models\Comment::where('uuid',$uuid)->delete();
        
        #update post
        $data = [
            'jml_komen'  => $comment[0]->post->jml_komen-1,
            'uuid'       => $comment[0]->post[0]->uuid
        ];
        $update = new Helper\UpdateController('post',$data);


        #update theme
        $data = [
            'jml_comment'  => $comment[0]->post[0]->theme->jml_comment-1,
            'uuid'         => $comment[0]->post[0]->theme->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);
        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Comment Berhasil'
        ]);
    }

    public function alertPost(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $validation = new Helper\ValidationController('postAlert');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        //$return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $uuid1 = $validation->data['uuid'];
        #input alertPost
        $data = [
            'id_user' => $request->user()->id,
            'id_post' => $post[0]->id,
            'komentar' => $request->komentar,
            'alert_status' => 0,
            'uuid' => $uuid1
        ];

        $input = new Helper\InputController('postAlert',$data);
        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Alert Post Berhasil'
        ]);
    }

    public function alertPostDelete(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete post
        $delete = Models\PostAlert::where('id_post',$post->id)
                ->where('id_user',$request->user()->id)->delete();

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Alert Post Berhasil'
        ]);
    }

    public function alertComment(Request $request)
    {
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $comment = Models\Comment::where('uuid',$uuid)->get();
        if(count($comment)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $validation = new Helper\ValidationController('commentAlert');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        //$return_data=$validator->validated();
        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $uuid1 = $validation->data['uuid'];

        #input alertPost
        $data = [
            'id_user' => $request->user()->id,
            'id_comment' => $comment[0]->id,
            'komentar' => $request->komentar,
            'alert_status' => 0,
            'uuid' => $uuid1
        ];

        $input = new Helper\InputController('commentAlert',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Alert Comment Berhasil'
        ]);
    }
  
    public function alertCommentDelete(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $comment = Models\Comment::where('uuid',$uuid)->get();
        if(count($comment)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete comment
        $delete = Models\CommentAlert::where('id_comment',$comment[0]->id)
                ->where('id_user',$request->user()->id)->delete();

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Alert Comment Berhasil'
        ]);
    }  
  
    public function addLike(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post[0]->postLike)==1){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        $validation = new Helper\ValidationController('postLike');
        // $this->rules = $validation->rules;
        // $this->messages = $validation->messages;

        // $validator = Validator::make($request->all(), $this->rules, $this->messages);
        // #echo $web_token;
        // //$return_data=$validator->validated();
        // if($validator->fails()){
        //     return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        // }

        $uuid1 = $validation->data['uuid'];
        #input alertPost
        $data = [
            'id_user' => $request->user()->id,
            'id_post' => $post[0]->id,
            'uuid' => $uuid1
        ];

        $input = new Helper\InputController('postLike',$data);

        #update post
        $data = [
            'jml_like'  => $post[0]->jml_like+1,
            'uuid'       => $post[0]->uuid
        ];
        $update = new Helper\UpdateController('post',$data);


        #update theme
        $data = [
            'jml_like'  => $post[0]->theme->jml_like+1,
            'uuid'         => $post[0]->theme->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Input Like Berhasil'
        ]);
    } 

    public function deleteLike(Request $request){
        $result = [];
        if(!$uuid = $request->token){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }
        
        $post = Models\Post::where('uuid',$uuid)->get();
        if(count($post[0]->postLike)==0){
            return response()->json([
                'message' => 'Failed',
                'error' => 'Token tidak sesuai'
            ]);
        }

        #delete like post
        $delete = Models\PostLike::where('id_post',$post[0]->id)
                ->where('id_user',$request->user()->id)->delete();

        #update post
        $data = [
            'jml_like'  => $post[0]->jml_like-1,
            'uuid'       => $post[0]->uuid
        ];
        $update = new Helper\UpdateController('post',$data);


        #update theme
        $data = [
            'jml_like'  => $post[0]->theme->jml_like-1,
            'uuid'         => $post[0]->theme->uuid
        ];
        $update = new Helper\UpdateController('theme',$data);

        return response()->json([
            'message' => 'Success',
            //'account' => $this->statUser($request->user()),
            'info'    => 'Proses Hapus Like Berhasil'
        ]);
    }

    public static function getPost($post,$userId){
        $pos = [];
        for($i = 0;$i < count($post); $i++){
            $arr1 = [];
            for($j = 0;$j < count($post[$i]->postImage);$j++){
                $arr1['url_gambar'] = $post[$i]->postImage[$j]->url_gambar;
                $arr1['gambar_uuid'] = $post[$i]->postImage[$j]->uuid;
            }
            $posting = 'False';
            $like = 'False';
            if($post[$i]->id_user==$userId){
                $posting = 'True';
            }
            if(count($likes = Models\PostLike::where('id_post',$post[$i]->id)
                            ->where('id_user',$userId)->get())>0){
                $like = 'True';
            }

            $pos[$i] = [
                'judul' => $post[$i]->judul,
                'user_posting' => $posting,
                'user_like' => $like,
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
