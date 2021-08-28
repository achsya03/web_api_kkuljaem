<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;
use Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    private $rules = [];

    private $messages = [];

    public function __invoke(Request $request)
    {
        $validation = new Helper\ValidationController('authUser');
        $this->rules = $validation->rules;
        $this->messages = $validation->messages;

        
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            // $return_data=$request->all();
            // unset($return_data['password']);
            // unset($return_data['password_confirmation']);
            return response()->json(['message'=>'Failed','info'=>$validator->errors()]);#,'input'=>$return_data
        }

        $web_token = $validation->data['web_token'];

        $info_pengguna=[
            #'nama' => request('nama'),
            'email' => request('email'),
            'web_token' => $web_token,
        ];

        if(!$kirim_email = MailController::sendEmail($info_pengguna,"verify")){
            return response()->json(['message'=>'Failed','info'
            => 'Email Gagal Dikirim']);
        }
        if($kirim_email != 'Mail Sended'){
            return response()->json(['message'=>'Failed','info'
            => 'Email Gagal Dikirim, Kirim Lagi']);
        }
        $uuid = $validation->data['uuid'];

        $data = [
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'web_token' => $web_token,
            'jenis_pengguna' => 0,
            'jenis_akun' => 0,
            'uuid' => $uuid
        ];

        $input = new Helper\InputController('authUser',$data);
        
        return response()->json(['message'=>'Success',
        'info'=> $kirim_email->message]);
    }
    /*public function apiRequest(Request $request){
        
        
    }*/

    /*public function webRequest(Request $request){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
  
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $web_token = $this->randomToken(144);

        $info_pengguna=[
            #'nama' => request('nama'),
            'email' => request('email'),
            'web_token' => $web_token,
        ];

        if(!$kirim_email=MailController::sendEmail($info_pengguna,"verify")){
            return response("Email Not Send.", 401);
        }

        User::create([
            #'nama' => request('nama'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'web_token' => $web_token,
            'jenis_pengguna' => 0,
            'jenis_akun' => 0
        ]);
        
        
        return redirect()->route('login');
    }*/
}