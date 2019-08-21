<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(){
        $comments = Comment::latest()->get();

        return view('admin.comments', compact('comments'));
    }

    public function destroy($id){
        $comment = Comment::findOrFail($id);
        $comment->delete();
        Toastr::success('Comment Removed', 'Success');
        return redirect()->back();
    }
}
