<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CommentController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\RoleController;
use Illuminate\Support\Facades\Route;


Route::delete('posts/{post}',[PostController::class,'destroy'])->middleware('auth:sanctum')->middleware('permission:delete_post');
Route::delete('comment/{id}', [CommentController::class,'destroy'])->middleware('auth:sanctum')->middleware('permission:delete_comment');



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/pending_posts',[PostController::class,'index'])->middleware('permission:show_post');
    Route::get('/post/{post}',[PostController::class,'show'])->middleware('permission:show_post');
    Route::patch('/confirm_post/{post}',[PostController::class,'update'])->middleware('permission:confirm_post');
});


Route::prefix('categories')->middleware('auth:sanctum')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store'])->middleware('permission:add_category');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete_category');
    Route::patch('/{category}/update', [CategoryController::class, 'update'])->middleware('permission:update_category');
});



Route::prefix('super_admin')->middleware(['auth:sanctum', 'role:super_admin'])->group(function () {

    Route::get('roles',[RoleController::class,'index']);
    Route::get('roles/{role}',[RoleController::class,'show']);
    Route::post('roles', [RoleController::class, 'store']);        //add role
    Route::patch('roles/{role}', [RoleController::class,'update']);
    Route::delete('roles/{role}',[RoleController::class,'delete']);

    Route::get('permissions',[PermissionController::class,'index']);

    Route::post('admins', [AdminController::class, 'store']);                   //create admin
    Route::patch('admins/user/{user}/role',[AdminController::class,'update']);  //add role to exist user
    Route::delete('admins/user/{user}/role',[AdminController::class,'destroy']);//remove role from admins
});
