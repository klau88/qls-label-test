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

    private function fetch()
    {
        return Http::withBasicAuth($this->username, $this->password);
    }

    public function authenticate()
    {
        return $this->fetch()->get(config('api.url'));
    }
}
