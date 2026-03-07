<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Product_upload_Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/sentry-test', function () {
    throw new Exception('Sentry Test Error');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/redis-test', function () {
    Redis::set('name', 'Laravel Redis');
    return Redis::get('name');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('product_upload', Product_upload_Controller::class);

    
    Route::get('/uploads/datatable', [DashboardController::class, 'datatable'])
        ->name('uploads.datatable');

    Route::get('/uploads/products/{upload}', [DashboardController::class, 'products'])
        ->name('uploads.products');

    Route::get('/uploads/logs', [DashboardController::class, 'logs'])
    ->name('uploads.logs');

    Route::get('/uploads/all_products', [DashboardController::class, 'all_products'])
    ->name('uploads.all_products');

    

});


require __DIR__.'/auth.php';
