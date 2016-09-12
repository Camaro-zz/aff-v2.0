<?php

namespace App\Http\Controllers;


use App\Models\Setting;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PagesController extends Controller
{
    /**
     * Display the homepage.
     */
    public function home() {
        //$posts = PostsRepo::getPosts(10, 'owner');
        $setting = Setting::first();
        $check = Auth::check();
        if($check == 1){
            return view('admin.index');
        }else{
            return view(config('theme.default.pages').'.auth.login',$setting);
        }
        //return view(config('theme.default.pages').'.index')->withPosts($posts);
    }
}
