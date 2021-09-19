<?php

use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\Service\ServiceArchiveController;

Route::group([
	// 'middleware'=>'auth',
    'middleware' => ['auth', 'twofactor'],
	'prefix'=>LaravelLocalization::setLocale(),
], function(){

    Route::group(['as'=>'admin.',], function(){

        Route::resource('services', ServiceController::class);
        Route::patch('services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');

        Route::resource('service_archives', ServiceArchiveController::class);
        Route::patch('service_archives/{service_archive}/restore', [ServiceArchiveController::class, 'restore'])->name('service_archives.restore');
    });

});
