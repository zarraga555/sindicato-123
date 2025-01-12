<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $pageTitle;
    public $breadcrumbMainUrl;
    public $breadcrumbMain;
    public $breadcrumbCurrent;

    /**
     * Create a new component instance.
     */
public function __construct($pageTitle, $breadcrumbMainUrl, $breadcrumbMain, $breadcrumbCurrent)
{
    $this->pageTitle = $pageTitle;
    $this->breadcrumbMainUrl = $breadcrumbMainUrl;
    $this->breadcrumbMain = $breadcrumbMain;
    $this->breadcrumbCurrent = $breadcrumbCurrent;
}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
