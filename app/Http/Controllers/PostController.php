<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->paginate(6);
        return view('posts', compact('posts'));
    }

    public function details($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $randomPost = Post::all()->random(3);
        $categories = Category::all();
        $postCountKey = 'post_'.$post->id;
        if(!Session::has($postCountKey)){
            $post->increment('view_count');
            Session::put($postCountKey, 1);
        }
        return view('details', compact('post', 'randomPost', 'categories'));
    }
}
