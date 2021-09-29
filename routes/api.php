<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


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





Route::get('/',[\App\Http\Controllers\PostController::class,'index']);

Route::post('/Posts', [\App\Http\Controllers\PostController::class , 'store'])->middleware('auth:sanctum');


Route::post('/user/login', [\App\Http\Controllers\Auth\UserController::class, 'login']);

Route::get('/user/logout',[\App\Http\Controllers\Auth\UserController::class,'logout'])->middleware('auth:sanctum');












