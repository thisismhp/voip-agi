<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    Route::get('province','ProvinceController');
    Route::get('city','CityController');
    Route::get('phone_number_type','PhoneNumberTypeController');
    Route::get('charge_type','ChargeTypeController');
    Route::apiResource('customer','CustomerController');
    Route::get('demo_user','DemoUserController');
});
