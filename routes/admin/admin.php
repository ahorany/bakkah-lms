<?php

use App\Http\Controllers\Auth\TwoFactorController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale().'/twofactor/verify',
    'as'=>'twofactor.verify.',
], function(){
    Route::get('/', [TwoFactorController::class, 'verify'])->name('index');//->only(['index', 'store']);
    Route::post('store', [TwoFactorController::class, 'store'])->name('store');//->only(['index', 'store']);
    Route::get('/resend', [TwoFactorController::class, 'resend'])->name('resend');
});

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

	Route::group(['prefix'=>'admin', 'as'=>'admin.'], function(){

		Route::get('/home', 'HomeController@index')->name('home');

		Route::resource('users', 'UserController');
		Route::patch('/users/{user}/restore', 'UserController@restore')->name('users.restore');
        Route::get('/users/{user}/change-password', 'UserController@changePassword')->name('users.changePassword');
        Route::patch('/users/{user}/change-password', 'UserController@savePassword')->name('users.savePassword');

        Route::resource('roles', 'RoleController');
        Route::patch('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');

        Route::resource('accordions', 'AccordionController');
        Route::patch('/accordions/{accordion}/restore', 'AccordionController@restore')->name('accordions.restore');
        
        Route::resource('details', 'DetailController');
        Route::patch('/details/{detail}/restore', 'AccordionController@restore')->name('details.restore');

    });
});
