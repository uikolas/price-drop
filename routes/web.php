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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', 'ProductController');

Route::get('product-retailers/create/{product}', [
    'as'   => 'product-retailers.create',
    'uses' => 'ProductRetailerController@create'
]);
Route::post('product-retailers/store/{product}', [
    'as'   => 'product-retailers.store',
    'uses' => 'ProductRetailerController@store'
]);
Route::resource('product-retailers', 'ProductRetailerController', [
    'parameters' => [
        'product-retailers' => 'productRetailer'
    ],
    'except' => [
        'index', 'show', 'create', 'store'
    ]
]);

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');
