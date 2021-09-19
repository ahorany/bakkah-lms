<?php

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

    Route::group(['prefix'=>'training/evaluation', 'as'=>'evaluation.'], function(){
        Route::get('/', 'EvaluationController@index')->name('index');
        Route::get('search', 'EvaluationController@search')->name('search');
        Route::post('sending', 'EvaluationController@sending')->name('sending');
        Route::get('sessionsJson', 'EvaluationController@sessionsJson')->name('sessionsJson');
    });
});
