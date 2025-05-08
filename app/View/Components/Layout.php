<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Layout extends Component
{
    public $hideNavbar;
    public $isAdmin;
    /**
     * Create a new component instance.
     */
    public function __construct($hideNavbar = false, $isAdmin = false)
    {
        $this->hideNavbar = $hideNavbar;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout');
    }
}
