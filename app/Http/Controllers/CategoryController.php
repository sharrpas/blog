<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required'
        ]);
        $category = new Category();
        $category->category = $request->category;
        $category->save();
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }

    public function update(Category $category, Request $request)
    {
        $category->update([
            'category' => $request->category,
        ]);
    }
}




