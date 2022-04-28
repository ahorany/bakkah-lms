<?php
use App\Timezone;
Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize', 'Recaptcha' ]
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

//Route::get('/timezone', function(){
//    // $arr = [];
//    $timestamp = time();
//    foreach (timezone_identifiers_list() as $key=>$zone) {
//        date_default_timezone_set($zone);
//        $zones['id'] = $key+1;
//        $zones['offset'] = date('P', $timestamp);
//        $zones['diff_from_gtm'] = 'UTC/GMT '.date('P', $timestamp);
//        $zones['location'] = $zone;
//        $zones['name'] = '( UTC/GMT '.date('P', $timestamp) . ' ) ' . $zone;
//        $timezone = Timezone::updateOrCreate(
//            [
//                'location' => $zone,
//            ],
//            [
//                'offset' => date('P', $timestamp),
//                'diff_from_gtm' => 'UTC/GMT '.date('P', $timestamp),
//                'location' => $zone,
//                'name' => '( UTC/GMT '.date('P', $timestamp) . ' ) ' . $zone,
//            ]);
//        // $arr[] = $zones;
//    }
//    // $collection = collect(json_decode( json_encode($arr) ));
//    dd('Done');
//});

Route::get('/clear-permissions', function(){
    \Artisan::call('cache:forget spatie.permission.cache');
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


Route::get('migration/user/import/{course_id}',function ($course_id){
    if (auth()->id() == 1){
        // dd('test');
       Excel::import(new \App\Imports\MigrationITILCourseUsersImport($course_id), public_path('upload\excel\Course_ECBA_self.xlsx'));
       dd('success');
    }
})->middleware('auth');
