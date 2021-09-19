<?php
// use App\Helpers\CacheLocation;
// use App\Models\Admin\Post;
// use App\Models\Training\Course;

// CacheLocation::Run();

// use App\Http\Controllers\Training\InterestController;
// Route::get('interest/{course_id}', [InterestController::class, 'target']);
// //Route::get('/', function () {
// //	// $obj = new stdClass();
// //	// dd(auth()->user()->id);
// //    return view('welcome');
// //});

// Route::group([
//     'prefix'=>LaravelLocalization::setLocale(),
//     'middleware' => [ 'localizationRedirect','localize' ]
// ], function(){

//     Route::get('/', 'Front\HomeController@index')->name('web.home');
//     Route::get('/sitemap/sitemap', 'Front\HomeController@sitemap')->name('web.sitemap');

//     Auth::routes(['verify' => true]);
// });

// Route::get('/search-vue-algolia/export', 'AlgoliaController@export');
// Route::get('/search-vue-algolia', function(){
//     return view('front.education.layouts.vue-instantsearch');
// });

// Route::get('/clear-cache', function(){
//     if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3){
//         \Artisan::call('cache:clear');
//         \Artisan::call('route:clear');
//         \Artisan::call('view:clear');
//         \Artisan::call('config:clear');
//         \Artisan::call('queue:restart');
//     }
//     return redirect()->route('education.index');
// })->middleware('auth');

// Route::get('/qplus/sessions/training-schedule', 'QplusController');
