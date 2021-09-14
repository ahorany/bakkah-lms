<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'training', 'as'=>'training.'], function(){

        Route::resource('courses', 'CourseController');
        Route::patch('/courses/{course}/restore', 'CourseController@restore')->name('courses.restore');

        Route::get('session/attendant/', 'SessionController@sessionAttendant')->name('sessions.attendance');
        Route::get('session/attendant/store', 'SessionController@sessionAttendantStore')->name('sessions.attendance.store');
        Route::post('session/attendant/importzoom', 'SessionController@importzoom')->name('sessions.importzoom');

        Route::resource('training_options', 'TrainingOptionController');
        Route::patch('/training_options/{training_option}/restore', 'TrainingOptionController@restore')->name('training_options.restore');

        Route::resource('options', 'OptionController');
        Route::patch('/options/{option}/restore', 'OptionController@restore')->name('options.restore');

        Route::resource('sessions', 'SessionController');
        Route::get('getSessionByCourse', 'SessionController@getSessionByCourse')->name('sessions.getSessionByCourse');
        Route::patch('/sessions/{session}/restore', 'SessionController@restore')->name('sessions.restore');

        Route::get('/calculate_cost', 'SessionController@calculate_cost')->name('sessions.calculate_cost');
        Route::get('/calculate_gross_margin', 'SessionController@calculate_gross_margin')->name('sessions.calculate_gross_margin');
        Route::get('/confirm_session', 'SessionController@confirm_session')->name('sessions.confirm_session');

        Route::get('carts/search', 'CartController@search')->name('carts.search');
        Route::get('carts/sessionsJson', 'CartController@sessionsJson')->name('carts.sessionsJson');
        Route::get('carts/insights', 'CartController@insights')->name('carts.insights');

        Route::get('interests', 'InterestController@index')->name('interests.index');
        Route::delete('interests/{interest}', 'InterestController@destroy')->name('interests.destroy');
        Route::patch('/interests/{interest}/restore', 'InterestController@restore')->name('interests.restore');

        Route::get('carts/insightsSearch', 'CartController@insightsSearch')->name('carts.insightsSearch');

        Route::get('carts/training-schedule', 'CartController@trainingSchedule')->name('carts.training-schedule');

        Route::get('carts/statistics', 'CartController@statistics')->name('carts.statistics');
        Route::get('carts/registeration', 'CartController@monthly_registeration')->name('carts.registeration');
        Route::get('carts/statistics/export', 'CartController@statisticsExport')->name('carts.statistics.export');
        Route::resource('carts', 'CartController');
        Route::delete('carts/{cart}', 'CartController@destroy')->name('carts.destroy');
        Route::patch('/carts/{cart}/restore', 'CartController@restore')->name('carts.restore');

        Route::get('/carts/{cart}/lms/', 'CartController@lms')->name('lms');

        Route::get('/carts/{cart_master_id}/invoice', 'CartController@invoice')->name('carts.invoice');
        // Route::get('/carts/{cart_master_id}/invoicePDF', 'CartController@invoicePDF')->name('carts.invoicePDF');

        Route::get('gross-margin/getSession', 'GrossMarginController@getSession')->name('gross-margin.getSession');
        Route::resource('gross-margin', 'GrossMarginController');
        Route::patch('/gross-margin/{cart}/restore', 'GrossMarginController@restore')->name('gross-margin.restore');

        Route::resource('discounts', 'DiscountController');
        Route::get('/discounts/insert/details', 'DiscountController@add_details')->name('discounts.add_details');
        Route::get('/discounts/destroy/details', 'DiscountController@destroy_from_details')->name('discounts.destroy_from_details');
        Route::get('/discounts/replicate/{discount?}', 'DiscountController@replicate')->name('discounts.replicate');
        Route::patch('/discounts/{discount}/restore', 'DiscountController@restore')->name('discounts.restore');

        Route::get('registration/exportYes/', 'CartController@exportYes')->name('carts.exportYes');
        Route::get('registration/exportNo/', 'CartController@exportNo')->name('carts.exportNo');

        Route::resource('reports', 'ReportController');
        Route::patch('/reports/{report}/restore', 'ReportController@restore')->name('reports.restore');

        Route::get('webinars/registrations', 'WebinarRegistrationController@index')->name('webinarsRegistrations.index');
        Route::delete('webinars/registrations/{WebinarsRegistration}', 'WebinarRegistrationController@destroy')->name('webinarsRegistrations.destroy');
        Route::patch('/webinars/registrations/{WebinarsRegistration}/restore', 'WebinarRegistrationController@restore')->name('webinarsRegistrations.restore');

        Route::resource('webinars', 'WebinarController');
        Route::patch('/webinars/{webinar}/restore', 'WebinarController@restore')->name('webinars.restore');

        Route::post('excelSubmit', 'UploadExcelController@excel')->name('excel');
        Route::get('excel_uploads', 'UploadExcelController@index')->name('excel_uploads.index');

        Route::get('prepayments', 'XeroController@index')->name('prepayments.index');

        Route::get('registeration/pdf/{id}', 'WebinarRegistrationController@certificate_pdf')->name('pdf');

        Route::post('WebinarRegistration/webi_register_certificate/test', 'WebinarRegistrationController@webi_register_certificate')
        ->name('WebinarRegistration.webi_register_certificate');
        // Route::post('webi_register_certificate/webi_register_certificate/test', 'WebinarRegistrationController@webi_register_certificate')->name('webinarsRegistrations.webi_register_certificate');

        // Route::get('evaluations', 'CartController@evaluations')->name('evaluations.index');
        // Route::group(['prefix'=>'certificates', 'as'=>'certificates.'], function(){
        //     Route::get('/{cart_id}', [CertificateController::class, 'certificate'])->name('certificate');
        //     Route::get('/{cart_id}/pdf', [CertificateController::class, 'certificate_pdf'])->name('pdf');
        // });
        
        
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
