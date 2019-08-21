<?php

namespace App\Http\Controllers\Author;

use App\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(){
        $posts = Auth::user()->posts;

        return view('author.comments', compact('posts'));
    }

    public function destroy($id){
        $comment = Comment::findOrFail($id);

        if($comment->post->user->id == Auth::id()){
            $comment->delete();
            Toastr::success('Comment Removed', 'Success');
        }else{
            Toastr::error('You donot have permission', 'Error');
        }

        return redirect()->back();
    }
}
