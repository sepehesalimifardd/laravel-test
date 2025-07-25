<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)
        ->except(['destroy']);

    Route::get('/products/{product}/history', [ProductHistoryController::class, 'index'])
        ->middleware('can:viewHistory,product');
    Route::get('/products/{product}/history/{version}', [ProductHistoryController::class, 'show'])
        ->middleware('can:viewVersion,product');
    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}/children', [CategoryController::class, 'children']);
        Route::get('/{id}/descendants', [CategoryController::class, 'descendants']);
        Route::get('/{id}/ancestors', [CategoryController::class, 'ancestors']);
        Route::patch('/{id}/move', [CategoryController::class, 'move']);
    });

});
