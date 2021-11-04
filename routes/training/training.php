<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor','checkUserType'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');

        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');

        Route::get('/units', 'UnitController@index')->name('units');
        Route::get('/delete_unit', 'UnitController@delete_unit')->name('delete_unit');
        Route::post('/add_unit', 'UnitController@add_unit')->name('add_unit');
        Route::post('/update_unit', 'UnitController@update_unit')->name('update_unit');

        Route::get('/course_users', 'CourseUserController@course_users')->name('course_users');
        Route::post('/search_user_course', 'CourseUserController@search_user_course')->name('search_user_course');
        Route::post('/add_users_course', 'CourseUserController@add_users_course')->name('add_users_course');
        Route::post('/course_users/delete', 'CourseUserController@delete_user_course')->name('delete_user_course');
        Route::post('/course_users/update', 'CourseUserController@update_user_expire_date')->name('update_user_expire_date');

        Route::get('/contents', 'ContentController@contents')->name('contents');
        Route::post('/add_section', 'ContentController@add_section')->name('add_section');
        Route::post('/update_section', 'ContentController@update_section')->name('update_section');
        Route::post('/add_content', 'ContentController@add_content')->name('add_content');
        Route::post('/update_content', 'ContentController@update_content')->name('update_content');
        Route::get('/delete_content', 'ContentController@delete_content')->name('delete_content');


        Route::get('/add_questions/{exam_id}', 'QuestionController@add_questions')->name('add_questions');
        Route::post('/add_question', 'QuestionController@add_question')->name('add_question');
        Route::get('/delete_question', 'QuestionController@delete_question')->name('delete_question');

        Route::post('/add_answer', 'QuestionController@add_answer')->name('add_answer');
        Route::get('/delete_answer', 'QuestionController@delete_answer')->name('delete_answer');
        Route::post('/update_answer', 'QuestionController@update_answer')->name('update_answer');

        Route::resource('groups', 'GroupController');
        Route::patch('/groups/{group}/restore', 'GroupController@restore')->name('groups.restore');

    });
});

