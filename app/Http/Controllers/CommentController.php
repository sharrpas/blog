<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show(Request $request, Post $post)
    {
        //   return response()->json(Comment::query()->where('post_id' , '=' , $request->post_id)->get());
        
        return response()->json($post->comments);
        }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        // $comment = new Comment();
        // $comment->post_id = $post->id;
        // $comment->comment = $request->comment;
        // $comment->save();

        $post->comments()->create($request->only('comment'));

        return response()->json(['message' => "Comment created for post " . $post->id]);
    }



}
