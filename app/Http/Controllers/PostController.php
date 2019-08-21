<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->Approve()->Published()->paginate(6);
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

    public function categoryPost($slug){
        $category = Category::where('slug', $slug)->first();
        return view('category-post', compact('category'));
    }

    public function tagPost($slug){
        $tag = Tag::where('slug', $slug)->first();
        return view('tag-post', compact('tag'));
    }
}
