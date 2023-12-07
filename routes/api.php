<?php

use App\Http\Controllers\Api\CasesApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ItemApiController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('categories')->name('api.categories.')->group(function () {
        Route::get('/', [CategoryApiController::class, 'index'])->name('all');
        Route::post('/create', [CategoryApiController::class, 'create'])->name('create');
        Route::put('/{category}', [CategoryApiController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('cases')->name('api.cases.')->group(function () {
        Route::get('/open/{case}', [CasesApiController::class, 'openCase'])->name('open');

        Route::get('/', [CasesApiController::class, 'index'])->name('all');
        Route::get('/{case}', [CasesApiController::class, 'show'])->name('show');
        Route::post('/create', [CasesApiController::class, 'create'])->name('create');
        Route::put('/{case}', [CasesApiController::class, 'update'])->name('update');
        Route::delete('/{case}', [CasesApiController::class, 'delete'])->name('delete');
    });

    Route::prefix('items')->name('api.items.')->group(function () {
        Route::get('/', [ItemApiController::class, 'index'])->name('all');
        Route::get('/{item}', [ItemApiController::class, 'show'])->name('show');
        Route::post('/create', [ItemApiController::class, 'create'])->name('create');
        Route::put('/{item}', [ItemApiController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemApiController::class, 'delete'])->name('delete');
    });
});
