<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');

        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');
    });
});

Route::group([
    'middleware' => 'web',
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::get('certificates/{id}', 'CertificateController@certificate')->name('certificate');
        Route::get('certificates-url/{id}', 'CertificateController@certificate_url')->name('certificate-url');
        Route::get('certificates-pdf/{id}', 'CertificateController@certificate_pdf')->name('certificate-pdf');

        Route::get('attendance/{id}', 'CertificateController@attendance')->name('attendance');
        Route::get('attendance-url/{id}', 'CertificateController@attendance_url')->name('attendance-url');
        Route::get('attendance-pdf/{id}', 'CertificateController@attendance_pdf')->name('attendance-pdf');

    });
});
