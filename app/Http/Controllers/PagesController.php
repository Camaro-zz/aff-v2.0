<?php

namespace App\Http\Controllers;


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
        $check = Auth::check();
        if($check == 1){
            return view('admin.index');
        }else{
            return view(config('theme.default.pages').'.auth.login');
        }
        //return view(config('theme.default.pages').'.index')->withPosts($posts);
    }

    /**
     * Display the specified post.
     *
     * @param \App\Post $post
     */
    /*public function post(Post $post){
        //return view(config('theme.default.pages').'.post')->withPost($post);
    }*/

    /**
     * Display the posts of specified category.
     *
     * @param \App\Category $category
     */
    /*public function category(Category $category){
        //$posts = PostsRepo::getCategoryPosts($category, 10, 'owner');
        //return view(config('theme.default.pages').'.category')->withPosts($posts);
    }*/
}
