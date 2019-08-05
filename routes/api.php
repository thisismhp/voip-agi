<?php

use Illuminate\Support\Facades\Route;

if (request()->expectsJson()) {
    Route::namespace('Api')->group(function () {
        Route::apiResource('customer', 'CustomerController');
        Route::post('charge', 'ChargeController');
        Route::post('check_charge', 'CheckChargeController');
        Route::post('cmt', 'CMTController');
        Route::get('province', 'ProvinceController');
        Route::get('city', 'CityController');
        Route::get('phone_number_type', 'PhoneNumberTypeController');
        Route::get('charge_type', 'ChargeTypeController');
        Route::get('demo_user', 'DemoUserController');
        Route::get('no_charge', 'NoChargeController');
    });
}
