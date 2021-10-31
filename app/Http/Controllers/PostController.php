<?php

namespace App\Http\Controllers;

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

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
            'in' => [Rule::in(['all', 'posts', 'comments'])]
        ]);

        $search = $request->search;
        $in = $request->in != '' ? $request->in : 'all';

        $search_in_post = false;
        $search_in_comment = false;

        if ($in == 'posts') $search_in_post = true;
        if ($in == 'comments') $search_in_comment = true;
        if ($in == 'all') {
            $search_in_comment = true;
            $search_in_post = true;
        }

        $posts = '';
        $comments = '';
        if ($search_in_post == true) {
            $posts = PostResource::collection((Post::query()
                ->where('title', 'LIKE', "%{$search}%")
                ->orWhere('text', 'LIKE', "%{$search}%")
                ->get()));
        }
        if ($search_in_comment == true) {
            $comments = CommentResource::collection((Comment::query()
                ->where('comment', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->get()));
        }

        return [$posts, $comments];
    }
}
