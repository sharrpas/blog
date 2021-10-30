<?php

namespace App\Http\Controllers\user;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show(Post $post)
    {
//        return response()->json($post->comments);
        return response()->json(CommentResource::collection($post->comments));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        $post->comments()->create([
            'comment' => $request->comment,
            'name' => auth()->user()->name,
        ]);
        return response()->json(['message' => "Comment created for post " . $post->id]);
    }


//{
//    error: false,
//    code: 200,
//    message: "mwssage",
//    data: ""
//}

}
