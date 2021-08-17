<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\DetailMentor;
use App\Models\DetailStudent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\DetailMentorController;
use App\Http\Controllers\Auth\DetailStudentController;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Session;
use Cloudinary;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private $rules = [
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|confirmed',
        'password_confirmation' => 'required',
        #'jenis_pengguna'        => 'required' # 0 Siswa, 1 Mentor, 2 Admin];
    ];

    private $messages = [
        'email.required'                  => 'Email wajib diisi',
        'email.email'                     => 'Email tidak valid',
        'email.unique'                    => 'Email sudah terdaftar',
        'password.required'               => 'Password wajib diisi',
        'password.confirmed'              => 'Password tidak sama dengan konfirmasi password',
        'password_confirmation.required'  => 'Konfirmasi password wajib diisi',
        #'jenis_pengguna.required'         => 'Jenis Penggguna wajib diisi'
    ];

    public static function randomToken($number){
        $web_token = Str::random($number);

        while(count(User::where('web_token',$web_token)->get())>0){
            $web_token = Str::random(144);
            #return response(User::where('web_token',"177Z2jb4RfdgDYAGp04lDBuqPLFeseGb")->get());
        }
        return $web_token;
    }

    public function checkData(Request $request)
    {
        if($request->user()==NULL){
            return response("You are Logout", 205);
        }
        return $request->user()->nama;
    }

    private function getUuid(){
        $uuid = (string) str_replace('-','',Str::uuid());

        $uuid_exist = count(User::where('uuid',$uuid)->get());
        while ($uuid_exist > 0) {
            $uuid = (string) str_replace('-','',Str::uuid());
            $uuid_exist = count(User::where('uuid',$uuid)->get());
        }

        return $uuid;
    }


    private function UUidCheck($gambar){
        if(count($gambar)>1){
            return response()->json(['message'=>"Only One Image Every Data"],401);
        }

        if(!$uploadedFileUrl = Cloudinary::uploadFile($gambar[0]->getRealPath(),[
            'folder' => date("Y-m-d")."/Profile",
            'use_filename' => 'True',
            'filename_override' => date('mdYhis')
        ])){
            return response()->json(['message'=>'Image Upload Failed','input'=>$return_data]);
        }

        return $uploadedFileUrl;
    }

    private function addDataMentor(){
        $this->rules += [
            'nama'                  => 'required',
            'bio'                   => 'required',
            'awal_mengajar'         => 'required',
            'url_foto'              => 'required'
        ];
    
        $this->messages += [
            'nama.required'                   => 'Nama wajib diisi',
            'bio.required'                    => 'Password wajib diisi',
            'awal_mengajar.required'          => 'Konfirmasi password wajib diisi',
            'url_foto.required'               => 'Foto wajib diisi'
        ];
    }

    private function addDataStudent(){
        $this->rules += [
            'nama'                  => 'required',
            'alamat'                  => 'required',
            'jenis_kel'               => 'required',
            'tgl_lahir'               => 'required'
        ];
    
        $this->messages += [
            'nama.required'                   => 'Nama wajib diisi',
            'bio.required'                    => 'Bio wajib diisi',
            'jenis_kel.required'              => 'Jenis Kelamin password wajib diisi',
            'tgl_lahir.required'              => 'Tanggal Lahir wajib diisi'
        ];

    }

    private function jenisPenggunaCheck($jenis_pengguna){
        $arr = [-1,0,1];
        if(!$result = in_array($jenis_pengguna,$arr)){
            return response()->json(['message'=>"Jenis Pengguna Not Valid"]);
        }
        if($jenis_pengguna==-1){
            $jenis_pengguna = 0;
        }elseif($jenis_pengguna==0){
            $this->addDataStudent();
        }elseif($jenis_pengguna==1 or $request->jenis_pengguna==2){
            $this->addDataMentor();
        }
    }

    public function addData(Request $request){
        $jenis_pengguna=$request->jenis_pengguna;
        $this->jenisPenggunaCheck($jenis_pengguna);
        
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        #echo $web_token;
        if($validator->fails()){
            $return_data=$request->all();
            unset($return_data['url_foto']);
            unset($return_data['password']);
            unset($return_data['password_confirmation']);
            return response()->json(['message'=>$validator->errors(),'input'=>$return_data]);
        }

        $web_token = $this->randomToken(144);

        $info_pengguna=[
            #'nama' => request('nama'),
            'email' => request('email'),
            'web_token' => $web_token,
        ];

        if(!$kirim_email=MailController::sendEmail($info_pengguna,"verify")){
            return response()->json(['message'
            => 'Email Not Send'],401);
        }
        $uuid=$this->getUuid();

        User::create([
            'nama' => request('nama'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'web_token' => $web_token,
            'jenis_pengguna' => $jenis_pengguna,
            'jenis_akun' => 0,
            'uuid' => $uuid
        ]);

        $id_user = User::where('uuid', $uuid)->first()->id;
        
        if($request->jenis_pengguna==1 or $request->jenis_pengguna==2){
            $gambar1 = $request->url_foto;
            $uploadedFileUrl1 = $this->UUidCheck($gambar1);
            $uuid1 = DetailMentorController::getUuid();
            $awal_mengajar = date_format(date_create($request->awal_mengajar),"Y/m/d");
            $data_user=[
                'id_users'  => $id_user,
                'bio'       => $request->bio,
                'awal_mengajar' => $awal_mengajar,
                'url_foto' => $uploadedFileUrl1->getSecurePath(),
                'foto_id' => $uploadedFileUrl1->getPublicId(),
                'uuid' => $uuid1
            ];
            DetailMentorController::addData($data_user);
        }elseif($request->jenis_pengguna==0){
            $uuid1 = DetailStudentController::getUuid();
            $tgl_lahir = date_format(date_create($request->tgl_lahir),"Y/m/d");
            $data_user=[
                'id_users'  => $id_user,
                'alamat'       => $request->alamat,
                'jenis_kel' => $request->jenis_kel,
                'tgl_lahir' => $tgl_lahir,
                'uuid' => $uuid1
            ];
            DetailStudentController::addData($data_user);
        }
        
        return response()->json(['message'
        => 'Your Email Registration Success. Please Activate From Your Email.'],200);
    }

    public function updateData(Request $request)
    {
        $email=$request->uuid;
        $val=array('nama');
        for($i=0;$i<count($val);$i++){
            if($request->has($val[$i])){
                if($request[$val[$i]]==null){
                    return response("Please fill value ".$val[$i], 401);
                }
                if($val[$i]=='jenis_kel' and $request[$val[$i]]!=0 and $request[$val[$i]]!=1){
                    return response("Fill jenis kelamin with 0 or 1 ", 401);
                }
                User::where('email','=',$request->user()->email)
                    ->update([$val[$i] => $request[$val[$i]]]);
                #return $request[$val[$i]];
            }
        }
        return response()->json(['message'
        => 'Data Uploaded'],200);
    }

    public function allData(Request $request)
    {
        $list_user = User::all();
        return $list_user;
    }
}