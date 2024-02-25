<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register.user');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.user.functionality');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login.user');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.user.functionality');

    Route::get('admin/login', [AuthenticatedAdminController::class, 'create'])->name('login.form.admin');
    Route::post('admin/login', [AuthenticatedAdminController::class, 'store'])->name('login.admin.functionality');
});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('admin')->group(function () {

    Route::put('password', [PasswordController::class, 'update'])->name('admin.password.update');

    Route::post('admin/logout', [AuthenticatedAdminController::class, 'destroy'])
        ->name('logout.admin');
});
