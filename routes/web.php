<?php
use App\Helpers\CacheLocation;
use App\Models\Admin\Post;
use App\Models\Training\Course;

CacheLocation::Run();

// Route::get('paypal', 'PaymentController@index');
// Route::get('paypal/success', 'PaymentController@success')->name('paypal.success');
// Route::get('paypal/cancel', 'PaymentController@cancel')->name('paypal.cancel');

use App\Http\Controllers\Training\InterestController;
Route::get('interest/{course_id}', [InterestController::class, 'target']);
//Route::get('/', function () {
//	// $obj = new stdClass();
//	// dd(auth()->user()->id);
//    return view('welcome');
//});

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function(){

    // Route::get('/', 'Front\HomeController@index')->name('web.home');
    Route::get('/sitemap/sitemap', 'Front\HomeController@sitemap')->name('web.sitemap');

    Auth::routes(['verify' => true]);
});

Route::get('/search-vue-algolia/export', 'AlgoliaController@export');
Route::get('/search-vue-algolia', function(){
    return view('front.education.layouts.vue-instantsearch');
});

Route::get('/clear-cache', function(){
    if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3){
        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        \Artisan::call('queue:restart');
    }
    return redirect()->route('education.index');
})->middleware('auth');

//Route::get('/users/import_from_wp', 'Admin\UserController@import_from_wp');


Route::get('/qplus/sessions/training-schedule', 'QplusController');


// Route::get('/home', 'HomeController@index')->name('home');

// for front end only
// Route::get('/knowledge-hub', function () {
//     return view('front.education.knowledge-center.new');
// });
// Route::get('/knowledge-single', function () {
//     return view('front.education.knowledge-center.single-new');
// });
