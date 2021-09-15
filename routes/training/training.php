<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');

        // Route::get('session/attendant/', 'SessionController@sessionAttendant')->name('sessions.attendance');
        // Route::get('session/attendant/store', 'SessionController@sessionAttendantStore')->name('sessions.attendance.store');
        // Route::post('session/attendant/importzoom', 'SessionController@importzoom')->name('sessions.importzoom');

        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');

        
    });
});

