<?php

use App\Http\Controllers\Api\V1\booking\AssetController;
use App\Http\Controllers\Api\V1\booking\BookingController;

Route::prefix('v1')->group(function () {
  Route::prefix('resources')->group(function () {
    Route::get('/', [AssetController::class, 'index']);
    Route::post('/', [AssetController::class, 'store']);
    Route::get('/{id}/bookings', [BookingController::class, 'assetBookings']);
  });

  Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/', [BookingController::class, 'store']);
    Route::delete('/{id}', [BookingController::class, 'destroy']);
  });
});