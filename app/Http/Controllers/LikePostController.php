<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikePostController extends Controller
{
    public function store(Post $post, Request $request)
    {
        $ip = $request->ip();

        if (Like::query()->where('post_id', '=', $post->id)->where('ip', '=', $ip)->exists()) {
            return $this->response(0,'you already liked this post');
        }
        $post->likes()->create(['ip' => $ip]);
        return \response()->json('liked');

    }
}
