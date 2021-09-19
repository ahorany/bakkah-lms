<?php

use App\Http\Controllers\UserFromExcelController;

Route::group(['prefix'=>'user/from/excel', 'as'=>'userfromexcel.'], function(){
    Route::get('send', [UserFromExcelController::class, 'send']);
});
