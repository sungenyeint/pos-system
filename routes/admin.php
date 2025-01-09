<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest:admin')
    ->controller(LoginController::class)
    ->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('authenticate', 'authenticate')->name('authenticate');
    });

Route::middleware('auth:admin')
    ->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/', function () {
            return redirect()->route('admin.admins.index');
        })->name('index');
        Route::resource('admins', AdminController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');
    });
