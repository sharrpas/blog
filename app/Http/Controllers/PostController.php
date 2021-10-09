<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index()
    {
//        return DB::table('posts')->paginate(5);
        return response()->json(\App\Models\Post::query()->paginate(10));
    }

    public function store(Category $category, PostRequest $request)
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

        $request->validate([
            'title' => 'required|min:4|max:20',
            'text' => 'required|min:20',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('image')) {
                $ImageName = date('Ymdhis') . rand(100, 999) . '.jpg';
                Storage::putFileAs('images', $request->file('image'), $ImageName);
            }
            $post = Post::create([
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'title' => $request->title,
                'text' => $request->text,
                'image' => $ImageName ?? null,
            ]);


            $tags = explode(' ', $request->tag);

            foreach ($tags as $item){

                $tag = Tag::query()->firstOrCreate([
                    'tag' => $item
                ]);

                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id
                ]);

            }

            Db::commit();

            return response()->json(['message' => "Post created"]);

        } catch (\Throwable $exception) {
            DB::rollBack();
        }
    }


    public function show(Post $post)
    {
        $like = $post->likes()->where('post_id', '=', $post->id)->count();
        return response()->json([$post, 'likes: ' . $like, $post->tags()->get(['tag'])]);
    }


    public function destroy(Post $post)
    {
        $post->comments()->delete();
        $post->delete();
        return response()->json(['message' => 'post ' . $post->id . ' and all comments deleted successfully']);
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
