<?php

namespace App\Http\Controllers;

use App\Subscribe;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
           'email' => 'required|email|unique:subscribes'
        ]);

        $subs = new Subscribe();
        $subs->email = $request->email;
        $subs->save();
        Toastr::success('Congratulation you are now a subscriber', 'Success');
        return redirect()->back();
    }
}
