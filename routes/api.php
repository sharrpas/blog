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

Route::get('/posts/{post}',[\App\Http\Controllers\PostController::class,'show']);

Route::post('/posts', [\App\Http\Controllers\PostController::class , 'store'])->middleware('auth:sanctum');

Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->middleware('auth:sanctum');


Route::post('/user/login', [\App\Http\Controllers\Auth\UserController::class, 'login']);

Route::post('/user/logout',[\App\Http\Controllers\Auth\UserController::class,'logout'])->middleware('auth:sanctum');


Route::post('/posts/{post}/comments', [\App\Http\Controllers\CommentController::class , 'store']);

Route::get('/posts/{post}/comments', [\App\Http\Controllers\CommentController::class,'show']);






