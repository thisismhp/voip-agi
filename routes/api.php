<?php

use Illuminate\Support\Facades\Route;

if (request()->expectsJson()) {
    Route::namespace('Api')->group(function () {
        Route::get('province', 'ProvinceController');
        Route::get('city', 'CityController');
        Route::get('phone_number_type', 'PhoneNumberTypeController');
        Route::get('charge_type', 'ChargeTypeController');
        Route::apiResource('customer', 'CustomerController');
        Route::get('demo_user', 'DemoUserController');
        Route::post('charge', 'ChargeController');
    });
}
