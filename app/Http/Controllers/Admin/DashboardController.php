<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        $popular_post = Post::withCount('comments')
            ->withCount('favorite_post_user')
            ->orderBy('view_count', 'desc')
            ->orderBy('comments_count')
            ->take(5)->get();
        $pending_post = $posts->where('is_approve', false)->count();
        $all_view = $posts->sum('view_count');
        $author_count = User::where('role_id', 2)->count();
        $new_authors_today = User::where('role_id', 2)->whereDate('created_at', Carbon::today())->count();
        $category_count = Category::all()->count();
        $tag_count = Tag::all()->count();
        return view('admin.dashboard', compact('posts', 'popular_post', 'pending_post', 'all_view', 'author_count', 'new_authors_today', 'category_count', 'tag_count'));
    }
}
