<?php

use Modules\CRM\Http\Controllers\GroupInvoiceController;
use Modules\CRM\Http\Controllers\ProductDemandController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ],
    'as'=>'crm::',
], function(){

    Route::group(['prefix'=>'crm'], function() {

        Route::get('/', 'CRMController@index');

        // Route::group(['as'=>'organizations.', 'prefix'=>'organizations'], function(){
        //     Route::get('/', [OrganizationController::class, 'index'])->name('index');
        //     //Route::get('/edit/{id}', [OrganizationController::class, 'edit'])->name('edit');
        // });
        Route::resource('organizations','OrganizationController');
        Route::group(['prefix'=>'organizations', 'as'=>'organizations.'], function(){
            Route::patch('/{organization}/restore', 'OrganizationController@restore')->name('restore');
        });

        // Route::resource('b2bs','B2BController');
        // Route::group(['prefix'=>'b2bs', 'as'=>'b2bs.'], function(){
        //     Route::patch('/{b2b}/restore', 'B2BController@restore')->name('restore');
        //     Route::get('sessionsJson/vue', 'B2BController@sessionsJson')->name('sessionsJson');
        //     Route::get('vue/search', 'B2BController@search')->name('search');
        // });

        Route::group(['prefix'=>'carts', 'as'=>'carts.'], function(){
            Route::get('/{id}/edit','ProductDemandController@edit')->name('edit');
            Route::post('/update','ProductDemandController@update')->name('update');
            Route::get('/GetSessionInfoJson','ProductDemandController@GetSessionInfoJson')->name('GetSessionInfoJson');
            Route::get('/{id}/getPaymentDetails','ProductDemandController@getPaymentDetails')->name('getPaymentDetails');
        });

        Route::resource('group_invoices', 'GroupInvoiceController');
        Route::group(['prefix'=>'group_invoices', 'as'=>'group_invoices.'], function(){
            Route::get('search','GroupInvoiceController@search')->name('search');
            Route::get('{invoice}/edit/searchcandidates','GroupInvoiceController@SearchCandidates')->name('searchcandidates');
            Route::patch('{invoice}/restore', 'GroupInvoiceController@restore')->name('restore');
        });

        Route::resource('group_invs', 'GroupInvController');
        Route::group(['prefix'=>'group_invs', 'as'=>'group_invs.'], function(){
            Route::get('search','GroupInvController@search')->name('search');
            Route::get('{invoice}/edit/searchcandidates','GroupInvController@SearchCandidates')->name('searchcandidates');
            Route::patch('{invoice}/restore', 'GroupInvController@restore')->name('restore');
            Route::get('/register/delete', 'GroupInvController@deleteCandidates')->name('register.delete');
            Route::get('/register/storeCandidate', 'GroupInvController@storeCandidate')->name('register.storeCandidate');
            Route::get('/{id}/exportQuotationToDoc', 'GroupInvController@exportQuotationToDoc')->name('exportQuotationToDoc');
            Route::get('/{id}/exportInvoiceToDoc/{type?}', 'GroupInvController@exportInvoiceToDoc')->name('exportInvoiceToDoc');
            Route::get('{id}/comment', 'GroupInvController@comment')->name('comment');
            Route::get('{id}/comments/delete', 'GroupInvController@deleteComments')->name('delete');
        });

        // Route::get('products-demand/{id}/edit', 'ProductDemandController@show')->name('ProductsDemand.details');
        // Route::get('products-demand/{id}/comment', 'ProductDemandController@comment')->name('ProductsDemand.comment');
        // Route::get('products-demand/{id}/comments/delete', 'ProductDemandController@deleteComments')->name('ProductsDemand.delete');
        Route::group(['as'=>'show.'], function(){
            Route::get('getNotes/{cartMasterId}', [ProductDemandController::class, 'getNotes'])->name('getNotes');
            Route::post('addNote', [ProductDemandController::class, 'addNote'])->name('addNote');
            Route::get('getData/{cartMasterId}', [ProductDemandController::class, 'getData'])->name('getData');
            Route::get('SessionsDetailsJson', [ProductDemandController::class, 'SessionsDetailsJson'])->name('SessionsDetailsJson');
            // Route::get('SessionsDetailsJson/{session_id}/{coin_id}', [ProductDemandController::class, 'SessionsDetailsJson'])->name('SessionsDetailsJson');
            Route::post('updateData', [ProductDemandController::class, 'updateData'])->name('updateData');
            Route::post('addCartFeature', [ProductDemandController::class, 'addCartFeature'])->name('addCartFeature');
            Route::post('changeCartPaymentStatus_forCalc', [ProductDemandController::class, 'changeCartPaymentStatus_forCalc'])->name('changeCartPaymentStatus_forCalc');
            Route::post('summations', [ProductDemandController::class, 'summations'])->name('summations');
            Route::post('SendEmailMaster', [ProductDemandController::class, 'SendEmailMaster'])->name('SendEmailMaster');
        });

    });

    Route::group(['as'=>'rfq.', 'prefix'=>'rfq'], function(){
        Route::get('/autofill/email', [GroupInvoiceController::class, 'autofill'])->name('register.autofill');
        Route::get('sessionsJson', [GroupInvoiceController::class, 'sessionsJson'])->name('sessionsJson');
        Route::get('SessionsDetailsJson', [GroupInvoiceController::class, 'SessionsDetailsJson'])->name('SessionsDetailsJson');
        Route::get('/{id}/exportQuotationToDoc', [GroupInvoiceController::class, 'exportQuotationToDoc'])->name('exportQuotationToDoc');
        Route::get('/{id}/exportInvoiceToDoc', [GroupInvoiceController::class, 'exportInvoiceToDoc'])->name('exportInvoiceToDoc');

        Route::get('/register', [GroupInvoiceController::class, 'register'])->name('register');
        Route::post('/register', [GroupInvoiceController::class, 'registerStore'])->name('register.submit');
        Route::get('/register/{id}/edit', [GroupInvoiceController::class, 'registerEdit'])->name('register.edit');
        Route::post('/register/{id}/update', [GroupInvoiceController::class, 'registerUpdate'])->name('register.update');
        Route::get('/register/delete', [GroupInvoiceController::class, 'deleteCandidates'])->name('register.delete');
        Route::get('/register/storeCandidate', [GroupInvoiceController::class, 'storeCandidate'])->name('register.storeCandidate');
        // Route::get('/register/thanks/{rfp}', [GroupInvoiceController::class, 'thanks'])->name('register.thanks');
    });

    Route::group(['prefix'=>'products-demand', 'as'=>'products-demand.'], function(){
        Route::get('delivery', 'ProductDemandController@GetDeliver')->name('GetDeliver');
        // Route::get('{id}/show', 'ProductDemandController@show')->name('show');
        Route::get('{id}/comment', 'ProductDemandController@comment')->name('comment');
        Route::get('{id}/comments/delete', 'ProductDemandController@deleteComments')->name('delete');
        Route::get('{cartMasterId}/show', 'ProductDemandController@show')->name('show');

    });
});
