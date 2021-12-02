<?php
use App\Http\Controllers\Training\InterestController;
Route::get('interest/{course_id}', [InterestController::class, 'target']);

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function(){

    // Route::get('/', 'Front\HomeController@index')->name('web.home');
    Route::get('/vsscorm1', function(){

        return view('scorm');
        dd('test');
    });
    Route::get('/sitemap/sitemap', 'Front\HomeController@sitemap')->name('web.sitemap');

    Auth::routes(['register' => false]);
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
