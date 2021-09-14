<?php

use App\Models\Training\Course;
use App\Helpers\Models\Training\SessionHelper;
use App\Http\Controllers\Admin\PipedriveController;
use App\Models\Training\TrainingOptionFeature;
use App\Http\Controllers\Testing\TestingController;
use App\Http\Controllers\Testing\DataMigrationController;

Route::get('/training/testing', [TestingController::class, 'index']);
Route::get('/training/pipedrive', [PipedriveController::class, 'pipedrive']);
Route::get('/training/pipedrive/organization', [PipedriveController::class, 'organization']);
Route::get('/training/pipedrive/products', [PipedriveController::class, 'products']);
Route::get('/training/pipedrive/pipedrive_product_attacheds__run', [PipedriveController::class, 'pipedrive_product_attacheds__run']);
Route::get('/training/PipedriveDealField/add_deal', [PipedriveController::class, 'add_deal']);
Route::post('/training/PipedriveDealField/add_deal', [PipedriveController::class, 'add_deal_post'])->name('add_deal_post');

Route::get('/training/PipedriveDealField/{post_type}', [PipedriveController::class, 'PipedriveDealField']);

Route::get('/retarget_discount/{cart_id}', [TestingController::class, 'retarget_discount']);

Route::get('algolia', [TestingController::class, 'algolia']);
Route::get('redirect_to_dontcom', [TestingController::class, 'redirect_to_dontcom']);

Route::get('test/test', function() {

    $course = Course::find(12);

    $SessionHelper = new SessionHelper;

    $course = $SessionHelper->Single($course->slug, false)
    ->where('session_id', 129)
    ->first();

    $SessionHelper->SetCourse($course);
    $PriceAfterDiscount = $SessionHelper->PriceAfterDiscount();
    dd($course->discount_id);
    dd($PriceAfterDiscount);


    $trainingOptionFeatures = TrainingOptionFeature::where('training_option_id', $course->training_option_id)->with(['feature'])
    ->get();

    $cart = 4;

    //$modal_body = view('front.education.products.components.modal_body', compact('cart', 'trainingOptionFeatures'))->render();
    $modal_body = view('front.education.products.components.modal_body', compact('course', 'cart', 'trainingOptionFeatures'))->render();

    return response()->json(['modal_body' => $modal_body]);

    //dump($trainingOptionFeatures);
});

Route::group(['prefix'=>'data_migration'], function(){
    Route::get('laravel', [DataMigrationController::class, 'laravel']);
    Route::get('wp', [DataMigrationController::class, 'wp']);
});
