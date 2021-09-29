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







Route::post('/savePost', [\App\Http\Controllers\PostController::class , 'store'])->middleware('auth:sanctum');

Route::get('/posts',function (){return response()->json([\App\Models\Post::all()->toArray()]);});

Route::post('/user/login', [\App\Http\Controllers\Auth\UserController::class, 'login']);

Route::get('/user/logout',[\App\Http\Controllers\Auth\UserController::class,'logout'])->middleware('auth:sanctum');

Route::get('/user', function (){ return \request()->user('');})->middleware('auth:sanctum');






Route::post('ppp', function (Request $request) {

    //  return storage_path('images/p.jpg');

//        return Storage::disk('public')->get('images/p.jpg');
//        return Storage::disk('public')->download('images/p.jpg','sina');
//        return Storage::disk('public')->url('images/p.jpg');

//Storage::put('file.jpg', $contents);
//         Manually specify a filename...
//        $path = Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');

//888
});





