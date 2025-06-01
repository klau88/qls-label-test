<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductRow extends Component
{
    public $title;
    public $value;
    public $price;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $value, $price)
    {
        $this->title = $title;
        $this->value = $value;
        $this->price = $price;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-row');
    }
}
