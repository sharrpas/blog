<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index()
    {
//        return DB::table('posts')->paginate(5);
        return response()->json(\App\Models\Post::query()->paginate(3));
    }

    public function store(PostRequest $request)
    {
//        $post = new \App\Models\Post();
//        $post->title = $request->title;
//        $post->text = $request->text;
//        $post->image = $request->image ?: '#';
//        $post->user_id = 1; // auth()->id
//        $post->save();

//        return storage_path('images/p.jpg');
//        return Storage::disk('public')->get('images/p.jpg');
//        return Storage::disk('public')->download('images/p.jpg','sina');
//        return Storage::disk('public')->url('images/p.jpg');
//        Storage::put('file.jpg', $contents);

        if ($request->hasFile('image')) {
            $ImageName = date('Ymdhis') . rand(100, 999) . '.jpg';
            Storage::putFileAs('images', $request->file('image'), $ImageName);
        }
        Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
            'image' => $ImageName ?? null,
        ]);
        return response()->json(['message' => "Post created"]);
    }


    public function show(Post $post)
    {
        return response()->json($post);
    }


    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'post ' . $post->id . ' deleted successfully']);
    }


    public function update(Post $post, Request $request)
    {
        $post->update([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
        ]);
        return response()->json('updated successfully');
    }


}
