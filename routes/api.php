<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('generatePdf', [ApiController::class, 'generatePdf'])->name('api.generated');
