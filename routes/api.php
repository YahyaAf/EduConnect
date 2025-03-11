<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\CategoryController;

Route::prefix('v1')->group(function () {

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    
});

Route::prefix('v1')->group(function () {

    Route::get('tags', [TagController::class, 'index']);
    Route::post('tags', [TagController::class, 'store']);
    Route::get('tags/{id}', [TagController::class, 'show']);
    Route::put('tags/{id}', [TagController::class, 'update']);
    Route::delete('tags/{id}', [TagController::class, 'destroy']);
    
});

