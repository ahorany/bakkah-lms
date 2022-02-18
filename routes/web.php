<?php
use App\Http\Controllers\Training\InterestController;
Route::get('interest/{course_id}', [InterestController::class, 'target']);

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function(){

    Route::get('/zoom', function(){
        return view('zoom');
    })->name('web.zoom');

    Route::get('/vsscorm12', function(){
        return view('scorm');
    });

    Auth::routes(['register' => false]);
});



































Route::get('/', function(){
    return redirect()->route('user.home');
});

Route::get('/clear-permissions', function(){
    \Artisan::call('cache:forget spatie.permission.cache');
    // dd('dddd');
    return redirect()->route('user.home');
})->middleware('auth');

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
