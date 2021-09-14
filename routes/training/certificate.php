<?php
// use App\Http\Controllers\convertapi\ConvertapiController;
use App\Http\Controllers\Training\CertificateController;

Route::group(['prefix'=>'certificates', 'as'=>'certificates.'], function(){

    Route::group(['prefix'=>'certificate', 'as'=>'certificate.'], function(){
        Route::get('{id}', [CertificateController::class, 'certificate'])->name('index');
        Route::get('url/{id}', [CertificateController::class, 'certificate_url'])->name('url');
        Route::get('pdf/{id}', [CertificateController::class, 'certificate_pdf'])->name('pdf');
    });

    Route::group(['prefix'=>'attendance', 'as'=>'attendance.'], function(){
        Route::get('{id}', [CertificateController::class, 'attendance'])->name('index');
        Route::get('url/{id}', [CertificateController::class, 'attendance_url'])->name('url');
        Route::get('pdf/{id}', [CertificateController::class, 'attendance_pdf'])->name('pdf');
    });
});

Route::get('/user/certifications/{id}', [CertificateController::class, 'certificate_userProfile'])->name('user.certifications.index');
