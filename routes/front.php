<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Front\UserProfileController;

Route::group([
    'middleware' => 'web',
    'prefix'=>LaravelLocalization::setLocale(),
], function() {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {//'prefix'=>'user',

        Route::get('/dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/home', [UserProfileController::class, 'home'])->name('home');
        Route::get('/info', [UserProfileController::class, 'info'])->name('info');
        Route::post('/info/{id}/upadte', [UserProfileController::class, 'update'])->name('update');

        Route::get('/my-courses', [UserProfileController::class, 'my_courses'])->name('my_courses');


        Route::get('/exercise', [UserProfileController::class, 'exercise'])->name('exercise');
        Route::get('/exams', [UserProfileController::class, 'exams'])->name('exams');
//
        Route::get('/file', [UserProfileController::class, 'file'])->name('file');
//        Route::get('/exercise', [UserProfileController::class, 'exercise'])->name('exercise');
//        Route::get('/exams', [UserProfileController::class, 'exams'])->name('exams');

        Route::get('/course_details/{course_id}', [UserProfileController::class, 'course_details'])->name('course_details');
        Route::get('/preview-content/{content_id}', [UserProfileController::class, 'course_preview'])->name('course_preview');
        Route::get('/exam/{exam_id}', [UserProfileController::class, 'exam'])->name('exam');
        Route::get('preview/exam/{exam_id}', [UserProfileController::class, 'preview_exam'])->name('preview.exam');
        Route::get('exam/{user_exams_id}/details', [UserProfileController::class, 'attempt_details'])->name('attempt_details.exam');
        Route::get('review/exam/{exam_id}', [UserProfileController::class, 'review_exam'])->name('review.exam');
        Route::post('exam/add_answers', [UserProfileController::class, 'add_answers'])->name('exam.add_answers');
        Route::post('rate', [UserProfileController::class, 'user_rate'])->name('rate');


        Route::get('/certifications', [UserProfileController::class, 'certifications'])->name('certifications');
        Route::get('/downloadCertifications/{id}', [UserProfileController::class, 'downloadCertifications'])->name('downloadCertifications');
        Route::get('/previewCertifications/{id}', [UserProfileController::class, 'previewCertifications'])->name('previewCertifications');
        Route::get('/payment_info', [UserProfileController::class, 'payment_info'])->name('payment_info');
        Route::get('/referral', [UserProfileController::class, 'referral'])->name('referral');
        Route::get('/invoice', [UserProfileController::class, 'invoice'])->name('invoice');
        Route::get('/request_tickets', [UserProfileController::class, 'request_tickets'])->name('request_tickets');
        Route::get('/change_password', [UserProfileController::class, 'change_password'])->name('change_password');
        Route::post('/save_password', [UserProfileController::class, 'save_password'])->name('save_password');
        Route::get('/wishlist', [UserProfileController::class, 'wishlist'])->name('wishlist');
        Route::get('/payment-history',  [UserProfileController::class, 'payment_history'])->name('payment_history');
        Route::get('/logout',  [UserProfileController::class, 'logout'])->name('logout');

        Route::get('/login', [UserProfileController::class, 'login'])->name('login');
        Route::post('/login', [UserProfileController::class, 'loginSubmit'])->name('loginSubmit');

        Route::get('/register', [UserProfileController::class, 'register'])->name('register');
        Route::post('/register', [UserProfileController::class, 'registerSubmit'])->name('registerSubmit');

        Route::get('/password/reset', [UserProfileController::class, 'passwordReset'])->name('passwordReset');
        Route::post('/password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('resetSubmit');


        Route::get('/request_tickets/{type?}', [UserProfileController::class, 'myComplaints'])->name('my_complaints');
        Route::get('/request_tickets/add/{type?}', [UserProfileController::class, 'complaintView'])->name('complaint');
        Route::post('/request_tickets/send_complaint', [UserProfileController::class, 'complaintStore'])->name('send_complaint');

        Route::get('/messages/inbox', [\App\Http\Controllers\Training\MessageController::class,'inbox'])->name('messages.inbox');
        Route::get('/add_message', [\App\Http\Controllers\Training\MessageController::class,'addMessage'])->name('add_message');
        Route::get('/send_message', [\App\Http\Controllers\Training\MessageController::class,'sendMessage'])->name('send_message');
        Route::get('/reply_message/{id}', [\App\Http\Controllers\Training\MessageController::class,'replyMessage'])->name('reply_message');
        Route::get('/add_reply', [\App\Http\Controllers\Training\MessageController::class,'addReply'])->name('add_reply');
        Route::get('/search_subject', [\App\Http\Controllers\Training\MessageController::class,'searchSubject'])->name('search_subject');

//        Route::get('/messages', [UserProfileController::class, 'getMessages'])->name('messages');
//        Route::get('/add_message', [UserProfileController::class, 'addMessage'])->name('add_message');
//        Route::get('/send_message', [UserProfileController::class, 'sendMessage'])->name('send_message');
//        Route::get('/reply_message/{id}', [UserProfileController::class, 'replyMessage'])->name('reply_message');
//        Route::get('/add_reply', [UserProfileController::class, 'addReply'])->name('add_reply');
//        Route::get('/search_subject', [UserProfileController::class, 'searchSubject'])->name('search_subject');

    });
});

//Route::get('/redirect/{service}', [AuthSocialController::class, 'redirect']);
//Route::get('/callback/{service}', [AuthSocialController::class, 'callback']);
