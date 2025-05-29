<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('authenticate', [ApiController::class, 'authenticate'])->name('api.authenticate');
Route::get('products', [ApiController::class, 'products'])->name('api.products');
Route::get('shipments', [ApiController::class, 'shipments'])->name('api.shipments');
Route::post('label/create', [ApiController::class, 'getLabel'])->name('api.createLabel');
Route::get('label/generate', [ApiController::class, 'generateLabelPdf'])->name('api.generateLabel');
Route::get('label/retrieve/{id}', [ApiController::class, 'retrieveLabel'])->name('api.retrieveLabel');
Route::get('generatePackingSlip/{shipment}', [ApiController::class, 'generatePackingSlip'])->name('api.generatePackingSlip');
Route::get('generateImage/{id}', [ApiController::class, 'pdfToImage'])->name('api.generateImage');
