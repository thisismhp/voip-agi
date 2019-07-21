<?php


use Illuminate\Support\Facades\Route;

Route::get('/{any}', 'LayoutController')->where('any', '.*');