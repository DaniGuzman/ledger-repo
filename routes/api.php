<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CreateLedgerController;
use \App\Http\Controllers\CreateTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('/ledgers')->name('ledgers')->group(function () {
    Route::post('/', CreateLedgerController::class)->name('.create');

    Route::prefix('/{ledger}')->group(function () {
        Route::prefix('/transactions')->name('.transactions')->group(function () {
            Route::post('/', CreateTransactionController::class)->name('.create');
        });
    });
})->scopeBindings();

