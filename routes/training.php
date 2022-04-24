<?php

Route::group([
    'middleware' => ['auth'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){


            Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.users.index'], function(){
                Route::resource('users', 'UserController');
                Route::patch('/users/{user}/restore', 'UserController@restore')->name('users.restore');
                Route::get('/get/user/data', 'UserController@getUserData')->name('getUserData');

                Route::get('/usersReportOverview', 'ReportController@usersReportOverview')->name('usersReportOverview');
                Route::get('/usersReportCourse', 'ReportController@usersReportCourse')->name('usersReportCourse');
                Route::get('/usersReportTest', 'ReportController@usersReportTest')->name('usersReportTest');
                Route::get('/usersReportScorm', 'ReportController@usersReportScorm')->name('usersReportScorm');

            });


            Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.roles.index'], function(){
                Route::resource('roles', 'RoleController');
                Route::patch('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');
            });


            Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.sessions.index'], function(){
                Route::resource('sessions', 'SessionController');
                Route::patch('/sessions/{session}/restore', 'SessionController@restore')->name('sessions.restore');
            });


        Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.courses.index'], function(){
            Route::resource('courses', 'CourseController');
            Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');


            Route::get('/coursesReportOverview', 'ReportController@coursesReportOverview')->name('coursesReportOverview');
            Route::get('/coursesReportUser', 'ReportController@coursesReportUser')->name('coursesReportUser');
            Route::get('/coursesReportTest', 'ReportController@coursesReportTest')->name('coursesReportTest');
            Route::get('/coursesReportScorm', 'ReportController@coursesReportScorm')->name('coursesReportScorm');
            Route::get('/coursesAssessments', 'ReportController@coursesAssessments')->name('coursesAssessments');
            Route::get('/progressDetails', 'ReportController@progressDetails')->name('progressDetails');
            Route::get('/exam', 'ReportController@exam')->name('exam');
            Route::get('/exam_review', 'ReportController@exam_review')->name('exam.review');
            Route::get('/exam_result_details/details', 'ReportController@exam_result_details')->name('exam.exam_result_details');


            Route::post('courses/importCourses', 'ImportController@importCourses')->name('importCourses');
            Route::post('courses/importUsers', 'ImportController@importUsers')->name('importUsers');
            Route::post('courses/importUsersCourses', 'ImportController@importUsersCourses')->name('importUsersCourses');
            Route::post('courses/importUsersGroups', 'ImportController@importUsersGroups')->name('importUsersGroups');
            Route::post('courses/importQuestions', 'ImportController@importQuestions')->name('importQuestions');
            Route::post('courses/importQuestionsCourse', 'ImportController@importQuestionsCourse')->name('importQuestionsCourse');


//             Route::get('/discussion', 'DiscussionController@discussion')->name('discussion');


            Route::get('/units', 'UnitController@index')->name('units');
            Route::get('/delete_unit', 'UnitController@delete_unit')->name('delete_unit');
            Route::post('/add_unit', 'UnitController@add_unit')->name('add_unit');
            Route::post('/update_unit', 'UnitController@update_unit')->name('update_unit');


            Route::get('/role_path', 'RolePathController@rolePath')->name('role_path');
            Route::get('/send_role_path', 'RolePathController@sendRolePath')->name('send_role_path');



            Route::get('/course_users', 'CourseUserController@course_users')->name('course_users');
            Route::post('/search_user_course', 'CourseUserController@search_user_course')->name('search_user_course');
            Route::post('/add_users_course', 'CourseUserController@add_users_course')->name('add_users_course');
            Route::post('/course_users/delete', 'CourseUserController@delete_user_course')->name('delete_user_course');



            Route::get('/contents', 'ContentController@contents')->name('contents');
            Route::post('/add_section', 'ContentController@add_section')->name('add_section');
            Route::post('/update_section', 'ContentController@update_section')->name('update_section');
            Route::post('/add_content', 'ContentController@add_content')->name('add_content');
            Route::post('/update_content', 'ContentController@update_content')->name('update_content');
            Route::get('/delete_content', 'ContentController@delete_content')->name('delete_content');
            Route::get('/reset_order_contents/{course_id}', 'ContentController@reset_order_contents')->name('reset_order_contents');
            Route::post('/add_gift', 'ContentController@add_gift')->name('add_gift');
            Route::post('/update_gift', 'ContentController@update_gift')->name('update_gift');
            Route::post('/save/content/order', 'ContentController@save_content_order')->name('contents.save_order');



            Route::get('/add_questions/{exam_id}', 'QuestionController@add_questions')->name('add_questions');
            Route::post('/add_question', 'QuestionController@add_question')->name('add_question');
            Route::get('/delete_question', 'QuestionController@delete_question')->name('delete_question');



            Route::post('/add_answer', 'QuestionController@add_answer')->name('add_answer');
            Route::get('/delete_answer', 'QuestionController@delete_answer')->name('delete_answer');
            Route::post('/update_answer', 'QuestionController@update_answer')->name('update_answer');



            Route::get('/group_users', 'GroupUserController@group_users')->name('group_users');
            Route::post('/search_user_group', 'GroupUserController@search_user_group')->name('search_user_group');
            Route::post('/add_users_group', 'GroupUserController@add_users_group')->name('add_users_group');
            Route::post('/group_users/delete', 'GroupUserController@delete_user_group')->name('delete_user_group');


            Route::get('/group_courses', 'GroupCourseController@group_courses')->name('group_courses');
            Route::post('/search_course_group', 'GroupCourseController@search_course_group')->name('search_course_group');
            Route::post('/add_course_group', 'GroupCourseController@add_course_group')->name('add_courses_group');
            Route::post('/group_courses/delete', 'GroupCourseController@delete_course_group')->name('delete_course_group');
        });


          Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.certificates.index'], function(){
              Route::get('certificates/preview', 'CertificateControllerH@preview')->name('certificates.preview');
              Route::get('certificates/add_new', 'CertificateControllerH@add_new')->name('certificates.add_new');
              Route::get('certificates/save_position', 'CertificateControllerH@save_position')->name('certificates.save_position');
              Route::get('certificates/preview_pdf','CertificateControllerH@preview_pdf')->name('certificates.preview_pdf');
              Route::get('certificates/delete_rich','CertificateControllerH@delete_rich')->name('certificates.delete_rich');
              Route::get('certificates/replicate','CertificateControllerH@replicate')->name('certificates.duplicate');

              Route::get('certificates/certificate','CertificateControllerH@certificate_dynamic')->name('certificates.certificate_dynamic');
              Route::resource('certificates', 'CertificateControllerH');
          });



         Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.scormsReportOverview'], function(){
            Route::get('/scormsReportOverview', 'ReportController@scormsReportOverview')->name('scormsReportOverview');
            Route::get('/scormsReportScorms', 'ReportController@scormsReportScorms')->name('scormsReportScorms');
            Route::get('/scorm_users', 'ReportController@scorm_users')->name('scorm_users');
         });


         Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.categories.index'], function(){
             Route::resource('categories', 'CategoryController');
             Route::patch('/categories/{category}/restore', 'CategoryController@restore')->name('categories.restore');
         });




         Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.groups.index'], function(){
             Route::resource('groups', 'GroupController');
             Route::patch('/groups/{group}/restore', 'GroupController@restore')->name('groups.restore');
             Route::get('/groupReportOverview', 'ReportController@groupReportOverview')->name('groupReportOverview');
             Route::get('/groupsReportUser', 'ReportController@groupsReportUser')->name('groupsReportUser');
             Route::get('/groupsReporcourse', 'ReportController@groupsReporcourse')->name('groupsReporcourse');
         });




        Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.branches.index'], function(){
            Route::resource('branches', 'BrancheController');
            Route::patch('/branches/{branche}/restore', 'BrancheController@restore')->name('branches.restore');

//            Route::get('/branche_users', 'BrancheController@branche_users')->name('group_users');
//            Route::post('/search_user_branches', 'BrancheController@search_user_branches')->name('search_user_branche');
//            Route::post('/add_users_branches', 'BrancheController@add_users_branches')->name('add_users_branche');
//            Route::post('/branches_users/delete', 'BrancheController@delete_user_branche')->name('delete_user_branche');
        });


        Route::group(['middleware' => 'SetSideBarItemActiveBySession:training.settings.index'], function(){
            Route::get('/settings', 'SettingController@index')->name('settings.index');
            Route::post('/update/settings', 'SettingController@update')->name('settings.update');
        });



        Route::get('/imports', 'ImportController@imports')->name('imports');
        Route::get('test/zoom','\App\Http\Controllers\Api\ZoomApiController@index')->name('zoom.index');
    });
});

