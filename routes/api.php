<?php

use App\Http\Controllers\user\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\PostController;
use App\Http\Controllers\user\UserController;
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

include 'admin.php';

Route::post('/user/signup', [UserController::class, 'signup']);
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/logout',[UserController::class,'logout'])->middleware('auth:sanctum');
Route::post('user/change/pass',[UserController::class, 'changePass'])->middleware('auth:sanctum');

Route::prefix('profile/posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/',[PostController::class,'index']);
    Route::get('/{post}',[PostController::class,'show']);
    Route::post('/{category}', [PostController::class , 'store']);
    Route::delete('/{post}', [PostController::class, 'destroy']);
    Route::patch('/{post}/update', [PostController::class,'update']);
});


Route::get('posts',[\App\Http\Controllers\PostController::class,'index']);
Route::get('posts/category',[CategoryController::class,'index']);
Route::get('posts/category/{category}',[CategoryController::class,'show']);
Route::get('posts/{post}',[\App\Http\Controllers\PostController::class,'show']);
Route::post('posts/{post}/like',[\App\Http\Controllers\PostController::class,'like']);

Route::get('tags',[TagController::class,'index']);
Route::get('posts/tags/{tag}',[TagController::class,'show']);

Route::get('/posts/{post}/comments', [CommentController::class,'show']);
Route::post('/posts/{post}/comments', [CommentController::class , 'store'])->middleware('auth:sanctum');











