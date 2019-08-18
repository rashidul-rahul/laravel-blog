<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Notifications\AuthorPostApprove;
use App\Notifications\SubscriberMail;
use App\Post;
use App\Subscribe;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Output\StreamOutput;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
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
        return view('admin.post.create', compact('categories', 'tags'));
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

            $post->is_approve = true;
            $post->save();

            $post->categories()->attach($request->categories);
            $post->tags()->attach($request->tags);

            $subscribers = Subscribe::all();
            foreach ($subscribers as $subscriber){
                Notification::route('mail', $subscriber->email)
                    ->notify(new SubscriberMail($post));
            }
            Toastr::success('Post Added Successfully', 'Success');
            return redirect()->route('admin.post.index');

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
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('post','categories', 'tags'));
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

        $post->is_approve = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);
        Toastr::success('Post Successfully Updated', 'Success');
        return redirect()->route('admin.post.index');
    }

    public function pending(){
        $posts = Post::where('is_approve', 0)->get();
        return view('admin.post.pending', compact('posts'));
    }

    public function approve($id){
        $post = Post::find($id);
        if($post->is_approve == false){
            $post->is_approve = true;
            $post->save();
            $post->user->notify(new AuthorPostApprove($post));

            $subscribers = Subscribe::all();
            foreach ($subscribers as $subscriber){
                Notification::route('mail', $subscriber->email)
                    ->notify(new SubscriberMail($post));
            }
            Toastr::success('Post already approved', 'Success');
            return redirect()->back();
        } else{
            Toastr::info('Post already approved', 'Info');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
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
