<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PairController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->apiResource('/pairs', PairController::class, ['only' => ['update', 'destroy', 'store']]);

// Currency converter with prefix api/
// Route::apiResource('/pairs', PairController::class, ['only' => ['index', 'show']]);
// Route::get('/convert', PairController::class . '@convert');
Route::get('/convert', [PairController::class, 'convert']);

Route::post('login', [AuthController::class, 'authenticate']);

Route::get('/pairs', [PairController::class, 'index'])->middleware('auth:sanctum');