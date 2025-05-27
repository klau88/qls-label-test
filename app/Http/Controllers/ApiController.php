<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    private $api;
    public function authenticate()
    {
        $api = app()->make(Api::class);
        return $api->authenticate();
    }
}
