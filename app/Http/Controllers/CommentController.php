<?php

namespace App\Http\Controllers;

use App\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = new Comment();

        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->save();
        Toastr::success('Comment add successfully', 'Success');
        return redirect()->back();
//        return $request;


    }
}
