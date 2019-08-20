<?php

namespace App\Http\Controllers;

use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addFavorite($post){
        $user = Auth::user();

        $is_favorite = $user->favorite_post()->where('post_id', $post)->count();

        if($is_favorite == 0){
            $user->favorite_post()->attach($post);
            Toastr::success('Post added to favorite', 'Success');
            return redirect()->back();
        } else{
            $user->favorite_post()->detach($post);
            Toastr::success('Post remove from favorite', 'Success');
            return redirect()->back();
        }
    }
}
