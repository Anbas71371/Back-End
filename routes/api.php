<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisterController::class, 'store']);
Route::get('/nama', [RegisterController::class, 'show']);
Route::get('/data', [RegisterController::class, 'data']);
Route::get('/userId/{id}', [RegisterController::class, 'userId']);
Route::get('getUsername/{id}', 'App\Http\Controllers\RegisterController@getUsername');
Route::get('getLoggedInUserId', 'App\Http\Controllers\RegisterController@getLoggedInUserId');



Route::post('/pinjaman', [PinjamanController::class, 'store']);
Route::get('/getuser', [PinjamanController::class, 'index'])->middleware('auth:api');
Route::get('/getuser/{id}', [PinjamanController::class, 'index'])->middleware('auth:api');



Route::post('/login', [LoginController::class, 'login']); // Menggunakan metode login pada LoginController
// Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');


Route::get('/users/{id}', [UserController::class, 'getUserById']);
Route::get('/getLoggedInUser', [UserController::class, 'getLoggedInUser'])->middleware('auth:api');
Route::middleware('auth:api')->put('/update', [UserController::class, 'update']);
