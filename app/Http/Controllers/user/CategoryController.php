<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(CategoryResource::collection(Category::all()));
    }

    public function show($category)
    {

        return response()->json(new PostCollection(Post::query()->where('category_id','=',$category)->where('status', '=','accepted')->paginate(10)));

//        return response()->json(Post::query()->where('category_id','=',$category)->where('is_config', '=', true)->paginate(10));
    }


}
