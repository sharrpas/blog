<?php

namespace App\Http\Controllers;


use App\Models\comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show(Request $request)
    {
          return response()->json(comment::all()->where('post_id' , '=' , $request->post_id));
    }

    public function store(Request $request)
    {
        $comment = new comment();
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->save();
    }

    public function destroy($id)
    {
        comment::query()->find($id)->delete();
        return 'Comment ' . $id . ' deleted successfully';
    }


}
