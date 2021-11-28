<?php

use App\Http\Controllers\Auth\TwoFactorController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale().'/twofactor/verify',
    'as'=>'twofactor.verify.',
], function(){
    Route::get('/', [TwoFactorController::class, 'verify'])->name('index');//->only(['index', 'store']);
    Route::post('store', [TwoFactorController::class, 'store'])->name('store');//->only(['index', 'store']);
    Route::get('/resend', [TwoFactorController::class, 'resend'])->name('resend');
});

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor','checkUserType'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'user', 'as'=>'admin.'], function(){
//
//        Route::resource('users', 'UserController');
//        Route::patch('/users/{user}/restore', 'UserController@restore')->name('users.restore');
//        Route::get('/users/{user}/change-password', 'UserController@changePassword')->name('users.changePassword');
//        Route::patch('/users/{user}/change-password', 'UserController@savePassword')->name('users.savePassword');
//
//        Route::resource('roles', 'RoleController');
//        Route::patch('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');

        Route::resource('details', 'DetailController');
        Route::patch('/details/{detail}/restore', 'AccordionController@restore')->name('details.restore');

        Route::resource('accordions', 'AccordionController');
        Route::patch('/accordions/{accordion}/restore', 'AccordionController@restore')->name('accordions.restore');

//		Route::get('/home', 'HomeController@index')->name('home');
//		Route::get('/general_questions', 'Survey2ConnectController@generalQuestions')->name('general_questions');
//		Route::get('/survey_questions', 'Survey2ConnectController@surveyQuestions')->name('survey_questions');

        //Route::get('/posts/import_from_wp', 'PostController@import_from_wp')->name('posts.import_from_wp');
//		Route::resource('posts', 'PostController');
//		Route::get('/posts/{locale?}/{locale_id}/trans', 'PostController@trans')->name('posts.trans');
//		Route::patch('/posts/{post}/restore', 'PostController@restore')->name('posts.restore');
//
//		Route::resource('constants', 'ConstantController');
//		Route::patch('/constants/{constant}/restore', 'ConstantController@restore')->name('constants.restore');
//
//
//
//        Route::resource('partners', 'PartnerController');
//        Route::patch('/partners/{partner}/restore', 'PartnerController@restore')->name('partners.restore');
//


//        Route::resource('testimonials', 'TestimonialController');
//        Route::patch('/testimonials/{testimonial}/restore', 'TestimonialController@restore')->name('testimonials.restore');
//
//        Route::get('contacts/export/', 'ContactController@export')->name('contacts.export');
//        Route::resource('contacts', 'ContactController');
//        Route::patch('/contacts/{contact}/restore', 'ContactController@restore')->name('contacts.restore');
//
//
//         Route::resource('/redirects', 'RedirectController');
//         Route::patch('/redirects/{redirect}/restore', 'RedirectController@restore')->name('redirects.restore');
//
//         Route::resource('/social_media', 'SocialMediaController');
//         Route::patch('/social_media/{social_media}/restore', 'SocialMediaController@restore')->name('social_media.restore');
//
//         Route::resource('/ticket', 'TicketController');
//         Route::patch('/ticket/{ticket}/restore', 'TicketController@restore')->name('ticket.restore');
//
//         Route::resource('/agreement', 'AgreementController');
//         Route::patch('/agreement/{agreement}/restore', 'AgreementController@restore')->name('agreement.restore');
//         Route::get('agreement/{id}/comment', 'AgreementController@comment')->name('agreement.comment');
//         Route::get('agreement/{id}/comments/delete', 'AgreementController@deleteComments')->name('agreement.delete');
//         Route::get('agreement/getNotes/{agreementId}', 'AgreementController@getNotes')->name('agreement.getNotes');
//         Route::post('agreement/addNote', 'AgreementController@addNote')->name('agreement.addNote');
//
//         Route::resource('/complaint', 'ComplaintController');
//         Route::patch('/complaint/{complaint}/restore', 'ComplaintController@restore')->name('complaint.restore');
//
//         Route::get('complaint/{id}/comment', 'ComplaintController@comment')->name('complaint.comment');
//         Route::get('complaint/{id}/comments/delete', 'ComplaintController@deleteComments')->name('complaint.delete');
//         Route::get('complaint/getNotes/{complaintId}', 'ComplaintController@getNotes')->name('complaint.getNotes');
//         Route::post('complaint/addNote', 'ComplaintController@addNote')->name('complaint.addNote');
//
//         Route::resource('/performance', 'PerformanceController');
//         Route::patch('/performance/{performance}/restore', 'PerformanceController@restore')->name('performance.restore');
//
//         Route::resource('/interestings', 'InterestingEmailController');
//         Route::patch('/interestings/{interesting}/restore', 'InterestingEmailController@restore')->name('interestings.restore');
//
//         Route::resource('/robot', 'RobotController');
//
//         Route::resource('learn_complaints', 'LearnComplaintsController');
//         Route::patch('/learn_complaints/{learn_complaints}/restore', 'LearnComplaintsController@restore')->name('learn_complaints.restore');
//         Route::get('get_learn_complaints/{id}', 'LearnComplaintsController@get_complaints')->name('get_complaints');
//
//        //Start Career
//        Route::resource('careers', 'CareerController');
//        Route::patch('/careers/{career}/restore', 'CareerController@restore')->name('careers.restore');
//        //End Career
//
//        Route::group(['prefix'=>'seo', 'as'=>'SEO.'], function(){
//            Route::get('edit/{infa_id}', 'SeoController@edit')->name('edit');
//            Route::patch('{infastructure}/update', 'SeoController@update')->name('update');
//        });

    });
});
