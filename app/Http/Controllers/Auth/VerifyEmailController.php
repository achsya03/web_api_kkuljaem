<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    
    public function __invoke(Request $request)
    {
        $validation = new Helper\ValidationController('verifyUser');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            $result = "Operasi Gagal";

            return response()->json(['message'=>'Failed','info'=>$result]);#,'input'=>$return_data
        }

        $user = User::where('web_token',$request->token)->get();
        if(count($user)==0){
            return response()->json(['message'=>'Failed','info'
            => 'Token Tidak Sesuai']);
        }
        
        $old_web_token = $request->token;
        $web_token = $validation->data['web_token'];

        $data = [
            'old_web_token'  => $old_web_token,
            'password'       => bcrypt(request('password')),
            'web_token'      => $web_token
        ];

        $input = new Helper\UpdateController('verifyUser',$data);
        
        return response()->json(['message'=>'Succcess','info'
        => 'Verifikasi Berhasil']);
    }
}
