<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/contacts', ContactController::class);

    Route::post('contacts/bulk-insert', [ContactController::class, 'bulkInsert']);

    Route::middleware('admin')->group(function () {
        Route::apiResource('/users', UserController::class);
        Route::get('/contacts/all/today', [StatsController::class, 'today']);
        Route::get('/contacts/all/week', [StatsController::class, 'week']);
        Route::get('/contacts/all/month', [StatsController::class, 'month']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
