<?php

Route::group(['prefix'=>'xero', 'as'=>'xero.'], function (){
    Route::get('authorization', 'XeroController@authorization')->name('authorization');
    Route::get('callback', 'XeroController@callback')->name('callback');
    Route::get('authorizedResource', 'XeroController@authorizedResource')->name('authorizedResource');
});
