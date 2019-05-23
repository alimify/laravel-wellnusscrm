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


Route::any('/','Api\ApiController@adcomboOrderIndex')->name('index');
Route::any('/index.php','Api\ApiController@adcomboOrderIndex')->name('index.php');

Auth::routes();

Route::get('/home', 'HomeController@home')->name('home');


/*Admin Routes*/

Route::group(['as' => 'admin.','prefix' => 'admin','namespace' => 'Admin','middleware' => ['auth','admin']],function() {

    Route::get('dashboard','DashboardController@index')->name('dashboard');
    Route::any('dashboard/ajax/data','DashboardController@indexAjax')->name('dashboard.ajax');


    /*User Routes*/
    Route::resource('user','UserController');
    Route::get('user/restore/all','UserController@restoreAll')->name('user.restore.all');
    Route::post('user/restore','UserController@restoreSelected')->name('user.restore.selected');
    Route::get('user/restore/{id}','UserController@restoreSingle')->name('user.restore.single');

    /*Product Routes*/
    Route::resource('product','ProductController');
    Route::get('product/restore/all','ProductController@restoreAll')->name('product.restore.all');
    Route::post('product/restore','ProductController@restoreSelected')->name('product.restore.selected');
    Route::any('product/restore/{id}','ProductController@restoreSingle')->name('product.restore.single');

    /*Supplier Routes*/
    Route::resource('supplier','SupplierController');
    Route::get('supplier/restore/all','SupplierController@restoreAll')->name('supplier.restore.all');
    Route::post('supplier/restore','SupplierController@restoreSelected')->name('supplier.restore.selected');
    Route::get('supplier/restore/{id}','SupplierController@restoreSingle')->name('supplier.restore.single');


    /*Lead Routes*/
    Route::resource('lead','LeadController');

    Route::any('lead/status/{id}/{status}','LeadController@leadStatus')->name('lead.status');
    Route::get('lead/restore/all','LeadController@restoreAll')->name('lead.restore.all');
    Route::post('lead/restore','LeadController@restoreSelected')->name('lead.restore.selected');
    Route::any('lead/restore/{id}','LeadController@restoreSingle')->name('lead.restore.single');
    Route::post('lead/note','LeadController@editNote')->name('lead.note.edit');

    Route::post('lead/sendTask','LeadController@sendTask')->name('lead.sendTask');

    Route::any('lead/ajax/data','LeadController@indexAjax')->name('lead.ajax');
    Route::any('lead/ajax/same','LeadController@sameLead')->name('lead.ajax.same');

});


/*Caller Routes*/

Route::group(['as' => 'caller.','prefix' => 'caller','namespace' => 'Caller','middleware' => ['caller']],function() {

    Route::get('all','DashboardController@all')->name('all');
    Route::get('dashboard','DashboardController@index')->name('dashboard');

    Route::any('allAjax','DashboardController@allAjax')->name('lead.all.ajax.data');
    Route::any('indexAjax','DashboardController@indexAjax')->name('lead.index.ajax.data');
    Route::any('lead/ajax/same','DashboardController@sameLead')->name('lead.ajax.same');

    Route::any('lead/note','LeadController@editNote')->name('lead.note.edit');
    Route::any('lead/address','LeadController@editAddress')->name('lead.address.edit');
    Route::any('lead/status/{id}/{status}','LeadController@leadStatus')->name('lead.status');

});


/*API ROUTES*/

Route::any('api/order','Api\ApiController@Order')->name('api.order');
Route::any('status.php','Api\ApiController@status')->name('status.php');
