<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        return response()->json(new PostCollection(Post::query()->where('status', '=','accepted')->paginate(10)));

//        return response()->json(Post::query()->where('status', 'accepted')->paginate(10));
    }


    public function show(Post $post)
    {
        return response()->json(PostResource::make($post->load('tags'),$post->load('comments')));
    }


    public function like(Post $post, Request $request)
    {
        $ip = $request->ip();

        if (Like::query()->where('post_id', '=', $post->id)->where('ip', '=', $ip)->exists()) {
            return \response()->json('you already liked this post');
        } else {
            $post->likes()->create(['ip' => $ip]);
            return \response()->json('liked');
        }
    }
}
