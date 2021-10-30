<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function destroy(Comment $id)
    {
        $id->delete();
        return $this->response(1,'Comment ' . $id->id . ' deleted successfully');
    }
}
