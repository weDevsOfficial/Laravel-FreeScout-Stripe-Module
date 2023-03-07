<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\Stripe\Http\Controllers'], function () {
    Route::get('/stripe/settings/{mailbox}', 'StripeController@index')->name('stripe.settings');
    Route::put('/stripe/settings/{mailbox}', 'StripeController@update')->name('stripe.settings.update');
    Route::delete('/stripe/settings/{mailbox}', 'StripeController@destroy')->name('stripe.settings.destroy');
});
