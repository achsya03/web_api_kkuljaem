<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserController;

use App\Http\Controllers\Auth;

use App\Http\Controllers\Banner;

use App\Http\Controllers\Classes;

use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'api/auth'], function () {
    Route::post('register', Auth\RegisterController::class);
    Route::post('login', Auth\LoginController::class);
    Route::post('logout', Auth\LogoutController::class);
    #Route::get('send-mail','MailController@sendEmail');
    Route::get('forget-password',Auth\ForgotPasswordController::class);
    Route::put('change-password',Auth\ChangePasswordController::class);
    Route::get('verify-mail',Auth\VerifyEmailController::class);
});

#==========================Student================================

Route::group(['prefix' => 'api/banner'], function () {
    Route::get('/', [Banner\BannerController::class,'allData']);
    Route::get('/detail', [Banner\BannerController::class,'detailData']);
});

Route::group(['prefix' => 'api/class'], function () {
    Route::get('/testimoni', [Classes\ClassController::class,'addData']);
});

Route::group(['prefix' => 'api/content'], function () {
    Route::get('/', [Banner\VideoController::class,'allDataByDate']);
});


Route::group(['prefix' => 'api/home'], function () {
    Route::get('/', [Helper\ShowController::class,'home']);
    Route::get('/banner', [Helper\ShowController::class,'banner']);
    Route::get('/word', [Helper\ShowController::class,'word']);
    Route::get('/video', [Helper\ShowController::class,'video']);
    Route::get('/search', [Helper\ShowController::class,'search']);
});

Route::group(['prefix' => 'api/classroom'], function () {
    Route::get('/', [Helper\ShowController::class,'classroom']);
    Route::get('/category', [Helper\ShowController::class,'classroomByCategory']);
    Route::get('/detail', [Helper\ShowController::class,'classroomDetail']);
    Route::get('/registered', [Helper\ShowController::class,'classroomRegistered']);
    Route::get('/mentor', [Helper\ShowController::class,'classroomMentorDetail']);
    Route::get('/detail/video', [Helper\ShowController::class,'classroomVideoDetail']);
    #Route::get('/testimoni', [Helper\ShowController::class,'testimoni']);
});

#==========================Student================================


Route::group(['prefix' => 'api/banner'], function () {
    Route::post('/', [Banner\BannerController::class,'addData']);
    Route::post('update', [Banner\BannerController::class,'updateData']);
    Route::get('/', [Banner\BannerController::class,'allData']);
    Route::get('/detail', [Banner\BannerController::class,'detailData']);
});

Route::group(['prefix' => 'api/video'], function () {
    Route::post('/', [Banner\VideoController::class,'addDataVideo']);
    Route::post('update', [Banner\VideoController::class,'updateDataVideo']);
    Route::get('/', [Banner\VideoController::class,'allDataVideo']);
    Route::get('/detail', [Banner\VideoController::class,'detailDataVideo']);
});



Route::group(['prefix' => 'api/word'], function () {
    Route::post('/', [Banner\WordController::class,'addDataWord']);
    Route::post('update', [Banner\WordController::class,'updateDataWord']);
    Route::get('/', [Banner\WordController::class,'allDataWord']);
    Route::get('/detail', [Banner\WordController::class,'detailDataWord']);
});

Route::group(['prefix' => 'api/class-category'], function () {
    Route::post('/', [Classes\ClassCategoryController::class,'addData']);
    Route::post('update', [Classes\ClassCategoryController::class,'updateData']);
    Route::get('/', [Classes\ClassCategoryController::class,'allData']);
    Route::get('/detail', [Classes\ClassCategoryController::class,'detailData']);
});

Route::group(['prefix' => 'api/class'], function () {
    Route::post('/', [Classes\ClassController::class,'addData']);
    Route::post('update', [Classes\ClassController::class,'updateData']);
    Route::get('/', [Classes\ClassController::class,'allData']);
    Route::get('/detail', [Classes\ClassController::class,'detailData']);
});


Route::group(['prefix' => 'api/content-quiz'], function () {
    Route::post('/', [Classes\ContentQuizController::class,'addData']);
    Route::post('update', [Classes\ContentQuizController::class,'updateData']);
    Route::get('/', [Classes\ContentQuizController::class,'allData']);
    Route::get('/detail', [Classes\ContentQuizController::class,'detailData']);
});

Route::group(['prefix' => 'api/content-video'], function () {
    Route::post('/', [Classes\ContentVideoController::class,'addData']);
    Route::post('update', [Classes\ContentVideoController::class,'updateData']);
    Route::get('/', [Classes\ContentVideoController::class,'allData']);
    Route::get('/detail', [Classes\ContentVideoController::class,'detailData']);
});

Route::group(['prefix' => 'api/option'], function () {
    Route::post('/', [Classes\OptionController::class,'addData']);
    Route::post('update', [Classes\OptionController::class,'updateData']);
    Route::get('/', [Classes\OptionController::class,'allData']);
    Route::get('/detail', [Classes\OptionController::class,'detailData']);
});

Route::group(['prefix' => 'api/question'], function () {
    Route::post('/', [Classes\QuestionController::class,'addData']);
    Route::post('update', [Classes\QuestionController::class,'updateData']);
    Route::get('/', [Classes\QuestionController::class,'allData']);
    Route::get('/detail', [Classes\QuestionController::class,'detailData']);
});


Route::group(['prefix' => 'api/teacher'], function () {
    Route::post('/', [Classes\TeacherController::class,'addData']);
    Route::post('update', [Classes\TeacherController::class,'updateData']);
    Route::get('/', [Classes\TeacherController::class,'allData']);
    Route::get('/detail', [Classes\TeacherController::class,'detailData']);
});

Route::group(['prefix' => 'api/testimoni'], function () {
    Route::post('/', [Classes\TestimoniController::class,'addData']);
    Route::post('update', [Classes\TestimoniController::class,'updateData']);
    Route::get('/', [Classes\TestimoniController::class,'allData']);
    Route::get('/detail', [Classes\TestimoniController::class,'detailData']);
});

Route::group(['prefix' => 'api/user'], function () {
    Route::post('update', [UserController::class, 'updateData']);
    Route::get('/', [UserController::class, 'allData']);
    Route::post('/', [UserController::class, 'addData']);
});
Route::get('/test', [HelperController::class, 'randomToken']);
Route::post('/upload', function (Request $request) {
    // $uploadedFileUrl = Cloudinary::uploadFile($request->file('file')->getRealPath(),[
    //     'folder' => date("Y-m-d"),
    //     'use_filename' => 'True',
    //     'filename_override' => 'aa'
    // ])->getPublicId();
    // return $uploadedFileUrl;
    #echo $request->file('file');
    #if($request->hasfile('file'))
    #     {
    #        foreach($request->file('file') as $file)
    #        {
    #            echo $file."-";
    #        }
    #     }
});

#Route::middleware('auth:api')->get('/user', function (Request $request) {
#    return $request->user();
#});
#Route::apiResource('products', ProductController::class);

// Route::get('/delete', function (Request $request) {
//     $uploadedFileUrl = Cloudinary::destroy("2021-08-04/Pengucapan/aa", array("resource_type"=>"video"));
//     return $uploadedFileUrl;
// });
#Route::get('app/verify-mail',VerifyEmailController::class);
#Route::get('/', function () {
#    return view('welcome');
#});
#Route::get('/test',[MailController::class, 'test1']);



#Route::get('user', [UserController::class, 'checkData']);


#Route::domain('localhost.myapp.com')->group(function () {
#    Route::view('/', 'Public/home');
#}); 

#Route::domain('student')->group(function($router){
#    return 'aa';
#});

#Route::put('update-user','App\Http\Controllers\MailController@updateUser');
#Route::get('products/{id}',  [ProductController::class, 'show']);
#Route::post('products/{id}',  [ProductController::class, 'store']);
#Route::put('products/{id}',  [ProductController::class, 'update']);
#Route::delete('products/{id}',  [ProductController::class, 'destroy']);
#Route::group([
#  'prefix' => 'v1', 
#  'as' => 'api.', 
#  'namespace' => 'Api\V1\Admin', 
#  'middleware' => ['auth:api']
#], function () {
#    Route::apiResource('projects', 'ProjectsApiController');
#});