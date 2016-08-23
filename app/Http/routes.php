<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'web'], function () {
    //homepage
    Route::get('/', ['as' => 'web.home', 'uses' => 'PagesController@home']);
    Route::get('blog', ['as' => 'web.blog', 'uses' => 'PagesController@home']);
    Route::get('/blog/{posts}', ['as' => 'web.post', 'uses' => 'PagesController@post']);
    Route::get('/category/{categories}', ['as' => 'web.category', 'uses' => 'PagesController@category']);

});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'Api'], function () {
    //posts
    Route::post('posts/{posts}/publish', ['as' => 'api.posts.publish', 'uses' => 'PostsController@publish']);
    Route::post('posts/{posts}/image', ['as' => 'api.posts.updateImage', 'uses' => 'PostsController@updateImage']);
    Route::resource('posts', 'PostsController', ['except' => ['create', 'edit']]);

    //categories
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);

    //posts categories
    Route::patch('posts/{posts}/categories', ['as' => 'api.posts.categories.sync', 'uses' => 'PostsCategoriesController@sync']);
    Route::resource('posts.categories', 'PostsCategoriesController', ['only' => ['index', 'store', 'destroy']]);

    //categories posts
    Route::get('categories/{categories}/posts', [ 'as' => 'api.categories.posts.index', 'uses' => 'CategoriesPostsController@index']);

    //users
    Route::get('me', ['as' => 'api.me.show', 'uses' => 'MeController@show']);
    Route::patch('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);
    Route::put('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);
});


/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'authorized:view-dashboard'], function () {
    Route::get('/{vue_capture?}', function () {
        return view('admin.index');
    })->where('vue_capture', '[\/\w\.-]*');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::auth();


Route::group(['prefix' => 'camp'], function () {
    Route::get('list.json', 'Campaigns\CampaignsController@getCamps');//获取campaigns列表
    Route::get('list_num.json', 'Campaigns\CampaignsController@getCampsNum');//获取campaign数量
    Route::put('edit/{camp_id}.json', 'Campaigns\CampaignsController@putCamp');//修改campaign内容
    Route::get('user_num.json', 'Campaigns\CampaignsController@getUsersNum');//获取users数量

    Route::get('info/{camp_id}.json', 'Campaigns\CampaignsController@getCamp');

    Route::get('users.json', 'Campaigns\CampaignsController@getUsers');//获取users列表

    Route::get('{camp_id}/offer.json', 'Campaigns\CampaignsController@getOffer');//根据活动id获取offer内容
    Route::put('offer.json', 'Campaigns\CampaignsController@putOffer');//修改LP内容

    Route::get('{camp_id}/lp.json', 'Campaigns\CampaignsController@getLP');//根据活动id获取lp内容
    Route::put('lp.json', 'Campaigns\CampaignsController@putLP');//修改LP内容

    Route::get('user_camps/{id}.json', 'Campaigns\CampaignsController@getCampUsers');//获取用户对应的camps
    Route::post('user_camps/{id}.json', 'Campaigns\CampaignsController@postCampUsers');

    Route::get('tokens/{id}.json', 'Campaigns\CampaignsController@getGroupByOption');
});
