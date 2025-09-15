<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UsersController;
use App\Http\Controllers\Api\v1\DispatcherController;
use App\Http\Controllers\Api\v1\ManagerController;
use App\Http\Controllers\Api\v1\LocationController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

//Route::middleware('auth')->group(function() {
Route::prefix('v1')->group(function () {

    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::get('/users/{user}', [UsersController::class, 'show']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);

    Route::get('/dispatchers', [DispatcherController::class, 'index']);
    Route::post('/dispatchers', [DispatcherController::class, 'store']);
    Route::get('/dispatchers/{id}', [DispatcherController::class, 'show']);
    Route::put('/dispatchers/{id}', [DispatcherController::class, 'update']);
    Route::delete('/dispatchers/{id}', [DispatcherController::class, 'destroy']);

    Route::get('/locations', [LocationController::class, 'index']);
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('/locations/{id}', [LocationController::class, 'show']);
    Route::put('/locations/{id}', [LocationController::class, 'update']);
    Route::delete('/locations/{id}', [LocationController::class, 'destroy']);

    Route::get('/managers', [ManagerController::class, 'index']);
    Route::post('/managers', [ManagerController::class, 'store']);
    Route::get('/managers/{id}', [ManagerController::class, 'show']);
    Route::put('/managers/{id}', [ManagerController::class, 'update']);
    Route::delete('/managers/{id}', [ManagerController::class, 'destroy']);


    Route::post('managers/{id}/dispatchers', [ManagerController::class, 'syncDispatchers']);
});

