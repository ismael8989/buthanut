<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\AuthMiddleware;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('verify')->group(function () {
    Route::post('/send', [VerificationController::class, 'sendVerificationNumber']);
    Route::post('/check', [VerificationController::class, 'checkVerificationNumber']);
});

Route::prefix('password')->group(function () {
    Route::post('/request-reset', [PasswordResetController::class, 'requestPasswordReset']);
    Route::post('/reset', [PasswordResetController::class, 'resetPassword']);
});

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);

    Route::get('/carnets', [CarnetController::class, 'index']);
    Route::get('/carnets/{id}', [CarnetController::class, 'show']);
    Route::post('/carnets', [CarnetController::class, 'store']);
    Route::put('/carnets/{id}', [CarnetController::class, 'update']);
    Route::delete('/carnets/{id}', [CarnetController::class, 'destroy']);

    Route::get('/transactions/{carnet_id}', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
    Route::post('/transactions/aggregate/{carnet_id}', [TransactionController::class, 'aggregate']);
});

Route::get('/carnet-content/{token}', [CarnetController::class, 'getContent']);
