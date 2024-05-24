<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Shopper\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shopper\OrderController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Request;

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

Route::get('/', [HomeController::class, 'index'])->middleware('verified')->name('home');
Route::get('/search', [SearchController::class, 'index'])->middleware('verified')->name('search');
Route::inertia('/about', 'About/Index')->middleware('verified')->name('about');

Route::middleware('auth')->group(function () {
    Route::middleware('role:shopper', 'verified')->group(function () {
        Route::resource('carts', CartController::class)->names(
            [
                'index' => 'carts.index',
                'edit' => 'carts.edit',
                'destroy' => 'carts.destroy',
                'update' => 'carts.update',
                'store' => 'carts.store'
            ]
        );

        Route::post('orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::get('orders/finish', [OrderController::class, 'finish'])->name('orders.finish');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('products/report', [ProductController::class, 'report'])->name('products.report');

        Route::resource('products', ProductController::class)->names(
            [
                'index' => 'products.index',
                'create' => 'products.create',
                'store' => 'products.store',
                'edit' => 'products.edit',
                'update' => 'products.update',
                'destroy' => 'products.destroy',
            ]
        )->except('show');

        Route::resource('categories', CategoryController::class)->names(
            [
                'index' => 'categories.index',
                'create' => 'categories.create',
                'store' => 'categories.store',
                'edit' => 'categories.edit',
                'update' => 'categories.update',
                'destroy' => 'categories.destroy',
            ]
        )->except('show');
    });
});

Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('orders/payment-error', function (Request $request) {
    dd($request);
    return 'PAYMENT ERROR';
});

require __DIR__ . '/auth.php';
