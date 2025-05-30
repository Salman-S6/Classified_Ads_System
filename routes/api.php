<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController as ControllersReviewController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class);

    Route::get('ads/rejected', [AdController::class, 'rejectedAds']);

    Route::post('/ads/{ad}/images', [AdController::class, 'addImage']);

    Route::apiResource('ads', AdController::class);

    Route::apiResource('reviews', ReviewController::class);
});
