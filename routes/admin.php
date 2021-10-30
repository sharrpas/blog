<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CommentController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\RoleController;
use Illuminate\Support\Facades\Route;


Route::delete('posts/{post}', [PostController::class, 'destroy'])
    ->middleware('auth:sanctum')->middleware('permission:delete_post')->name('posts-deletePost');
Route::delete('comment/{id}', [CommentController::class, 'destroy'])
    ->middleware('auth:sanctum')->middleware('permission:delete_comment')->name('comments-deleteComment');


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/pending_posts', [PostController::class, 'index'])->middleware('permission:show_post')->name('posts-pending');
    Route::get('/post/{post}', [PostController::class, 'show'])->middleware('permission:show_post')->name('posts-status');
    Route::patch('/confirm-post/{post}', [PostController::class, 'update'])->middleware('permission:confirm_post')->name('posts-confirm');
});


Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [CategoryController::class, 'store'])->middleware('permission:add_category')->name('categories-add');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete_category')->name('categories-delete');
    Route::patch('/{category}/update', [CategoryController::class, 'update'])->middleware('permission:update_category')->name('categories-update');
});


Route::prefix('super_admin')->middleware(['auth:sanctum', 'role:super_admin'])->group(function () {

    Route::get('roles', [RoleController::class, 'index'])->name('roles-all');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles-oneRole');
    Route::post('roles', [RoleController::class, 'store'])->name('roles-addRole');        //add role
    Route::patch('roles/{role}', [RoleController::class, 'update'])->name('roles-updateRole');
    Route::delete('roles/{role}', [RoleController::class, 'delete'])->name('roles-deleteRole');

    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions-all');

    Route::post('admins', [AdminController::class, 'store'])->name('admins-add');                      //create admin
    Route::patch('admins/user/{user}/role', [AdminController::class, 'update'])->name('admins-update');  //add role to existing user
    Route::delete('admins/user/{user}/role', [AdminController::class, 'destroy'])->name('admins-delete');//remove role from admins
});
