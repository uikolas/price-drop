<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductRetailerController;
use App\Http\Controllers\TriggerProductRetailerUpdate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('products.retailers', ProductRetailerController::class)
        ->shallow()
        ->only(['create', 'store', 'destroy']);
    Route::post('/retailers/{retailer}/trigger', TriggerProductRetailerUpdate::class)->name('trigger');
});


//Route::get('/notification', function () {
//    $productRetailer = \App\Models\ProductRetailer::find(1);
//
//    return (new \App\Notifications\PriceDrop($productRetailer))
//        ->toMail($productRetailer->product->user);
//});
