<?php

use App\Http\Controllers\GetCurrencyConversionController;
use App\Http\Controllers\ListBalancesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateLedgerController;
use App\Http\Controllers\CreateTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/ledgers')
    ->name('ledgers')
    ->scopeBindings()
    ->middleware([
        'throttle:10,1',
    ])
    ->group(function () {
        Route::post('/', CreateLedgerController::class)->name('.create');

        Route::prefix('/{ledger}')->group(function () {
            Route::prefix('/transactions')->name('.transactions')->group(function () {
                Route::post('/', CreateTransactionController::class)->name('.create');
            });

            Route::prefix('/balances')->name('.balances')->group(function () {
                Route::get('/', ListBalancesController::class)->name('.index');
            });
        });
    });

Route::get('/convert', GetCurrencyConversionController::class)->name('conversion');

