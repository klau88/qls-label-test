<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use Inertia\Inertia;

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

    public function label()
    {
        $order = app()->make('testOrder');

        return Inertia::render('Label', [
            'order' => $order,
            'csrf' => csrf_token()
        ]);
    }

    public function postLabel()
    {
        $order = app()->make('testOrder');
        $shipment = $this->api()->mapOrderToShipment($order);

        return $this->api()->postLabel($shipment);
    }
}
