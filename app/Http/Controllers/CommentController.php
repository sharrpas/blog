<?php

namespace App\Http\Controllers;


use App\Models\comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show(Post $post)
    {
        //   return response()->json(Comment::query()->where('post_id' , '=' , $request->post_id)->get());
        return response()->json($post->comments);

    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'name' => 'required',
            'comment' => 'required',
        ]);
        $post->comments()->create($request->only('comment','name','email'));
        return response()->json(['message' => "Comment created for post " . $post->id]);
    }

    public function destroy(comment $id)
    {
        $id->delete();
        return 'Comment ' . $id->id . ' deleted successfully';
    }



}
