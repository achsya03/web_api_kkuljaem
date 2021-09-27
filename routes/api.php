<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserController;

use App\Http\Controllers\Auth;

use App\Http\Controllers\Banner;
use App\Http\Controllers\Post;

use App\Http\Controllers\Classes;
use App\Http\Controllers\TestController;

use App\Http\Controllers\Helper;
use App\Http\Controllers\Payment;
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
    Route::post('register',         Auth\RegisterController::class);
    Route::post('login',            Auth\LoginController::class);
    Route::post('logout',           Auth\LogoutController::class);
    #Route::get('send-mail','MailController@sendEmail');
    Route::get('forget-password',   Auth\ForgotPasswordController::class);
    Route::put('change-password',   Auth\ChangePasswordController::class);
    Route::get('verify-mail',       Auth\VerifyEmailController::class);
});

#==========================Student================================

Route::group(['prefix' => 'api/banner'], function () {
    Route::get('/',         [Banner\BannerController::class,'allData']);
    Route::get('/detail',   [Banner\BannerController::class,'detailData']);
});

Route::group(['prefix' => 'api/class'], function () {
    Route::get('/testimoni', [Classes\ClassController::class,'addData']);
});

Route::group(['prefix' => 'api/content'], function () {
    Route::get('/',     [Banner\VideoController::class,'allDataByDate']);
});


Route::group(['prefix' => 'api/home'], function () {
    Route::get('/',         [Helper\ShowController::class,'home']);
    Route::get('/web',         [Helper\StudentWebController::class,'homeWeb']);
    Route::get('/banner',   [Helper\ShowController::class,'banner']);
    Route::get('/word',     [Helper\ShowController::class,'word']);
    Route::get('/video',    [Helper\ShowController::class,'video']);
    Route::get('/search',   [Helper\ShowController::class,'search']);
});

Route::group(['prefix' => 'api/classroom'], function () {
    Route::get('/',                     [Helper\ShowController::class,'classroom']);

    //Route::post('/student',             [Classes\StudentController::class,'studentAdd']);
    Route::post('/student-video',       [Classes\StudentController::class,'studentVideoAdd']);
    Route::post('/student-quiz',        [Classes\StudentController::class,'studentQuizAdd']);
    //Route::post('/student-quiz/answer', [Classes\StudentController::class,'studentQuizAdd']);

    Route::get('/category',             [Helper\ShowController::class,'classroomByCategory']);
    Route::get('/detail',               [Helper\ShowController::class,'classroomDetail']);
    Route::get('/registered',           [Helper\ShowController::class,'classroomRegistered']);
    Route::get('/mentor',               [Helper\ShowController::class,'classroomMentorDetail']);
    Route::get('/detail/video',         [Helper\ShowController::class,'classroomVideoDetail']);
    Route::get('/detail/quiz',          [Helper\ShowController::class,'classroomQuizDetail']);
    Route::get('/detail/more',          [Helper\ShowController::class,'classroomVideoMore']);
    Route::get('/detail/task',          [Helper\ShowController::class,'classroomVideoTask']);
    Route::get('/detail/shadowing',     [Helper\ShowController::class,'classroomVideoShadowing']);
    #Route::get('/testimoni', [Helper\ShowController::class,'testimoni']);
});


Route::group(['prefix' => 'api/forum'], function () {
    Route::get('/',                 [Helper\ShowController::class,'forum']);

    Route::post('/post',            [Post\PostController::class,'addForumPost']);##
    Route::delete('/post',          [Post\PostController::class,'deletePost']);#
    Route::post('/comment',         [Post\PostController::class,'addComment']);##
    Route::delete('/comment',       [Post\PostController::class,'deleteComment']);
    Route::post('/post/alert',      [Post\PostController::class,'alertPost']);#
    Route::delete('/post/alert',      [Post\PostController::class,'alertPostDelete']);
    Route::post('/comment/alert',   [Post\PostController::class,'alertComment']);#
    Route::delete('/comment/alert',   [Post\PostController::class,'alertCommentDelete']);#
    Route::post('/like',            [Post\PostController::class,'addLike']);
    Route::delete('/like',          [Post\PostController::class,'deleteLike']);

    Route::get('/detail',           [Helper\ShowController::class,'forumDetail']);
    Route::get('/popular',          [Helper\ShowController::class,'forumByThemePop']);
    Route::get('/latest',           [Helper\ShowController::class,'forumByThemeNew']);
    Route::get('/posting',          [Helper\ShowController::class,'forumByUser']);
});

Route::group(['prefix' => 'api/qna'], function () {
    Route::get('/',                 [Helper\ShowController::class,'qna']);

    Route::post('/post',            [Post\PostController::class,'addQnAPost']);##
    Route::delete('/post',          [Post\PostController::class,'deletePost']);#
    Route::post('/comment',         [Post\PostController::class,'addComment']);##
    Route::delete('/comment',       [Post\PostController::class,'deleteComment']);#
    Route::post('/post/alert',      [Post\PostController::class,'alertPost']);#
    Route::delete('/post/alert',      [Post\PostController::class,'alertPostDelete']);
    Route::post('/comment/alert',   [Post\PostController::class,'alertComment']);#
    Route::delete('/comment/alert',   [Post\PostController::class,'alertCommentDelete']);
    Route::post('/like',            [Post\PostController::class,'addLike']);
    Route::delete('/like',          [Post\PostController::class,'deleteLike']);

    Route::get('/video',            [Helper\ShowController::class,'qnaByVideo']);
    Route::get('/posting',          [Helper\ShowController::class,'qnaByUser']);
    Route::get('/detail',           [Helper\ShowController::class,'qnaDetail']);
});

Route::group(['prefix' => 'video/redirect'], function () {
    Route::get('/', [Helper\RedirectVideoController::class,'getVideo']);
});
#==========================Student================================

#==========================Admin/Mentor================================
Route::group(['prefix' => 'api/admin'], function () {
    Route::get('/', [Helper\AdminController::class,'dashboard']);
});

Route::group(['prefix' => 'api/admin/classroom-group'], function () {
    Route::post('/',        [Classes\ClassCategoryController::class,'addData']);
    Route::post('update',   [Classes\ClassCategoryController::class,'updateData']);
    Route::get('/',         [Classes\ClassCategoryController::class,'allData']);
    Route::get('/detail',   [Classes\ClassCategoryController::class,'detailData']);
    Route::delete('/delete',   [Classes\ClassCategoryController::class,'deleteData']);
});

Route::group(['prefix' => 'api/admin/classroom'], function () {
    Route::post('/',        [Classes\ClassController::class,'addData']);
    Route::delete('/',      [Classes\ClassController::class,'deleteData']);##
    Route::post('/update',  [Classes\ClassController::class,'updateData']);
    Route::get('/',         [Classes\ClassController::class,'allData']);
    Route::get('/category', [Classes\ClassController::class,'detailDataForClass']);
    Route::get('/add',      [Classes\ClassController::class,'getForAddData']);
    Route::get('/edit',     [Classes\ClassController::class,'detailData']);
    Route::get('/student',  [Classes\ClassController::class,'studentData']);
});

Route::group(['prefix' => 'api/admin/classroom/content'], function () {
    Route::get('/',             [Classes\ClassController::class,'classContent']);
    Route::get('/add',          [Classes\ClassController::class,'checkData']);

    Route::get('/quiz/all',     [Classes\ContentQuizController::class,'getData']);
    Route::get('/quiz',         [Classes\ContentQuizController::class,'checkData']);
    Route::post('/quiz',        [Classes\ContentQuizController::class,'addData']);
    Route::delete('/quiz',      [Classes\ContentQuizController::class,'deleteData']);#
    Route::get('/quiz/detail',  [Classes\ContentQuizController::class,'detailData']);
    Route::post('/quiz/update', [Classes\ContentQuizController::class,'updateData']);
    Route::get('/video/all',    [Classes\ContentVideoController::class,'getData']);
    Route::get('/video',        [Classes\ContentVideoController::class,'checkData']);
    Route::post('/video',       [Classes\ContentVideoController::class,'addData']);
    Route::delete('/video',     [Classes\ContentVideoController::class,'deleteData']);#
    Route::get('/video/detail', [Classes\ContentVideoController::class,'detailData']);
    Route::post('/video/update',[Classes\ContentVideoController::class,'updateData']);
});

Route::group(['prefix' => 'api/admin/classroom/content/video'], function () {
    Route::get('/task',         [Classes\TaskController::class,'checkData']);
    Route::post('/task',        [Classes\TaskController::class,'addData']);
    Route::delete('/task',      [Classes\TaskController::class,'deleteData']);##
    Route::get('/task/detail',  [Classes\TaskController::class,'detailData']);
    Route::post('/task/update', [Classes\TaskController::class,'updateData']);
});
Route::post('/test', [TestController::class,'test']);

Route::group(['prefix' => 'api/admin/classroom/content/video'], function () {
    Route::get('/shadowing',            [Classes\ShadowingController::class,'checkData']);
    Route::post('/shadowing',           [Classes\ShadowingController::class,'addData']);
    Route::delete('/shadowing',        [Classes\ShadowingController::class,'deleteData']);##
    Route::get('/shadowing/detail',     [Classes\ShadowingController::class,'detailData']);
    Route::post('/shadowing/update',    [Classes\ShadowingController::class,'updateData']);
});

  
Route::group(['prefix' => 'api/admin/classroom/content/quiz'], function () {
    Route::get('/exam',         [Classes\ExamController::class,'checkData']);
    Route::post('/exam',        [Classes\ExamController::class,'addData']);
    Route::delete('/exam',        [Classes\ExamController::class,'deleteData']);##
    Route::get('/exam/detail',  [Classes\ExamController::class,'detailData']);
    Route::post('/exam/update', [Classes\ExamController::class,'updateData']);
});

Route::get('/test', [TestController::class,'test']);
Route::get('/payment', [Payment\PaymentController::class,'show']);
Route::get('/payment', [Payment\PaymentController::class,'show']);
#==========================Admin/Mentor================================


Route::group(['prefix' => 'api/option'], function () {
    Route::post('/',        [Classes\OptionController::class,'addData']);
    Route::post('update',   [Classes\OptionController::class,'updateData']);
    Route::get('/',         [Classes\OptionController::class,'allData']);
    Route::get('/detail',   [Classes\OptionController::class,'detailData']);
});

Route::group(['prefix' => 'api/question'], function () {
    Route::post('/',        [Classes\QuestionController::class,'addData']);
    Route::post('update',   [Classes\QuestionController::class,'updateData']);
    Route::get('/',         [Classes\QuestionController::class,'allData']);
    Route::get('/detail',   [Classes\QuestionController::class,'detailData']);
});


Route::group(['prefix' => 'api/teacher'], function () {
    Route::post('/',        [Classes\TeacherController::class,'addData']);
    Route::post('update',   [Classes\TeacherController::class,'updateData']);
    Route::get('/',         [Classes\TeacherController::class,'allData']);
    Route::get('/detail',   [Classes\TeacherController::class,'detailData']);
});

Route::group(['prefix' => 'api/testimoni'], function () {
    Route::post('/',        [Classes\TestimoniController::class,'addData']);
    Route::post('update',   [Classes\TestimoniController::class,'updateData']);
    Route::get('/',         [Classes\TestimoniController::class,'allData']);
    Route::get('/detail',   [Classes\TestimoniController::class,'detailData']);
});

Route::group(['prefix' => 'api/user'], function () {
    Route::post('update',   [UserController::class, 'updateData']);
    Route::get('/',         [UserController::class, 'allData']);
    Route::post('/',        [UserController::class, 'addData']);
});
//Route::get('/test', [HelperController::class, 'randomToken']);
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