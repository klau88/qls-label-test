<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('authenticate', [ApiController::class, 'authenticate']);
