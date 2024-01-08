<?php

use App\Http\Controllers\Api\CasesApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\RequestedItemsApiController;
use App\Http\Controllers\Api\TypeApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\UserInventoryApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth')->name('api.')->group(function () {
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::post('/cases/{category}', [CategoryApiController::class, 'categoryCases'])->name('cases');

        Route::get('/', [CategoryApiController::class, 'index'])->name('all');
        Route::get('/{category}', [CategoryApiController::class, 'show'])->name('show');
        Route::post('/create', [CategoryApiController::class, 'create'])->name('create');
        Route::put('/{category}', [CategoryApiController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/open/{case}', [CasesApiController::class, 'openCase'])->name('open');
        Route::post('/items/{case}', [CasesApiController::class, 'caseItems'])->name('items');
        Route::post('/open/exchangeItems', [CasesApiController::class, 'exchangeOpenedItems'])->name('exchangeOpenedItems');

        Route::get('/', [CasesApiController::class, 'index'])->name('all');
        Route::get('/{case}', [CasesApiController::class, 'show'])->name('show');
        Route::post('/create', [CasesApiController::class, 'create'])->name('create');
        Route::put('/{case}', [CasesApiController::class, 'update'])->name('update');
        Route::delete('/{case}', [CasesApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemApiController::class, 'index'])->name('all');
        Route::get('/{item}', [ItemApiController::class, 'show'])->name('show');
        Route::post('/create', [ItemApiController::class, 'create'])->name('create');
        Route::put('/{item}', [ItemApiController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('types')->name('types.')->group(function() {
        Route::get('/', [TypeApiController::class, 'index'])->name('all');
        Route::get('/{type}', [TypeApiController::class, 'show'])->name('show');
        Route::post('/create', [TypeApiController::class, 'create'])->name('create');
        Route::put('/{type}', [TypeApiController::class, 'update'])->name('update');
        Route::delete('/{type}', [TypeApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [UserInventoryApiController::class, 'index'])->name('all');
        Route::post('/add', [UserInventoryApiController::class, 'addToInventory'])->name('add');
        Route::delete('/delete', [UserInventoryApiController::class, 'removeFromInventory'])->name('delete');

        Route::post('/exchange', [UserInventoryApiController::class, 'exchangeItems'])->name('exchange');
    });

    Route::prefix('request-items')->name('request-items.')->group(function () {
        Route::get('/', [RequestedItemsApiController::class, 'index'])->name('all');
        Route::post('/create', [RequestedItemsApiController::class, 'create'])->name('create');

        //todo: add middleware to check if user is admin
        Route::put('/{requestedItem}', [RequestedItemsApiController::class, 'update'])->name('update');

        Route::delete('/{requestedItem}', [RequestedItemsApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/me', [UserApiController::class, 'getCurrentUser'])->name('me');
    });
});
