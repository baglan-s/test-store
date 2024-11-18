<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\API\RegisterController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::prefix('users')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('product_categories')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\ProductCategoryController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('products')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\ProductController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/slug/{slug}', 'slug');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('specifications')
    ->controller(\App\Http\Controllers\API\SpecificationController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('specification-values')
    ->controller(\App\Http\Controllers\API\SpecificationValueController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('cart')
    ->controller(\App\Http\Controllers\API\CartController::class)
    ->group(function () {
        Route::get('/{id}', 'show');
        Route::put('/add/{id}', 'add');
        Route::put('/remove/{id}', 'remove');
        Route::delete('/clear/{id}', 'clear');
    });

Route::prefix('orders')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\OrderController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::delete('/{id}', 'destroy');
        
    });
Route::prefix('orders')
    ->controller(\App\Http\Controllers\API\OrderController::class)
    ->group(function() {
        Route::post('/', 'store');
    });
