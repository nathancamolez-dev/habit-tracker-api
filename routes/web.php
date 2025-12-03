<?php

declare(strict_types = 1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): array => [config('app.name')]);

Route::middleware('guest')->group(function (): void {
    Route::prefix('/api')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });
});
