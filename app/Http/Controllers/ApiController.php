<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    private function api()
    {
        return app()->make(Api::class);
    }

    public function authenticate()
    {
        return $this->api()->authenticate();
    }

    public function products()
    {
        return $this->api()->getProducts();
    }
}
