<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommentsController extends Controller
{
    public function store(Requests\PostCommentsRequest $request )
    {
       Comment::create(array_merge($request->all(),['user_id'=>\Auth::user()->id]));
       return redirect()->action('PostsController@show',['id'=>$request->get('discussion_id')]);
    }

    public function destroy($id)
    {

        Comment::destroy($id);
        return redirect()->back();
    }
}
