<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\Stripe\Http\Controllers'], function () {
    Route::get('/stripe/settings/{mailbox}', 'StripeController@index')->name('stripe.settings');
    Route::post('/stripe/settings/{mailbox}', 'StripeController@update')->name('stripe.settings.update');
});
