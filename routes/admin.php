<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SaleController;
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
            return redirect()->route('admin.home');
        })->name('home');

        Route::get('home', [HomeController::class, 'home'])->name('home');
        Route::resource('admins', AdminController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');
        Route::post('products/upload', [ProductController::class, 'upload'])->name('products.upload');
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::resource('purchases', PurchaseController::class)->except('show');
        Route::get('purchases/report', [PurchaseController::class, 'report'])->name('purchases.report');
        Route::resource('sales', SaleController::class)->except('show');
        Route::get('sales/report', [SaleController::class, 'report'])->name('sales.report');
    });
