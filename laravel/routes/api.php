<?php

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\TokenController;

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

Route::apiResource('files', FileController::class);
Route::post('files/{file}', [FileController::class, 'update_post']);




Route::post('/register', [TokenController::class, 'register']);
Route::post('/login', [TokenController::class, 'login']);
Route::post('/logout', [TokenController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/user', [TokenController::class, 'user'])->middleware(['auth:sanctum']);

Route::apiResource('profiles', ProfileController::class);

Route::apiResource('movies', MovieController::class);

Route::apiResource('reviews', ReviewController::class);


