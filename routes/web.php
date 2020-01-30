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


Route::group(['prefix' => 'admin', 'middleware' => ['auth.shop']], function(){
    Route::get('/', 'OrderController@getHome')->name('home');
    Route::get('orders', 'OrdersController@Orders')->name('admin.orders');
    Route::get('order/{id}/detail','OrdersController@OrderDetails')->name('order.detail');

    Route::get('backgrounds','BackgroundController@Backgrounds')->name('admin.background');
    Route::post('backgrounds/category/save','BackgroundController@Background_Categories_Save')->name('admin.background.categories.save');
    Route::post('backgrounds/save','BackgroundController@Background_Save')->name('admin.background.save');
    Route::get('backgrounds/{id}/delete','BackgroundController@Background_Delete')->name('admin.background.delete');

    Route::post('designer/save','DesignerController@Designer_Save')->name('admin.designer.save');
    Route::get('designer/dashboard','DesignerController@Dashboard')->name('admin.designer.dashboard');

});

//Route::get('admin/orders','OrderController@getOrders')->name('admin.orders');
//Route::get('admin/background','OrderController@getBackground')->name('admin.background');
Route::get('admin/login','OrderController@ManagementLogin')->name('admin.login');
//Route::get('admin/dashboard','OrderController@getDashboard')->name('management.dashboard');


//Route::get('/login','CustomerController@getLogin')->name('customer.login');
Route::get('/overview','CustomerController@getOrderOverview')->name('order.overview');

Route::get('/change/background','CustomerController@ChangeBackground')->name('choose.background');
