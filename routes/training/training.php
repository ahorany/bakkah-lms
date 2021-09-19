<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');
<<<<<<< HEAD

        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');
=======
        
        
        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');

        Route::get('/contents', 'ContentController@contents')->name('contents');
        Route::get('/add_section', 'ContentController@add_section')->name('add_section');
        Route::get('/showModal', 'ContentController@showModal')->name('showModal');
        
        
        
>>>>>>> c89d1697efbbad74dee21aa845b4144b3241f548
    });
});

