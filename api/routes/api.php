<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PairController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
// Route about pairs data;
Route::apiResource('/pairs', PairController::class, ['only' => ['update', 'destroy', 'store']])->middleware('auth:sanctum');
Route::get('/convert', [PairController::class, 'convert']);
Route::get('/pairs', [PairController::class, 'index']);

// Route about AUTH;
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Route about currency data;
Route::apiResource('/currencies', CurrencyController::class, ['only' => ['update', 'destroy', 'store']])->middleware('auth:sanctum');
Route::get('/currencies', [CurrencyController::class, 'index']);

// Ping route for testing if the DATABASE is connected
Route::get('ping', function () {
    // text if the database is connected
    try {
        DB::connection()->getPdo();
        return response()->json(['message' => 'API running'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'API is closed.'], 500);
    }
});
