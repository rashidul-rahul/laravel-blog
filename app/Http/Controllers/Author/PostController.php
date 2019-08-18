<?php

namespace App\Http\Controllers\Author;

use App\Category;
use App\Notifications\NewUserPost;
use App\Post;
use App\Tag;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::User()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'tags' => 'required',
            'categories' => 'required',
            'body' => 'required',
            'image' => 'required|mimes:jpg,jpeg,bmp,png'
        ]);
        if($request->method() == 'POST'){
            $image = $request->file('image');
            $slug = Str::slug($request->title, '-');

            if(isset($image)){
                $currentDateTime = Carbon::now()->toDateString();
                $imageName = $slug.'-'.$currentDateTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();

                if(!Storage::disk('public')->exists('post')){
                    Storage::disk('public')->makeDirectory('post/');
                }

                $postImage = Image::make($image)->resize(1600, 1066)->save();

                Storage::disk('public')->put('post/'.$imageName, $postImage);
            } else {
                $imageName = 'default.png';
            }

            $post = new Post();

            $post->title = $request->title;
            $post->user_id = Auth::id();
            $post->slug = $slug;
            $post->image = $imageName;
            $post->body = $request->body;

            if(isset($request->status)){
                $post->status = true;
            }else{
                $post->status = false;
            }

            $post->is_approve = false;
            $post->save();

            $post->categories()->attach($request->categories);
            $post->tags()->attach($request->tags);

            $users = User::where('role_id', '1')->get();
            Notification::send($users, new NewUserPost($post));
            Toastr::success('Post Added Successfully', 'Success');
            return redirect()->route('author.post.index');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You are not have permission to access', 'Error');
            return redirect()->back();
        }
        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You are not have permission to access', 'Error');
            return redirect()->back();
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit', compact('post','categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You are not have permission to access', 'Error');
            return redirect()->back();
        }
        $this->validate($request, [
            'title' => 'required',
            'tags' => 'required',
            'categories' => 'required',
            'body' => 'required',
            'image' => 'image'
        ]);
        $image = $request->file('image');
        $slug = Str::slug($request->title, '-');

        if(isset($image)){

            //set image unique name
            $currentDateTime = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDateTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check for folder exist then create folder
            if(!Storage::disk('public')->exists('post')){
                Storage::disk('public')->makeDirectory('post/');
            }

            //delete old post image

            if(Storage::disk('public')->exists('post/'.$post->image)){
                Storage::disk('public')->delete('post/'.$post->image);
            }

            //save new image
            $postImage = Image::make($image)->resize(1600, 1066)->save();
            Storage::disk('public')->put('post/'.$imageName, $postImage);
        } else {
            $imageName = $post->image;
        }

        $post->title = $request->title;
        $post->user_id = Auth::id();
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;

        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }

        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);
        Toastr::success('Post Successfully Updated', 'Success');
        return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You are not have permission to access', 'Error');
            return redirect()->back();
        }
        if(Storage::disk('public')->exists('post/'.$post->image)){
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
        Toastr::success('Post successfully deleted', 'Success');
        return redirect()->back();
    }
}
