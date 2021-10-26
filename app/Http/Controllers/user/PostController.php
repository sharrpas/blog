<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\Hash;

class PostController extends Controller
{
    public function index()
    {
//        return DB::table('posts')->paginate(5);
//        return response()->json(Post::query()->where('user_id','=',auth()->id())->paginate(10));
        return response()->json(new PostCollection(Post::query()->where('user_id', auth()->id())->paginate(10)));

    }

    public function store(Category $category, PostRequest $request)
    {

        $request->validate([
            'title' => 'required|min:4|max:20',
            'text' => 'required|min:15',
            'image' => 'image',
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
            return response()->json(['message' => "error"]);
        }
    }


    public function show(Post $post)
    {
//        $like = $post->likes()->where('post_id', '=', $post->id)->count();
        return response()->json(PostResource::make($post->load('tags'),$post->load('comments')));
    }


    public function destroy(Post $post)
    {
        if ($post->user_id == auth()->id()) {
            $post->comments()->delete();
            $post->tags()->detach();
            $post->delete();
            return response()->json(['message' => 'post ' . $post->id . ' and all comments deleted successfully']);
        }
    }

    public function update(Post $post, Request $request)
    {
        $request->validate([
            'title' => 'required|min:4|max:20',
            'text' => 'required|min:15',
        ]);
        if ($post->user_id == auth()->id()) {
            $post->update([
                'title' => $request->title,
                'text' => $request->text,
                'status' => 'pending',
            ]);
            return response()->json('updated successfully');
        }
    }




}
