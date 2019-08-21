<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class SearchController extends Controller
{
    public function search(Request $request){
        $query = $request->input('query');
        $posts = Post::where('title','LIKE',"%$query%")->Approve()->Published()->get();
        return view('search', compact('posts', 'query'));
    }
}
