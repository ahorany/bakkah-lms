<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor','checkUserType'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::group([
            'middleware' => ['CheckInstructorType'],
        ], function(){
            Route::get('/imports', 'ImportController@imports')->name('imports');

            Route::resource('users', 'UserController');
            Route::patch('/users/{user}/restore', 'UserController@restore')->name('users.restore');
            Route::get('/users/{user}/change-password', 'UserController@changePassword')->name('users.changePassword');
            Route::patch('/users/{user}/change-password', 'UserController@savePassword')->name('users.savePassword');

            Route::resource('roles', 'RoleController');
            Route::patch('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');

            Route::get('/usersReport', 'ReportController@user_report')->name('usersReport');
            Route::get('/courseReport', 'ReportController@courseReport')->name('courseReport');
            Route::get('/testReport', 'ReportController@testReport')->name('testReport');





            Route::get('/group_users', 'GroupUserController@group_users')->name('group_users');
            Route::post('/search_user_group', 'GroupUserController@search_user_group')->name('search_user_group');
            Route::post('/add_users_group', 'GroupUserController@add_users_group')->name('add_users_group');
            Route::post('/group_users/delete', 'GroupUserController@delete_user_group')->name('delete_user_group');


            Route::get('/group_courses', 'GroupCourseController@group_courses')->name('group_courses');
            Route::post('/search_course_group', 'GroupCourseController@search_course_group')->name('search_course_group');
            Route::post('/add_course_group', 'GroupCourseController@add_course_group')->name('add_courses_group');
            Route::post('/group_courses/delete', 'GroupCourseController@delete_course_group')->name('delete_course_group');
        });



        Route::post('courses/importCourses', 'ImportController@importCourses')->name('importCourses');
        Route::post('courses/importUsers', 'ImportController@importUsers')->name('importUsers');
        Route::post('courses/importUsersCourses', 'ImportController@importUsersCourses')->name('importUsersCourses');
        Route::post('courses/importUsersGroups', 'ImportController@importUsersGroups')->name('importUsersGroups');

        Route::post('courses/importQuestions', 'ImportController@importQuestions')->name('importQuestions');
        Route::post('courses/importResults', 'ImportController@importResults')->name('importResults');



        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');


//        Route::resource('training_options', 'TrainingOptionController');
//        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');

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

