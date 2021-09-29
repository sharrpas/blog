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

    }

    public function store(PostRequest $request)
    {
//        $post = new \App\Models\Post();
//        $post->title = $request->title;
//        $post->text = $request->text;
//        $post->image = $request->image ?: '#';
//        $post->user_id = 1; // auth()->id
//        $post->save();

        $ImageName = date('Ymdhis') . rand(100,999) . '.jpg' ;
        Storage::putFileAs('images', $request->file('image'), $ImageName);
        Post::create([
            'user_id' => auth()->id() ,
            'title' => $request->title,
            'text' => $request->text,
            'image' => $ImageName,


        ]);

        return response()->json();
    }
}
