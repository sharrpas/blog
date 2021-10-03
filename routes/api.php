<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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

Route::post('/PostShow',[\App\Http\Controllers\PostController::class,'show']);

Route::post('/Posts', [\App\Http\Controllers\PostController::class , 'store'])->middleware('auth:sanctum');

Route::post('Delete', [\App\Http\Controllers\PostController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('/post/{id}/update', [\App\Http\Controllers\PostController::class,'update'])->middleware('auth:sanctum');


Route::post('/user/login', [\App\Http\Controllers\Auth\UserController::class, 'login']);

Route::get('/user/logout',[\App\Http\Controllers\Auth\UserController::class,'logout'])->middleware('auth:sanctum');


Route::post('/comments', [\App\Http\Controllers\CommentController::class , 'store']);

Route::post('commentShow', [\App\Http\Controllers\CommentController::class,'show']);






