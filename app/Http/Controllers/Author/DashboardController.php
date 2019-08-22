<?php

namespace App\Http\Controllers\Author;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts;
        $popular_post = $user->posts()
            ->withCount('comments')
            ->withCount('favorite_post_user')
            ->orderBy('view_count', 'desc')
            ->orderBy('comments_count')
            ->take(5)->get();
        $pending_post = $posts->where('is_approve', false);
        $all_view = $posts->sum('view_count');
        return view('author.dashboard', compact('user', 'posts', 'popular_post', 'pending_post','all_view'));
    }
}
