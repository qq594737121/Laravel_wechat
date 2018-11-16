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
error_reporting(0);

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::get('admin/verify_image', 'Admin\Index\LoginController@verify_image'); 
Route::get("menu/getMenuInfo",'MenuController@getMenuInfo');
// 后台功能管理 **********
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
	//登陆
	Route::get('admin/index', 'Index\IndexController@index');
	Route::any('login/index', 'Index\LoginController@index');
	Route::any('login/signin','Index\LoginController@signin');
	Route::any('login/logout','Index\LoginController@logout');
	//机构
	Route::any('Group/index','Index\GroupController@index');
	Route::any('Group/create','Index\GroupController@create');
	Route::any('Group/store','Index\GroupController@store');
	Route::any('Group/edit','Index\GroupController@edit');
	Route::any('Group/update','Index\GroupController@update');
	Route::any('Group/enable','Index\GroupController@enable');
	Route::any('Group/destroy','Index\GroupController@destroy');
	Route::any('Group/seluser','Index\GroupController@seluser');
	//角色
	Route::any('Permis/index','Index\PermisController@index');
	Route::any('Permis/edit','Index\PermisController@edit');
	Route::any('Permis/update','Index\PermisController@update');
	Route::any('Permis/enable','Index\PermisController@enable');
	Route::any('Permis/store','Index\PermisController@store');
	Route::any('Permis/setpermis','Index\PermisController@setpermis');
	Route::any('Permis/permismodule','Index\PermisController@permismodule');
	//用户
	Route::any('User/index','Index\UserController@index');
	Route::any('User/create','Index\UserController@create');
	Route::any('User/enable','Index\UserController@enable');
	Route::any('User/deluser','Index\UserController@deluser');
	Route::any('User/editpass','Index\UserController@editpass');
	Route::any('User/store','Index\UserController@store');
	Route::any('User/edit','Index\UserController@edit');
	Route::any('User/update','Index\UserController@update');

	//无限级分类
	Route::any('Category/index','Index\CategoryController@index');
	Route::any('Category/add','Index\CategoryController@add');
	Route::any('Category/updates','Index\CategoryController@updates');
	Route::any('Category/xg','Index\CategoryController@xg');
	Route::any('Category/deletes','Index\CategoryController@deletes');
	//轮播图
	Route::any('CarouselImage/index','Index\CarouselImageController@index');
	Route::any('CarouselImage/add_group_carousel','Index\CarouselImageController@add_group_carousel');
	Route::any('CarouselImage/save_group_carousel','Index\CarouselImageController@save_group_carousel');
	Route::any('CarouselImage/edit_carousel_image','Index\CarouselImageController@edit_carousel_image');
	Route::any('CarouselImage/get_child_carousel','Index\CarouselImageController@get_child_carousel');
	Route::any('CarouselImage/add_child_carousel','Index\CarouselImageController@add_child_carousel');
	Route::any('CarouselImage/upload_image','Index\CarouselImageController@upload_image');
	Route::any('CarouselImage/show','Index\CarouselImageController@show');
	Route::any('CarouselImage/no_show','Index\CarouselImageController@no_show');
	Route::any('CarouselImage/get_child_carousel_details','Index\CarouselImageController@get_child_carousel_details');
	//会员管理
	Route::any('MemberManage/index','Member\MemberManageController@index');
});

//订单
Route::group(['namespace' => 'Home', 'prefix' => 'home'], function() {
	Route::any('Orders/status','Orders\OrdersController@status');
	Route::any('Orders/unified','Orders\OrdersController@unified');
	Route::any('Orders/redpack','Orders\OrdersController@redpack');
});
Route::group(['middleware'=>['CheckOpenid']],function()
{
	Route::get('home/index/index','Home\IndexController@index');
	Route::get('home/index/homepage','Home\IndexController@homepage');
});


//二维码公共相关
Route::get('common/getCode', 'CommonController@getCode');
Route::get('common/getCodeUnlimit', 'CommonController@getCodeUnlimit');
Route::get('common/getQrCode', 'CommonController@getQrCode');
Route::get('common/GetUrl', 'CommonController@GetUrl');
Route::post('common/qiniu_upload','CommonController@qiniu_upload');

// 小程序登录
Route::prefix('login')->group(function () {
	// 登陆
	Route::post('code', 'Mini\WechatMiniLoginController@code');
	// 用户信息
	Route::post('user', 'Mini\WechatMiniLoginController@user');
	// 自动登陆
	Route::get('auto', 'Mini\CommonController@autoLogin');
});


Route::get('home/index/ueditor','Home\IndexController@ueditor');

Route::get('test/send', 'TestController@send');             				  //测试邮件
Route::get('test/userinfo', 'TestController@userinfo');             		  //测试邮件
Route::get('test/send_template', 'TestController@send_template');             //测试微信模板消息
Route::get('test/send_mini_message', 'TestController@send_mini_message');     //测试小程序模板消息



