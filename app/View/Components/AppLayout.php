<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $hideSidebar;
    public $topNav;

    /**
     * Create a new component instance.
     */
    public function __construct($hideSidebar = false, $topNav = false)
    {
        $this->hideSidebar = $hideSidebar;
        $this->topNav = $topNav;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
