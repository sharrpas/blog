<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{
    public function show(Request $request)
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
