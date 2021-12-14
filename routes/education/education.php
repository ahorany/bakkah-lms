<?php
use App\Http\Controllers\Front\Education\EducationController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize','checkRedirectPage' ]
], function() {

    Route::group(['as' => 'education.'], function () {

        Route::get('/', [\App\Http\Controllers\Front\UserProfileController::class, 'home'])->name('login');

    });
});
