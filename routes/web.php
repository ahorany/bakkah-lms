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

<<<<<<< HEAD
    Route::get('/vsscorm1', function(){
=======
    Route::get('/vsscorm12', function(){

>>>>>>> 2ee991f8503e33a8e5dc0c50e2c6dcd66d1b914a
        return view('scorm');
    });

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
