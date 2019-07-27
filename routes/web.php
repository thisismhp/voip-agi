<?php


use Illuminate\Support\Facades\Route;

if (!request()->expectsJson()) {
    Route::get('/{any}', 'LayoutController')->where('any', '.*');
}