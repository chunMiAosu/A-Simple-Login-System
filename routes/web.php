<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');
Route::any('/registered','IndexController@registered');
Route::any('/login','IndexController@login');
Route::any('/logout','IndexController@logout');

//-------------------------------------------------------------------------------------------------
//后台登录路由
Route::get('/admin/login','Admin\LoginController@login');
Route::post('/admin/doLogin','Admin\LoginController@doLogin');

//没有权限
Route::get('noaccess','Admin\LoginController@noaccess');


Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => ['hasRole','isLogin']],function(){
    //后台首页路由
    Route::get('index','LoginController@index');
   
    //后台欢迎页
    Route::get('welcome','LoginController@welcome');

    //后台用户模块相关路由(使用资源路由)
    Route::get('user/del','UserController@delAll');
    Route::resource('user','UserController');

    //角色模块
    //角色授权路由
    Route::get('role/auth/{id}','RoleController@auth');
    Route::post('role/doAuth','RoleController@doAuth');
    Route::resource('role','RoleController');

    //分类路由
    Route::resource('category','CategoryController');
});
 //登录后退出路由
Route::get('/admin/logout','Admin\LoginController@logout');