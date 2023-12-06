<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolPageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});

Route::prefix('logs')->group(function () {
    Route::get('/', [LogController::class, 'index']);
    Route::get('/{id}', [LogController::class, 'show']);
    Route::post('/', [LogController::class, 'create']);
    Route::put('/{id}', [LogController::class, 'update']);
    Route::delete('/{id}', [LogController::class, 'destroy']);
});

Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index']);
    Route::get('/{id}', [PageController::class, 'show']);
    Route::post('/', [PageController::class, 'create']);
    Route::put('/{id}', [PageController::class, 'update']);
    Route::delete('/{id}', [PageController::class, 'destroy']);
});

Route::prefix('persons')->group(function () {
    Route::get('/', [PersonController::class, 'index']);
    Route::get('/{id}', [PersonController::class, 'show']);
    Route::post('/', [PersonController::class, 'create']);
    Route::put('/{id}', [PersonController::class, 'update']);
    Route::delete('/{id}', [PersonController::class, 'destroy']);
});

Route::prefix('rolpage')->group(function () {
    Route::get('/', [RolPageController::class, 'index']);
    Route::get('/{id}', [RolPageController::class, 'show']);
    Route::post('/', [RolPageController::class, 'create']);
    Route::put('/{id}', [RolPageController::class, 'update']);
    Route::delete('/{id}', [RolPageController::class, 'destroy']);
});

Route::prefix('rols')->group(function () {
    Route::get('/', [RolController::class, 'index']);
    Route::get('/{id}', [RolController::class, 'show']);
    Route::post('/', [RolController::class, 'create']);
    Route::put('/{id}', [RolController::class, 'update']);
    Route::delete('/{id}', [RolController::class, 'destroy']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'create']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});