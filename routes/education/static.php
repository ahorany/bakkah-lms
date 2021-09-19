<?php

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function() {

    Route::group(['as'=>'education.'], function () {//'prefix'=>'education',

        Route::get('/for-corporate', 'StaticController@forCorporate')->name('for-corporate');

        Route::get('/search-algolia', 'StaticController@algolia')->name('search-algolia');

        Route::group(['as'=>'static.'], function () {

            Route::get('/reports','StaticController@reports')->name('reports');
            Route::get('/reports/{slug?}','StaticController@reportSingle')->name('reports.single');
            Route::post('/reports/download', 'StaticController@reportsDownload')->name('reports.download');//{post_id}

            Route::get('/webinars','StaticController@webinars')->name('webinars');
            Route::get('/webinars/{slug?}','StaticController@webinarSingle')->name('webinars.single');
            Route::post('/webinars/{slug?}', 'StaticController@webinarRegistrationSubmit')->name('webinarRegistration');

            Route::get('/partners','StaticController@partners')->name('partners');
            Route::get('/partners/{slug?}','StaticController@partnerSingle')->name('partners.single');

            Route::get('/clients','StaticController@clients')->name('clients');

            // New Knowledge Hub
            Route::get('knowledge-hub', 'StaticController@knowledgeHub')->name('knowledge-hub');
            Route::get('category/{post_type?}', 'StaticController@knowledgeCenter')->name('knowledge-center');
            Route::get('/knowledge-center/{slug?}', 'StaticController@knowledgeCenterSingleHub')->name('knowledge-center.single');
            Route::post('/knowledge-center/newsletter', 'StaticController@newsletter')->name('knowledge-center.newsletter');//{post_id}

            Route::get('/contact-us','StaticController@contactusIndex')->name('contactusIndex');
            Route::Post('/contact-us','StaticController@contactusStore')->name('contactusStore');

            Route::get('/about-bakkah','StaticController@aboutBakkah')->name('about-bakkah');
            Route::get('/{post_type}','StaticController@staticPage')->name('static-page');

            // Route::get('/knowledge-center/{slug?}', 'StaticController@knowledgeCenterSingle')->name('knowledge-center.single');//{post_id}

        });
    });
});
