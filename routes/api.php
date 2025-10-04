<?php

declare(strict_types=1);

use App\Http\Controllers\ShortenerController;
use Illuminate\Support\Facades\Route;

Route::prefix('shorten')->name('shorten.')->controller(ShortenerController::class)->group(function () {
    Route::get('{shortCode}/clicks', 'getClicks')->name('getClicks');
    Route::post('{shortCode}/clicks', 'increaseClicks')->name('increaseClicks');

    Route::post('', 'store')->name('store');
    Route::get('{shortCode}', 'show')->name('show');
    Route::put('{shortCode}', 'update')->name('update');
    Route::delete('{shortCode}', 'destroy')->name('destroy');
});
    