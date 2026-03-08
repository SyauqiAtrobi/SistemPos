<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LocationPicker extends Component
{
    /**
     * Optional initial latitude
     */
    public $lat;

    /**
     * Optional initial longitude
     */
    public $lng;

    public function __construct($lat = null, $lng = null)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function render()
    {
        return view('components.location-picker');
    }
}
