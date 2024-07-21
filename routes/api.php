<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BookController;

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

/**
 * Book Routes
 */
Route::prefix('books')->controller(BookController::class)->group(function() {
    Route::get('/', 'index')->name('api.books.index');
    Route::get('/{id}', 'getByID')->name('api.books.get');
    Route::post('/', 'create')->name('api.books.create');
    Route::patch('/{id}', 'update')->name('api.books.update');
    Route::delete('/{id}', 'delete')->name('api.books.delete');
});
