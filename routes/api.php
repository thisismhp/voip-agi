<?php

use Illuminate\Support\Facades\Route;

if (request()->expectsJson()) {
    Route::namespace('Api')->group(function () {
        Route::post('my', 'ChangePasswordController');
        Route::get('access', 'CheckAccessController');
        Route::patch('logout', 'LogoutController');
        Route::apiResource('customer', 'CustomerController');
        Route::apiResource('service', 'ServiceController');
        Route::apiResource('user', 'UserController')->except(['destroy']);
        Route::apiResource('symbol', 'SymbolController')->except(['show','destroy']);
        Route::apiResource('service_group', 'ServiceGroupController');
        Route::post('charge', 'ChargeController');
        Route::post('check_charge', 'CheckChargeController');
        Route::post('cmt', 'CMTController');
        Route::get('province', 'ProvinceController');
        Route::get('city', 'CityController');
        Route::get('phone_number_type', 'PhoneNumberTypeController');
        Route::get('charge_type', 'ChargeTypeController');
        Route::apiResource('unit', 'UnitController')->except(['destroy','update']);
        Route::get('demo_user', 'DemoUserController');
        Route::get('no_charge', 'NoChargeController');
        Route::post('check_service', 'CheckSoapController');
    });
}
