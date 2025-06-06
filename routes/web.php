<?php

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

Route::controller(ShipmentController::class) ->group(function() {
    Route::get('shipments', 'index')->name('shipments.index');
    Route::get('shipments/create', 'create')->name('shipments.create');
    Route::post('shipments/store', 'store')->name('shipments.store');
    Route::get('shipments/{id}', 'show')->name('shipments.show');
    Route::get('shipments/{id}/edit', 'edit')->name('shipments.edit');
    Route::put('shipments/{id}/update', 'update')->name('shipments.update');
    Route::delete('shipments/{id}/destroy', 'destroy')->name('shipments.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';
