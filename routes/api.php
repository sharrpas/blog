<?php

use App\Http\Controllers\LikePostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\user\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\PostController;
use App\Http\Controllers\user\UserController;
use App\Models\Post;
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

Route::post('/user/signup', [UserController::class, 'signup'])->name('signup');
Route::post('/user/login', [UserController::class, 'login'])->name('login');
Route::post('/user/logout',[UserController::class,'logout'])->middleware('auth:sanctum')->name('logout');
Route::post('user/change/pass',[UserController::class, 'changePass'])->middleware('auth:sanctum')->name('changePass');

Route::prefix('profile/posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/',[PostController::class,'index'])->name('profile-posts-all');
    Route::get('/{post}',[PostController::class,'show'])->name('profile-posts-onePost');
    Route::post('/{category}', [PostController::class , 'store'])->name('profile-posts-addPost');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('profile-posts-deletePost');
    Route::patch('/{post}/update', [PostController::class,'update'])->name('profile-posts-updatePost');
});


Route::get('posts',[\App\Http\Controllers\PostController::class,'index'])->name('posts-all');
Route::get('posts/category',[CategoryController::class,'index'])->name('categories-all');
Route::get('posts/category/{category}',[CategoryController::class,'show'])->name('posts-all-oneCategory');
Route::get('posts/{post}',[\App\Http\Controllers\PostController::class,'show'])->name('posts-onePost');
Route::post('posts/{post}/like',[LikePostController::class,'store'])->name('post-like');

Route::get('tags',[TagController::class,'index'])->name('tags-all');
Route::get('posts/tags/{tag}',[TagController::class,'show'])->name('posts-all-oneTag');

Route::get('/posts/{post}/comments', [CommentController::class,'show'])->name('comments-onePost');
Route::post('/posts/{post}/comments', [CommentController::class , 'store'])->middleware('auth:sanctum')->name('comments-addComment');

Route::post('search',[SearchController::class,'show'])->name('search');









