<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class BtnButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $url,
        public string $judul,
        public string $icon,
        public string $type,
        public bool $modal = false,
        public bool $buttonOnly = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract|Closure|string
    {
        return View::make('admin.layouts.components.buttons.btn');
    }
}
