<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index()
    {
        return response()->json([\App\Models\Post::all()->toArray()]);
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

        $ImageName = date('Ymdhis') . rand(100, 999) . '.jpg';
        Storage::putFileAs('images', $request->file('image'), $ImageName);
        Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
            'image' => $ImageName,
        ]);
        return response()->json();
    }

    public function destroy(Request $request)
    {
        Post::query()->find($request->id)->delete();
        return 'post ' . $request->id . ' deleted successfully';
    }


}
