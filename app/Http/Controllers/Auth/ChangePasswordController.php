<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validation = new Helper\ValidationController('changePassUser');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if($validator->fails()){
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);
        }

        $user = User::where('web_token',$request->token)->get();
        if(count($user)==0){
            return response()->json(['message'=>'Failed','info'
            => 'Token Tidak Terdaftar']);
        }

        $old_web_token = $request->token;
        $web_token = $validation->data['web_token'];

        $data = [
            'old_web_token'  => $old_web_token,
            'password'       => bcrypt(request('password')),
            'web_token'      => $web_token
        ];

        $input = new Helper\UpdateController('changePassUser',$data); 
        
        return response()->json(['message'=>'Success','info'
        => 'Password Berhasil Diperbarui']);
    }
    /*public function apiRequest(Request $request){
        
    }*/

    /*public function webRequest(Request $request){
        $rules = [
            'password'              => 'required|confirmed'
        ];
  
        $messages = [
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
        ];
  
        $validator = Validator::make($request->all(), $rules, $messages);
  
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }
    }*/
}
