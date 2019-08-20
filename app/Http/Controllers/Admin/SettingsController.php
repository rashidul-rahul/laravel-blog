<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|email',
            'image'=>'image'
        ]);
        $user = User::findOrFail(Auth::id());
        $image = $request->file('image');

        if(isset($image)){
            $slug = Str::slug($request->name, '-');

            $currentDateTime = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDateTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('profile_pic')){
                Storage::disk('public')->makeDirectory('profile_pic');
            }

            $resizeImage = Image::make($image)->resize(200,200)->save();
            Storage::disk('public')->put('profile_pic/'.$imageName, $resizeImage);

             //delete old image

            if(Storage::disk('public')->exists('profile_pic/'.$user->image)){
                Storage::disk('public')->delete('profile_pic'.$user->image);
            }
        }else{
            $imageName = $user->image;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->about = $request->about;

        $user->save();
        Toastr::success('Profile Update Successful', 'Success');
        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hashedPassword = Auth::user()->getAuthPassword();
        if(Hash::check($request->old_password, $hashedPassword)){
            if(!Hash::check($request->password, $hashedPassword)){
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();

                Auth::logout();
                Toastr::success('Password Changed', 'Success');
                return redirect()->back();
            }else{
                Toastr::error('New password is same as old password', 'Error');
                return redirect()->back();
            }
        }else {
            Toastr::error("Password don't match", "Error");
            return redirect()->back();
        }
    }
}
