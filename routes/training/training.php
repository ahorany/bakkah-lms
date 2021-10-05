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
        Route::post('/update_section', 'ContentController@update_section')->name('update_section');
        Route::post('/add_content', 'ContentController@add_content')->name('add_content');
        Route::post('/update_content', 'ContentController@update_content')->name('update_content');
        Route::get('/delete_content', 'ContentController@delete_content')->name('delete_content');


        Route::get('/add_questions/{exam_id}', 'QuestionController@add_questions')->name('add_questions');
        Route::post('/add_question', 'QuestionController@add_question')->name('add_question');
        Route::post('/add_answer', 'QuestionController@add_answer')->name('add_answer');
        Route::get('/delete_answer', 'QuestionController@delete_answer')->name('delete_answer');
        Route::post('/update_answer', 'QuestionController@update_answer')->name('update_answer');


    });
});

