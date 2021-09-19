<?php
use App\Http\Controllers\Front\Education\EducationController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize','checkRedirectPage' ]
], function() {

    Route::group(['as' => 'education.'], function () {

        Route::get('/', [EducationController::class, 'index'])->name('index');

        Route::group(['prefix' => 'sessions'], function () {

            Route::get('/{category?}', [EducationController::class, 'sessions'])->name('courses');

        });
    });
});
