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
    Route::post('order/note','OrdersController@update_notes')->name('order.notes.update');

    Route::get('backgrounds','BackgroundController@Backgrounds')->name('admin.background');
    Route::post('backgrounds/category/save','BackgroundController@Background_Categories_Save')->name('admin.background.categories.save');
    Route::post('backgrounds/save','BackgroundController@Background_Save')->name('admin.background.save');
    Route::get('backgrounds/{id}/delete','BackgroundController@Background_Delete')->name('admin.background.delete');

    Route::post('designer/save','DesignerController@Designer_Save')->name('admin.designer.save');
    Route::get('designer/dashboard','DesignerController@Dashboard')->name('admin.designer.dashboard');
    Route::post('/manaul-picker','DesignerController@ManualDesignPicker')->name('admin.manual-picker');
    Route::get('designer/status','DesignerController@SetStatus')->name('admin.designer.status');


    /*Filter Orders*/
    Route::get('orders/filter','OrdersController@filter_orders')->name('admin.orders.filter');
    Route::get('order/status','OrdersController@change_order_status')->name('admin.orders.change_status');
    Route::post('order/line-item/design-upload','OrdersController@design_upload')->name('admin.order.line-item.design.upload');
    Route::post('order/line-item/style-change','OrdersController@change_style')->name('admin.order.line-item.change.style');

});

Route::get('/sync/orders','OrdersController@GetShopifyOrders')->name('orders.sync');
//Route::get('admin/background','OrderController@getBackground')->name('admin.background');
Route::get('admin/login','OrderController@ManagementLogin')->name('admin.login');
//Route::get('admin/dashboard','OrderController@getDashboard')->name('management.dashboard');


Route::get('/customer','CustomerController@getLogin')->name('customer.login');
Route::get('/customer/logout','CustomerController@Logout')->name('customer.logout');

Route::get('/customer/order/overview','CustomerController@checkOrder')->name('customer.check');
Route::post('/customer/order/new_photo','CustomerController@NewPhoto')->name('customer.order.new_photo');
Route::post('/customer/order/request','CustomerController@RequestFix')->name('customer.order.request');
Route::get('/customer/order/{product}/background','CustomerController@ChangeBackground')->name('choose.background');
Route::post('/customer/order/background-save','CustomerController@SaveBackground')->name('order.save.background');
Route::get('/customer/order/save','CustomerController@SaveApproved')->name('order.save.status');
Route::post('/customer/order/review','CustomerController@SaveReview')->name('order.save.review');

