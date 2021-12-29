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

<<<<<<< HEAD
=======
Route::get('/', function(){
    return redirect()->route('user.home');
});



>>>>>>> aaf57faaf4a62bff55d9afe53cf19d343fe54ad6
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
