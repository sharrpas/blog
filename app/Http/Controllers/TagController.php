<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::all());
    }

    public function show(Tag $tag)
    {
        return response()->json(Tag::with(['posts' => function ($posts) {
            return $posts->where('status', 'accepted');
        }])->find($tag));
    }

}
