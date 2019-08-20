<?php

namespace App\Http\Controllers\Author;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->favorite_post;
        return view('author.post.favorite', compact('posts'));
    }

    public function removeFavorite($id)
    {
        Auth::user()->favorite_post()->detach($id);
        Toastr::success('Remove From favorite', 'Success');
        return redirect()->back();
    }
}
