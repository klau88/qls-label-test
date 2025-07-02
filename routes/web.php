<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome');

    $order = app()->make('testOrder');

    return Inertia::render('Index', compact('order'));
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('orders', OrderController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';
