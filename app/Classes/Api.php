<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Api
{
    private $url = 'https://api.pakketdienstqls.nl/';
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate()
    {
        return Http::withBasicAuth($this->username, $this->password)->get(config('api.url'));
    }
}
