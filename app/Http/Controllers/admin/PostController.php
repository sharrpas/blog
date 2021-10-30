<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    public function index()
    {
        return response()->json(
            new PostCollection(
                Post::query()->with('category')->where('status', '=', 'pending')->paginate(10)
            )
        );
    }


    public function update(Post $post, Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in(['accepted', 'rejected'])]
        ]);
        $post->update([
            'status' => $request->status
        ]);
        return $this->response(1,'post '.$request->status);
    }


    public function show(Post $post)
    {
        return response()->json(PostResource::make($post->load('tags')));
    }


    public function destroy(Post $post)
    {
        $post->comments()->delete();
        $post->tags()->detach();
        $post->delete();
        return $this->response(1, 'post ' . $post->id . ' and all comments deleted successfully');
    }


}
