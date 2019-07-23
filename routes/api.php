<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//公共路由无需登陆就可以访问
Route::group(['prefix'=>'v1'],function (){

    Route::get('/user', 'API\UsersController@getUser');

    Route::get('/cafes','API\CafesController@getCafes');

    Route::get('/cafes/{id}','API\CafesController@getCafe');

    //获取所有冲泡方法

    Route::get('/brew-methods','API\BrewMethodsController@getBrewMethods');

    //自动补全路由
    Route::get('/tags','API\TagsController@getTags');

});

//路由添加了一个 prefix 属性方便我们后续管理 API 的版本
//同时为群组路由设置了中间件 auth:api，意味着该群组中的所有路由都需要用户认证后才能访问
Route::group(['prefix'=>'v1','middleware'=>'auth:api'],function (){


    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Controller:     API\CafesController@postNewCafe
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
//    Route::post('/cafes','API\CafesController@postNewCafe');
    Route::post('/cafes','API\CafesController@postNewCafe')->name('abc');
    //喜欢咖啡店

    Route::post('/cafes/{id}/like','API\CafesController@postLikeCafe');

    //取消喜欢咖啡店
    Route::delete('/cafes/{id}/like','API\CafesController@deleteLikeCafe');
    //添加标签路由
    Route::post('/cafes/{id}/tags','API\CafesController@postAddTags');
    //删除标签路由
    Route::delete('/cafes/{id}/tags/{tagID}','API\CafesController@deleteCafeTag');

    Route::put('/user','API\UsersController@putUser');


    Route::get('/logout','API\UsersController@delLogout');



});






