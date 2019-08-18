<?php

namespace App\Http\Controllers\Admin;

use App\Subscribe;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscribeController extends Controller
{
    public function index(){
        $subscribers = Subscribe::latest()->get();
        return view('admin.subscribe', compact('subscribers'));
    }

    public function destroy ($id){
        $subscriber = Subscribe::findOrFail($id)->delete();
        Toastr::success('Unsubscribe Successfully', 'Success');
        return redirect()->back();
    }
}
