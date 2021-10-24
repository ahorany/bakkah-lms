<?php
use App\Http\Controllers\Front\Education\EducationController;
use Modules\UserProfile\Http\Controllers\UserProfileController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize','checkRedirectPage' ]
], function() {

    Route::group(['as' => 'education.'], function () {

        Route::get('/', [UserProfileController::class, 'login'])->name('login');

        Route::group(['prefix' => 'sessions'], function () {

            Route::get('/{category?}', [EducationController::class, 'sessions'])->name('courses');

        });
    });
});
