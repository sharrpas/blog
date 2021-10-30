<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|unique:App\Models\Category,category'
        ]);
        $category = new Category();
        $category->category = $request->category;
        $category->save();
        return response()->json(['message' => 'category created']);
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->exists()){
            return response()->json(['message' => 'the category has posts so it can not be deleted']);
        }
        $category->delete();
        return response()->json(['message' => 'category deleted']);
    }

    public function update(Category $category, Request $request)
    {
        $request->validate([ 'category' => 'required' ]);
        $category->update([
            'category' => $request->category,
        ]);
        return response()->json(['message' => 'category updated to: ' . $request->category]);
    }
}




