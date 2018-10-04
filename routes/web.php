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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*Admin Routes*/

Route::group(['as' => 'admin.','prefix' => 'admin','namespace' => 'Admin','middleware' => ['auth','admin']],function() {

    Route::get('dashboard','DashboardController@index')->name('dashboard');


    /*User Routes*/
    Route::resource('user','UserController');
    Route::get('user/restore/all','UserController@restoreAll')->name('user.restore.all');
    Route::post('user/restore','UserController@restoreSelected')->name('user.restore.selected');
    Route::get('user/restore/{id}','UserController@restoreSingle')->name('user.restore.single');

    /*Product Routes*/
    Route::resource('product','ProductController');
    Route::get('product/restore/all','ProductController@restoreAll')->name('product.restore.all');
    Route::post('product/restore','ProductController@restoreSelected')->name('product.restore.selected');
    Route::get('product/restore/{id}','ProductController@restoreSingle')->name('product.restore.single');

    /*Supplier Routes*/
    Route::resource('supplier','SupplierController');
    Route::get('supplier/restore/all','SupplierController@restoreAll')->name('supplier.restore.all');
    Route::post('supplier/restore','SupplierController@restoreSelected')->name('supplier.restore.selected');
    Route::get('supplier/restore/{id}','SupplierController@restoreSingle')->name('supplier.restore.single');


    /*Lead Routes*/
    Route::resource('lead','LeadController');
    Route::get('lead/status/{id}/{status}','LeadController@leadStatus')->name('lead.status');
    Route::get('lead/restore/all','LeadController@restoreAll')->name('lead.restore.all');
    Route::post('lead/restore','LeadController@restoreSelected')->name('lead.restore.selected');
    Route::get('lead/restore/{id}','LeadController@restoreSingle')->name('lead.restore.single');
    Route::post('lead/note','LeadController@editNote')->name('lead.note.edit');

    Route::post('lead/sendTask','LeadController@sendTask')->name('lead.sendTask');
});


/*Caller Routes*/

Route::group(['as' => 'caller.','prefix' => 'caller','namespace' => 'Caller','middleware' => ['caller']],function() {

    Route::get('dashboard','DashboardController@index')->name('dashboard');
    Route::post('lead/note','LeadController@editNote')->name('lead.note.edit');
    Route::get('lead/status/{id}/{status}','LeadController@leadStatus')->name('lead.status');

});


/*API ROUTES*/

Route::any('api/order','Api\ApiController@Order')->name('api.order');
