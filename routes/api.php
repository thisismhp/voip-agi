<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    Route::get('province','ProvinceController');
    Route::get('city','CityController');
});
