<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('authenticate', [ApiController::class, 'authenticate'])->name('api.authenticate');
Route::get('products', [ApiController::class, 'products'])->name('api.products');
Route::post('label/create/{shipment}', [ApiController::class, 'getLabel'])->name('api.createLabel');
Route::post('generatePdf', [ApiController::class, 'generatePdf'])->name('api.generated');
