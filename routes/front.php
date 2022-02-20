<?php

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Front\UserProfileController;

Route::get('/video/{secret}', 'VideoController@find')->name("get_file");
Route::get('/video/secret/{secret}', 'VideoController@playVideoWithSecret')->name('video_secret')->middleware('signed');


Route::get('/file/{secret}', 'PreventDownloadFileController@find')->name("get_file");
Route::get('/file/secret/{secret}', 'PreventDownloadFileController@FileWithSecret')->name('file_secret')->middleware('signed');


Route::group([
    'middleware' => 'web',
    'prefix'=>LaravelLocalization::setLocale(),
], function() {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

        Route::get('/home', [\App\Http\Controllers\Front\HomeController::class, 'home'])->name('home')->middleware('SetSideBarItemActiveBySession:user.home');



        Route::get('/certificate', [\App\Http\Controllers\Front\HomeController::class, 'certificate'])->name('certificate');
        Route::get('/congrats', [\App\Http\Controllers\Front\HomeController::class, 'congrats'])->name('congrats');
        Route::get('/change/role/{id}',  [UserProfileController::class, 'change_role'])->name('change.role');

        Route::get('/logout',  [UserProfileController::class, 'logout'])->name('logout');
        Route::get('/info', [UserProfileController::class, 'info'])->name('info');
        Route::post('/info/{id}/upadte', [UserProfileController::class, 'update'])->name('update');
        Route::get('/change_password', [UserProfileController::class, 'change_password'])->name('change_password');
        Route::post('/save_password', [UserProfileController::class, 'save_password'])->name('save_password');



        Route::get('/course_details/{course_id}', [\App\Http\Controllers\Front\CourseController::class, 'course_details'])->name('course_details');
        Route::get('/preview-content/{content_id}', [\App\Http\Controllers\Front\CourseController::class, 'preview_content'])->name('course_preview');
        Route::get('resume/{course_id}', [\App\Http\Controllers\Front\CourseController::class, 'resume'])->name('resume');
        Route::post('rate', [\App\Http\Controllers\Front\CourseController::class, 'user_rate'])->name('rate');
        Route::post('update_completed_status', [\App\Http\Controllers\Front\CourseController::class, 'update_completed_status'])->name('update_completed_status');
        Route::get('/pdf/save_page', [\App\Http\Controllers\Front\CourseController::class, 'save_page'])->name('save_page');
        Route::post('/flag_content', [\App\Http\Controllers\Front\CourseController::class, 'flag_content'])->name('flag_content');




        Route::get('/exam/{exam_id}', [\App\Http\Controllers\Front\ExamController::class, 'exam'])->name('exam');
        Route::get('preview/exam/{exam_id}', [\App\Http\Controllers\Front\ExamController::class, 'preview_exam'])->name('preview.exam');
        Route::get('exam/{user_exams_id}/details', [\App\Http\Controllers\Front\ExamController::class, 'attempt_details'])->name('attempt_details.exam');
        Route::get('review/exam/{exam_id}', [\App\Http\Controllers\Front\ExamController::class, 'review_exam'])->name('review.exam');
        Route::post('exam/add_answers', [\App\Http\Controllers\Front\ExamController::class, 'add_answers'])->name('exam.add_answers');




        Route::get('/messages/inbox', [\App\Http\Controllers\Training\MessageController::class,'inbox'])->name('messages.inbox');
        Route::get('/add_message', [\App\Http\Controllers\Training\MessageController::class,'addMessage'])->name('add_message');
        Route::get('/send_message', [\App\Http\Controllers\Training\MessageController::class,'sendMessage'])->name('send_message');
        Route::get('/reply_message/{id}', [\App\Http\Controllers\Training\MessageController::class,'replyMessage'])->name('reply_message');
        Route::get('/add_reply', [\App\Http\Controllers\Training\MessageController::class,'addReply'])->name('add_reply');
        Route::get('/search_subject', [\App\Http\Controllers\Training\MessageController::class,'searchSubject'])->name('search_subject');




// Zoom Test
//        Route::get('zoom/join', [UserProfileController::class, 'join_zoom'])->name('join_zoom');
//        Route::post('zoom/add/join', [UserProfileController::class, 'add_join_zoom'])->name('add.join_zoom');
//        Route::get('zoom/meeting', [UserProfileController::class, 'meeting'])->name('meeting');


    });
});


