<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\Education\CartController;
use App\Http\Controllers\Front\Education\ScoutController;
use App\Http\Controllers\Front\Education\WishlistController;
use App\Http\Controllers\Front\Education\EducationController;

Route::group([
    'prefix'=>LaravelLocalization::setLocale(),
    'middleware' => [ 'localizationRedirect','localize','checkRedirectPage' ]
], function() {

    Route::group(['as' => 'education.'], function () {//'prefix'=>'education'

        Route::get('/change-currency/{currency?}', [EducationController::class, 'changeCurrency'])->name('learning.changeCurrency');
        Route::get('/lastChance', [EducationController::class, 'lastChance'])->name('learning.lastChance');
        Route::get('/GetQuery', [ScoutController::class, 'GetQuery'])->name('learning.GetQuery');

        Route::get('/', [EducationController::class, 'index'])->name('index');

        Route::get('hot-deals/{category?}', [EducationController::class, 'hot_deals'])->name('hot-deals');

        Route::group(['prefix' => 'sessions'], function () {//'prefix' => 'courses'

            Route::get('/training-schedule', [EducationController::class, 'trainingSchedule'])->name('training-schedule');

            Route::get('/{category?}', [EducationController::class, 'sessions'])->name('courses');

            Route::get('/autofill/email', [EducationController::class, 'autofill'])->name('courses.register.autofill');
            Route::get('/{slug?}/register', [EducationController::class, 'register'])->name('courses.register');//course_id
            Route::post('/{slug}/register', [EducationController::class, 'registerSubmit'])->name('courses.register.submit');
            Route::get('/{slug?}/interest', [EducationController::class, 'interest'])->name('courses.interest');//course_id
            Route::post('/{slug}/interest', [EducationController::class, 'interestSubmit'])->name('courses.interest.submit');

            Route::post('/question', [EducationController::class, 'quesionSubmit'])->name('courses.question.submit');
            Route::get('/{id}/cipd', [EducationController::class, 'cipd'])->name('courses.question.cipd');
            Route::get('/{id}/exportCipdToDoc', [EducationController::class, 'exportCipdToDoc'])->name('courses.question.exportCipdToDoc');

            Route::get('/promocode/check', [EducationController::class, 'promocode'])->name('courses.promocode');

            Route::get('/verify_code/check', [EducationController::class, 'verify_code'])->name('courses.verify_code');

            Route::get('/{slug?}/{method?}', [EducationController::class, 'single'])->name('courses.single');//{course_id}
            // Route::get('/posts/{post}', 'PostController@show');

            // Route::get('/{slug?}/{method?}', [EducationController::class, 'single'])->name('courses.single');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', [CartController::class, 'cart'])->name('cart');
            Route::get('/add', [CartController::class, 'addCartItem'])->name('addCartItem');
            Route::get('/cartItems', [CartController::class, 'cartItems'])->name('cartItems');
            Route::get('/deleteCartItem', [CartController::class, 'deleteCartItem'])->name('deleteCartItem');
            Route::get('/addCartFeature', [CartController::class, 'addCartFeature'])->name('addCartFeature');
            Route::get('/moveToLater', [CartController::class, 'moveToLater'])->name('moveToLater');
            Route::get('/moveToCart', [CartController::class, 'moveToCart'])->name('moveToCart');
            Route::get('/cartSaveForLater', [CartController::class, 'cartSaveForLater'])->name('cartSaveForLater');
            Route::get('/promoCodeCart', [CartController::class, 'promoCodeCart'])->name('promoCodeCart');
        });

        Route::group(['prefix' => 'wishlist'], function () {
            Route::get('/', [WishlistController::class, 'wishlist'])->name('wishlist');
            Route::get('/add', [WishlistController::class, 'addToWishlist'])->name('addToWishlist');
            Route::get('/cartItems', [WishlistController::class, 'wishlistItems'])->name('wishlistItems');
            Route::get('/deleteWishlistItem', [WishlistController::class, 'deleteWishlistItem'])->name('deleteWishlistItem');
        });

        Route::get('/course_categories/{post_type?}', [EducationController::class, 'courses'])->name('course_categories');
    });

    // Route::group(['prefix' => 'epay', 'as' => 'epay.'], function () {
    //     Route::post('prepare_the_checkout', 'EpayController@prepare_the_checkout')->name('prepare_the_checkout');
    //     Route::get('/{slug?}/transaction', [EpayController::class, 'transaction'])->name('transaction');
    //     Route::get('/{session_id}/{user}/session_payment', [EpayController::class, 'session_payment'])->name('session_payment');
    //     Route::get('/payment', 'EpayController@payment')->name('payment.post');
    //     Route::get('/thanks/{status}/{code}/{description}', 'EpayController@thanks')->name('payment.thanks');
    // });
});
