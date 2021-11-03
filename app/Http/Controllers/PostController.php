<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    public function index()
    {
        return response()->json(new PostCollection(Post::query()->where('status', '=', 'accepted')->paginate(10)));
//        return response()->json(Post::query()->where('status', 'accepted')->paginate(10));
    }

    public function show(Post $post)
    {
        return response()->json(PostResource::make($post->load('tags'), $post->load('comments')));
    }


}
