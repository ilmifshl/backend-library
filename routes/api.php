<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DetailTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


// Public Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])->name('csrf-cookie');


// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('book', [BookController::class, 'index']);
    
    Route::get('category', [CategoryController::class, 'index']);

    Route::get('transactions', [TransactionController::class, 'index']);

    Route::get('detail_transaction', [DetailTransactionController::class, 'index']);

    Route::middleware(['check.role:2'])->group(function(){
        Route::post('book', [BookController::class, 'store']);
        Route::patch('book/{id}', [BookController::class, 'update']);

        Route::post('category', [CategoryController::class, 'store']);
        Route::patch('category/{id}', [CategoryController::class, 'update']);

        Route::post('transaction', [TransactionController::class, 'store']);
        Route::put('transaction/{id}', [TransactionController::class, 'update']);

        Route::post('detail_transaction', [DetailTransactionController::class, 'store']);
        Route::put('detail_transaction/{id}', [DetailTransactionController::class, 'update']);

        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::get('users', [AuthController::class, 'getAllUsers']);


    });
    
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});
