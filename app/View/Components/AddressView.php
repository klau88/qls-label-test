<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddressView extends Component
{
    public $title;
    public $name;
    public $companyname;
    public $street;
    public $housenumber;
    public $postalcode;
    public $city;
    public $country;
    public $email;
    public $phone;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $name, $companyname, $street, $housenumber, $postalcode, $city, $country, $email, $phone)
    {
        $this->title = $title;
        $this->name = $name;
        $this->companyname = $companyname;
        $this->street = $street;
        $this->housenumber = $housenumber;
        $this->postalcode = $postalcode;
        $this->city = $city;
        $this->country = $country;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.address-view');
    }
}
