<?php

use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\RoleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
});

Route::middleware(['auth:sanctum', 'auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => "setting"], function () {
        Route::group(['prefix' => "account"], function () {
            //Account
            Route::get('/', [AccountController::class, 'index'])->name('setting.account');
            Route::get('/get-account', [AccountController::class, 'getAccount'])->name('setting.getAccount');
            Route::get('/create', [AccountController::class, 'create'])->name('setting.account.create');
            Route::post('/store', [AccountController::class, 'store'])->name('setting.account.store');
            Route::get('/show/{id}', [AccountController::class, 'show'])->name('setting.account.show');
            Route::post('/update/{id}', [AccountController::class, 'update'])->name('setting.account.update');
            Route::get('/destroy/{id}', [AccountController::class, 'destroy'])->name('setting.account.destroy');
            Route::get('/change-status/{id}', [AccountController::class, 'changeStatus'])->name('setting.account.changeStatus');
        });

        Route::group(['prefix' => "role"], function () {
            //Role
            Route::get('/', [RoleController::class, 'index'])->name('setting.role');
            Route::get('/get-role', [RoleController::class, 'getRole'])->name('setting.role.get');
            Route::get('/create', [RoleController::class, 'create'])->name('setting.role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('setting.role.store');
            Route::get('/show/{id}', [RoleController::class, 'show'])->name('setting.role.show');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('setting.role.update');
            Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->name('setting.role.destroy');
            Route::get('/change-status/{id}', [RoleController::class, 'changeStatus'])->name('setting.role.changeStatus');
        });

    });
});

// Route tùy chỉnh xử lý 404
Route::fallback(function () {
    return view('404'); // Thay 'errors.404' bằng tên view bạn muốn hiển thị
})->name('404');