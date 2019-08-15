<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->method() == 'POST'){

            $this->validate($request, [
                'name' => 'required|unique:categories',
                'image' => 'required|mimes:jpeg,png,bmp,jpg'
            ]);
            //get image
            $image = $request->file('image');
            $slug = Str::slug($request->name, '-');
            if (isset($image)){
                //take current date and time
                $currentDateTime = Carbon::now()->toDateString();
                $imageName = $slug.'-'.$currentDateTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();

                //check folder or create folder
                if(!Storage::disk('public')->exists('category')){
                    Storage::disk('public')->makeDirectory('category');
                }

//                resize image
                $resizeImage = Image::make($image)->resize(1600, 479)->save();
                Storage::disk('public')->put('category/'.$imageName, $resizeImage);


                //check category/storage folder or create folder
                if(!Storage::disk('public')->exists('category/slider')){
                    Storage::disk('public')->makeDirectory('category/slider');
                }

                $resizeSliderImage = Image::make($image)->resize(500, 333)->save();
                Storage::disk('public')->put('category/slider/'.$imageName, $resizeSliderImage);
            }else {
                $imageName = 'default.png';
            }

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->image = $imageName;
            $category->save();
            Toastr::success('Category successfully added', 'Success');
            return redirect()->route('admin.category.index');


        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

            $this->validate($request, [
                'name' => 'required',
                'image' => 'mimes:jpeg,png,bmp,jpg'
            ]);
            //get image
            $image = $request->file('image');
            $slug = Str::slug($request->name, '-');
            $category = Category::find($id);
            if (isset($image)){
                //take current date and time
                $currentDateTime = Carbon::now()->toDateString();
                $imageName = $slug.'-'.$currentDateTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();

                //check folder or create folder
                if(!Storage::disk('public')->exists('category')){
                    Storage::disk('public')->makeDirectory('category');
                }

//                resize image
                $resizeImage = Image::make($image)->resize(1600, 479)->save();
                Storage::disk('public')->put('category/'.$imageName, $resizeImage);
                //delete old image
                if(Storage::disk('public')->exists('category/'.$category->image)){
                    Storage::disk('public')->delete('category/'.$category->image);
                }


                //check category/storage folder or create folder
                if(!Storage::disk('public')->exists('category/slider')){
                    Storage::disk('public')->makeDirectory('category/slider');
                }

                $resizeSliderImage = Image::make($image)->resize(500, 333)->save();
                Storage::disk('public')->put('category/slider/'.$imageName, $resizeSliderImage);

                //delete old slider image
                if(Storage::disk('public')->exists('category/slider/'.$category->image)){
                    Storage::disk('public')->delete('category/slider/'.$category->image);
                }
            }else {
                $imageName = $category->image;
            }

            $category->name = $request->name;
            $category->slug = $slug;
            $category->image = $imageName;
            $category->save();
            Toastr::success('Category successfully Updated', 'Success');
            return redirect()->route('admin.category.index');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if(Storage::disk('public')->exists('category/'.$category->image)){
            Storage::disk('public')->delete('category/'.$category->image);
        }

        if(Storage::disk('public')->exists('category/slider/'.$category->image)){
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        $category->delete();
        Toastr::success('Category Delete Successfully', 'Success');
        return redirect()->back();
    }
}
