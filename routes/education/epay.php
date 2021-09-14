<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Education\EpayController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize' ]
], function() {

    Route::group(['prefix' => 'epay', 'as' => 'epay.'], function () {
        Route::post('prepare_the_checkout', [EpayController::class, 'prepare_the_checkout'])->name('prepare_the_checkout');
        Route::get('/{slug?}/transaction', [EpayController::class, 'transaction'])->name('transaction');
        Route::get('/{cart}/checkout', [EpayController::class, 'checkout'])->name('checkout');
        Route::get('/payment', [EpayController::class, 'payment'])->name('payment.post');
        // Route::get('/thanks/{status}/{code}/{description}', [EpayController::class, 'thanks'])->name('payment.thanks');
        Route::get('/paypal', [EpayController::class, 'paypal'])->name('payment.paypal');
        Route::get('/{status}/thanks', [EpayController::class, 'final_thanks'])->name('payment.final_thanks');
        Route::get('/{payment}/{status}/thanks', [EpayController::class, 'thanks'])->name('payment.thanks');

        // cart checkout
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/{cart}/checkout/{master_id?}', [EpayController::class, 'cartCheckout'])->name('cartCheckout');
            // Route::get('/payment', [EpayController::class, 'cartPayment'])->name('cartPayment.post');
            Route::post('/payment/{user}/{master_id?}', [EpayController::class, 'cartPayment'])->name('cartPayment.post');
            Route::get('/paypal/{user}/{master_id?}', [EpayController::class, 'paypalByCheckout'])->name('cartPayment.paypalByCheckout');
            // Route::get('/paypal', [EpayController::class, 'cartPaypal'])->name('cartPayment.paypal');
            Route::get('/{code}/{payment_status}/{description}/{status}/thanks', [EpayController::class, 'cartThanks'])->name('cartPayment.thanks');
        });

    });
});
