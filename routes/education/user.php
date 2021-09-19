<?php

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function() {
   Route::group(['as'=>'user.'], function () {//'prefix'=>'user',

    //    Route::get('/user/home', 'UserProfileController@dashboard')->name('home');
    //    Route::get('/user/info', 'UserProfileController@info')->name('info');
    //    Route::get('/user/notification', 'UserProfileController@notification')->name('notification');
    //    Route::get('/user/my-courses', 'UserProfileController@my_courses')->name('my_courses');
    //    Route::get('/user/certifications', 'UserProfileController@certifications')->name('certifications');
    //    Route::get('/user/payment-info', 'UserProfileController@payment_info')->name('payment_info');
    //    Route::get('/user/logout', 'UserProfileController@logout')->name('logout');

    //    Route::get('/user/cart', 'UserProfileController@cart')->name('cart');

   });
});
