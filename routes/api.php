<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BooksController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('books', [BooksController::class, 'index']);
Route::get('books/{id}', [BooksController::class, 'show']);

Route::post('books', [BooksController::class, 'store']);

Route::match(['put', 'patch'], 'books/{id}', [BooksController::class, 'update']);

Route::delete('books/{id}', [BooksController::class, 'destroy']);

