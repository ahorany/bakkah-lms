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

        Route::get('/contents', 'ContentController@contents')->name('contents');
        Route::post('/add_section', 'ContentController@add_section')->name('add_section');
        Route::post('/update_content', 'ContentController@update_content')->name('update_content');
        Route::get('/delete_content', 'ContentController@delete_content')->name('delete_content');
        Route::get('/showModal', 'ContentController@showModal')->name('showModal');
        Route::get('/showChildModal', 'ContentController@showChildModal')->name('showChildModal');
    });
});

