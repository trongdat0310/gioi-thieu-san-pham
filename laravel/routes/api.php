<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemCategoryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SupperUserController;
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
    Route::apiResource("account", AccountController::class);

    Route::group(['prefix' => "/supper-user"], function () {
        Route::post('/', [SupperUserController::class, 'store']);
        Route::delete('/{id}', [SupperUserController::class, 'destroy']);
    });

    Route::apiResource("organization", OrganizationController::class);

    Route::apiResource("product", ProductController::class);

    Route::apiResource("media", MediaController::class);

    Route::apiResource("category", CategoryController::class);

    Route::apiResource("item-category", ItemCategoryController::class);

    Route::apiResource("attribute", AttributeController::class);

    // Route::group(['prefix' => "/role"], function () {
    //     Route::get('/', [RoleController::class, 'index']);
    //     Route::post('/store', [RoleController::class, 'store']);
    //     Route::get('/show/{id}', [RoleController::class, 'show']);
    //     Route::put('/update/{id}', [RoleController::class, 'update']);
    //     Route::delete('/destroy/{id}', [RoleController::class, 'destroy']);
    // });

    Route::apiResource("permission", PermissionController::class);

    Route::apiResource("role", RoleController::class);
});

// Route tùy chỉnh xử lý 404
Route::fallback(function () {
    return response()->json("Không tồn tại trang này", 404); // Thay 'errors.404' bằng tên view bạn muốn hiển thị
})->name('404');
