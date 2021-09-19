<?php

// use App\Http\Controllers\Admin\HomeController;
// Route::get('/admin/home', [HomeController::class, 'index'])->name('home');
// use App\Http\Controllers\Auth\TwoFactorController;

// Route::group([
//     'prefix'=>LaravelLocalization::setLocale().'/twofactor/verify',
//     'as'=>'twofactor.verify.',
// ], function(){
//     Route::get('/', [TwoFactorController::class, 'verify'])->name('index');//->only(['index', 'store']);
//     Route::post('store', [TwoFactorController::class, 'store'])->name('store');//->only(['index', 'store']);
//     Route::get('/resend', [TwoFactorController::class, 'resend'])->name('resend');
// });

// Route::group([
// 	// 'middleware'=>'auth',
//     'middleware' => ['auth', 'twofactor'],
// 	'prefix'=>LaravelLocalization::setLocale(),
// ], function(){

// 	Route::group(['prefix'=>'admin', 'as'=>'admin.'], function(){

// 		// Route::get('/home', function(){
//         //     dd('test');
//         // })->name('home');
//         Route::get('/home', 'HomeController@index')->name('home');

//     });
// });
