<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth.api'])->group(function () {
    Route::group(['prefix' => "/account"], function () {
        Route::get('/', [AccountController::class, 'index']);
    });

    Route::group(['prefix' => "/product"], function () {
        Route::get('/', [ProductController::class, 'index']);
    });

    Route::group(['prefix' => "/media"], function () {
        Route::get('/', [MediaController::class, 'index']);
    });

    Route::group(['prefix' => "/category"], function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    // Route::group(['prefix' => "/permission"], function () {
    //     Route::get('/', [PermissionController::class, 'index']);
    //     Route::post('/store', [PermissionController::class, 'store']);
    //     Route::get('/show/{id}', [PermissionController::class, 'show']);
    //     Route::put('/update/{id}', [PermissionController::class, 'update']);
    //     Route::delete('/destroy/{id}', [PermissionController::class, 'destroy']);
    // });

    Route::apiResource("permission", PermissionController::class);
});
